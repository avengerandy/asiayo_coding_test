<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Order\OrderCurrency;
use App\Order\OrderTransformer;

class OrderTransformerTest extends TestCase
{
    public function test_not_transform_data_when_currency_is_not_usd(): void
    {
        $orderData = [
            'currency' => OrderCurrency::TWD_FORMAT,
            'price' =>'100'
        ];
        $orderTransformer = new OrderTransformer();
        $actualOrderData = $orderTransformer->transform($orderData);

        $this->assertEquals($orderData, $actualOrderData);
    }

    public function test_transform_data_when_currency_is_usd(): void
    {
        $expectOrderData = [
            'currency' => OrderCurrency::TWD_FORMAT,
            'price' =>'3100'
        ];

        $orderData = [
            'currency' => OrderCurrency::USD_FORMAT,
            'price' =>'100'
        ];
        $orderTransformer = new OrderTransformer();
        $actualOrderData = $orderTransformer->transform($orderData);

        $this->assertEquals($expectOrderData, $actualOrderData);
    }
}
