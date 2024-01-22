<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $service)
    {
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $order = $this->service->store($request->validated());

        return Response::success([
            'order' => OrderResource::make($order),
            'message' => 'order created successfully.',
        ]);
    }

    public function cancel(Order $order): JsonResponse
    {
        $this->service->cancel($order);

        return Response::success([
            'message' => 'Order canceled.',
        ]);
    }
}
