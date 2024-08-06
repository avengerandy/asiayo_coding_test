<?php

namespace App\Order\OrderChecker;

use App\Order\OrderCurrency;

class WrongCurrencyFormat extends Checker
{
    public $errorMessage = 'Currency format is wrong';

    protected function handle(array $orderData): bool
    {
        $isCurrentFormat = in_array($orderData['currency'], OrderCurrency::CURRENCY_FORMAT);
        return $isCurrentFormat;
    }
}
