<?php

namespace App\Order\OrderChecker;

class OverPrice extends Checker
{
    public const MAX_PRICE = 2000;

    public $errorMessage = 'Price is over 2000';

    protected function handle($orderData): bool
    {
        $price = (int) $orderData['price'];
        $isOverPrice = ($price > self::MAX_PRICE);
        return !$isOverPrice;
    }
}
