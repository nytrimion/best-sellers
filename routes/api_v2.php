<?php

declare(strict_types=1);

use App\Http\V2\GetBestSellers\GetBestSellersController;
use Illuminate\Support\Facades\Route;

Route::get('/best-sellers', GetBestSellersController::class)->name('best-sellers');
