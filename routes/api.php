<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login')->name('login');

    //Socialite
    Route::get('/login/{provider}', 'socialiteAuthRedirect');
    Route::get('/socialite/callback', 'socialiteGoogleAuthCallback');
    Route::delete('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('events')
            ->controller(EventController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{event}', 'show');
                Route::post('/', 'store');
                Route::patch('/{event}', 'update');
                Route::get('/available-tickets/{event}', 'availableTickets');
                Route::delete('/{event}', 'destroy');
            });

        Route::prefix('tickets')
            ->controller(TicketController::class)
            ->group(function () {
                Route::post('/{eventId}/import-tickets', 'importTickets');
            });

        Route::prefix('orders')
            ->controller(OrderController::class)
            ->group(function () {
                Route::post('/', 'store');
                Route::patch('/cancel/{order}', 'cancel');
            });

        Route::prefix('booking')
            ->controller(BookingController::class)
            ->group(function () {
                Route::post('/checkout', 'bookTickets');

                Route::patch('cancel/{booking}', 'cancelBooking');

                // Those callback routes should be your frontend URLs, for sake of API i removed middlewares.
                Route::get('/callback/success', 'checkoutSuccess')
                    ->withoutMiddleware('auth:sanctum')
                    ->name('checkout.success');
                Route::get('/callback/cancel', 'checkoutFail')
                    ->withoutMiddleware('auth:sanctum')
                    ->name('checkout.cancel');
            });

        Route::apiResource('bookmarks', BookmarkController::class)->except(['update', 'show']);
        Route::apiResource('reviews', ReviewController::class)->except(['update', 'show']);
    });
