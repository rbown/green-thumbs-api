<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class ApiController extends Controller
{
    /**
     * Model Class this controller caters.
     *
     * @var string
     */
    protected string $modelClass;

    /**
     * Policy Class this controller refers to.
     *
     * @var string
     */
    protected string $policyClass;

    /**
     * Collection Class the above assigned Model uses.
     *
     * @var string
     */
    protected string $collectionClass;

    /**
     * Resource Class the above assigned Model uses.
     *
     * @var string
     */
    protected string $resourceClass;

    /**
     * Filter Class the above assigned Model uses.
     *
     * @var string
     */
    protected string $filterClass;

    /**
     * Request instance.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * Request Rules.
     *
     * @var array
     */
    protected array $requestRules;

    /**
     * Service CLass.
     *
     * @var string
     */
    protected $serviceClass;

    /**
     * Get Model Class this controller caters.
     *
     * @return string
     */
    abstract protected function getModelClass() : string;

    /**
     * Get Policy Class this controller refers to.
     *
     * @return string
     */
    abstract protected function getPolicyClass() : string;

    /**
     * Get Collection Class the above assigned Model uses.
     *
     * @return string
     */
    abstract protected function getCollectionClass() : string;

    /**
     * Get Resource Class the above assigned Model uses.
     *
     * @return string
     */
    abstract protected function getResourceClass() : string;

    /**
     * Get Filter Class the above assigned Model uses.
     *
     * @return string
     */
    abstract protected function getFilterClass() : string;

    /**
     * Get Request Rules the above assigned Model uses.
     *
     * @return array
     */
    abstract protected function getRequestRules() : array;

    /**
     * Get Request Rules the above assigned Model uses.
     *
     * @return string
     */
    abstract protected function getServiceClass() : string;

    /**
     * ApiController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->modelClass = $this->getModelClass();
        $this->policyClass = $this->getPolicyClass();
        $this->collectionClass = $this->getCollectionClass();
        $this->resourceClass = $this->getResourceClass();
        $this->filterClass = $this->getFilterClass();
        $this->requestRules = $this->getRequestRules();
        $this->serviceClass = $this->getServiceClass();
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return ResourceCollection|JsonResponse
     * @throws AuthorizationException
     */
    public function index(): ResourceCollection|JsonResponse
    {
        $this->authoriseAction('index');

        return response()->json([
            'data' => new $this->collectionClass(
                $this->modelClass::when(
                    $this->filterClass,
                    fn ($query) => $query->filter(
                        new $this->filterClass,
                        json_decode($this->request->get('filters'))
                    )
                )->get()
            )
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(): JsonResponse
    {
        if ($this->requestRules){
            $this->request->validate($this->requestRules);
        }

        $this->authoriseAction('create');

        $model = new $this->modelClass();
        $model->fill($this->request->all());
        $model->save();

        return response()->json([
            'message' => 'Successfully Created',
            'data' => new $this->resourceClass($model)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(string $id): JsonResponse
    {
        $model = $this->modelClass::firstWhere('id', $id);

        $this->authoriseAction('view', $model);

        if ($model) {
            return response()->json([
                'data' => new $this->resourceClass($model)
            ]);
        }

        return response()->json([
            'message' => 'Not Found'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(string $id): JsonResponse
    {
        if ($this->requestRules){
            $this->request->validate($this->requestRules);
        }

        $model = $this->modelClass::firstWhere('id', $id);

        $this->authoriseAction('update', $model);

        if ($model) {
            $model->fill($this->request->all());
            $model->save();

            return response()->json([
                'data' => new $this->resourceClass($model)
            ]);
        }

        return response()->json([], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(string $id): JsonResponse
    {
        $model = $this->modelClass::find($id);

        $this->authoriseAction('delete', $model);

        if ($model) {
            try {
                $model->delete();

                return response()->json([
                    'message' => 'Successfully Deleted'
                ], 204);
            } catch(QueryException $e){
                if ($e->getCode() === '23000'){
                    return response()->json([
                        'message' => 'Unable to delete a record that has been used'
                    ], 422);
                }

                return response()->json([
                    'message' => 'Error Deleting Record'
                ], 422);
            }
        }

        return response()->json([
            'message' => 'Not Found'
        ], 404);
    }

    /**
     * Authorise action.
     *
     * @param $action
     * @param null $model
     * @throws AuthorizationException
     */
    protected function authoriseAction($action, $model = null)
    {
        if (class_exists($this->policyClass)) {
            $this->authorize($action, is_null($model) ? $this->modelClass : $model);
        }
    }
}
