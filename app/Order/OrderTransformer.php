<?php

namespace App\Order;

use App\Order\OrderCurrency;

class OrderTransformer
{
    public function transform(array $orderData)
    {
        if ($orderData['currency'] == OrderCurrency::USD_FORMAT) {
            $orderData['currency'] = OrderCurrency::TWD_FORMAT;
            $price = (int) $orderData['price'];
            $price = $price * OrderCurrency::USD_EXCHANGE_RATE;
            $orderData['price'] = (string) $price;
        }

        return $orderData;
    }
}
