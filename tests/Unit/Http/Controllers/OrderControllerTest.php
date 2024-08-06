<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\OrderController;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Exceptions\OrderCheckerException;

class OrderControllerTest extends TestCase
{
    public function test_index_call_orderRequest_and_orderService_than_return_Response_json(): void
    {
        $orderData = ["testOrderData"];
        $mockOrderRequest = $this->mock(OrderRequest::class);
        $mockOrderRequest->expects()->validated()->once()->andReturn($orderData);
        $mockOrderService = $this->mock(OrderService::class);
        $mockOrderService->expects()->transform($orderData)->once()->andReturn($orderData);

        $expectResult = 'testResult';
        Response::shouldReceive('json')->with($orderData)->once()->andReturn($expectResult);

        $orderController = new OrderController();
        $actualResult = $orderController->index($mockOrderRequest, $mockOrderService);

        $this->assertSame($expectResult, $actualResult);
    }

    public function test_index_call_orderRequest_and_orderService_than_return_errorMessage_when_orderService_throw_exception(): void
    {
        $orderData = ["testOrderData"];
        $mockOrderRequest = $this->mock(OrderRequest::class);
        $mockOrderRequest->expects()->validated()->once()->andReturn($orderData);

        $expectErrorMessage = 'errorMessage';
        $exception = new OrderCheckerException($expectErrorMessage);
        $mockOrderService = $this->mock(OrderService::class);
        $mockOrderService->expects()->transform($orderData)->once()->andThrow($exception);

        $expectResult = 'testResult';
        Response::shouldReceive('json')->with([
            'message' => $expectErrorMessage
        ], 400)->once()->andReturn($expectResult);

        $orderController = new OrderController();
        $actualResult = $orderController->index($mockOrderRequest, $mockOrderService);

        $this->assertSame($expectResult, $actualResult);
    }
}
