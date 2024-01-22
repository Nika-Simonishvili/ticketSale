<?php

namespace App\Providers;

use App\Contracts\BookingRepositoryContract;
use App\Contracts\OrderRepositoryContract;
use App\Contracts\TicketRepositoryContract;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TicketRepository;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Interface bindings
        $this->app->bind(TicketRepositoryContract::class, TicketRepository::class);
        $this->app->bind(BookingRepositoryContract::class, BookingRepository::class);
        $this->app->bind(OrderRepositoryContract::class, OrderRepository::class);

        // Response macros
        Response::macro('success', function (array $message) {
            $responseData = array_merge(['success' => true], $message);

            return \response()->json($responseData);
        });

        Response::macro('error', function (array $message, ?int $statusCode = 400) {
            $responseData = array_merge(['success' => false], $message);

            return \response()->json($responseData, $statusCode);
        });
    }
}
