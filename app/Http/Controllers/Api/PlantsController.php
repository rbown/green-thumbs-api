<?php

namespace App\Http\Controllers\Api;

use App\Http\Filters\PlantFilter;
use App\Http\Resources\Plant\PlantCollection;
use App\Http\Resources\Plant\PlantResource;
use App\Models\Plant;
use App\Services\PlantService;
use Illuminate\Http\JsonResponse;

class PlantsController extends ApiController
{
    protected function getModelClass(): string
    {
        return Plant::class;
    }

    protected function getPolicyClass(): string
    {
        return '';
    }

    protected function getCollectionClass(): string
    {
        return PlantCollection::class;
    }

    protected function getResourceClass(): string
    {
        return PlantResource::class;
    }

    protected function getFilterClass(): string
    {
        return PlantFilter::class;
    }

    protected function getRequestRules(): array
    {
        return [
            'name' => 'required',
            'species' => 'required'
        ];
    }

    protected function getServiceClass(): string
    {
        return PlantService::class;
    }

    public function store(): JsonResponse
    {
        $service = new $this->serviceClass;

        if ($this->requestRules){
            $this->request->validate($this->requestRules);
        }

        $this->authoriseAction('create');

        $model = new $this->modelClass();
        $model->fill($this->request->except(['photo']));
        $model->photo = $service->handleFileUpload($this->request->file('photo'));
        $model->save();

        return response()->json([
            'message' => 'Successfully Created',
            'data' => new $this->resourceClass($model)
        ]);
    }
}
