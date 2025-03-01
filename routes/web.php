<?php

declare(strict_types=1);

use App\Http\GetBestSellers\GetBestSellersController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::prefix('/api')->group(static function (Router $router): void {
    $router->get('/best-sellers', GetBestSellersController::class)->name('best-sellers');
});
