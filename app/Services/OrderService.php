<?php

namespace App\Services;

use App\Order\OrderCheckerChainFactory;
use App\Order\OrderTransformer;

class OrderService
{
    private $orderCheckerChainFactory;
    private $orderTransformer;

    public function __construct(OrderCheckerChainFactory $orderCheckerChainFactory, OrderTransformer $orderTransformer)
    {
        $this->orderCheckerChainFactory = $orderCheckerChainFactory;
        $this->orderTransformer = $orderTransformer;
    }

    public function transform(array $orderData): array
    {
        $orderCheckerChain = $this->orderCheckerChainFactory->create();
        $orderCheckerChain->check($orderData);
        $orderData = $this->orderTransformer->transform($orderData);
        return $orderData;
    }
}
