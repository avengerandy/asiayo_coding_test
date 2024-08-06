<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Services\OrderService;
use App\Exceptions\OrderCheckerException;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    public function index(OrderRequest $orderRequest, OrderService $orderService) {
        $orderData = $orderRequest->validated();
        try {
            $orderData = $orderService->transform($orderData);
        } catch (OrderCheckerException $exception) {
            $errorMessage = [
                'message' => $exception->getMessage()
            ];
            return Response::json($errorMessage, 400);
        }
        return Response::json($orderData);
    }
}
