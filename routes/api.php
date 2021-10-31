<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlantsController;

Route::resource('/plants', PlantsController::class);
