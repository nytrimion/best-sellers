<?php

declare(strict_types=1);

use app\Http\V1\GetBestSellers\GetBestSellersController;
use Illuminate\Support\Facades\Route;

Route::get('/best-sellers', GetBestSellersController::class)->name('best-sellers');
