<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\OrderService;
use App\Order\OrderTransformer;
use App\Order\OrderCheckerChainFactory;
use App\Order\OrderChecker\NonEngilsh;

class OrderServiceTest extends TestCase
{
    public function test_transform_call_orderCheckerChain_and_orderTransformer(): void
    {
        $orderData = ['name' => 'Melody Holiday Inn'];
        $expectOrderData = ['transform' => true];

        $mockCheckerChain = $this->mock(NonEngilsh::class);
        $mockCheckerChain->expects()->check($orderData)->once();

        $mockOrderCheckerChainFactory = $this->mock(OrderCheckerChainFactory::class);
        $mockOrderCheckerChainFactory->expects()->create()->once()->andReturn($mockCheckerChain);

        $mockOrderTransformer = $this->mock(OrderTransformer::class);
        $mockOrderTransformer->expects()->transform($orderData)->once()->andReturn($expectOrderData);

        $orderService = new OrderService($mockOrderCheckerChainFactory, $mockOrderTransformer);
        $actualOrderData = $orderService->transform($orderData);

        $this->assertEquals($expectOrderData, $actualOrderData);
    }
}
