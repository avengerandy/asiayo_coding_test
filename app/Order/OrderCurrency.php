<?php

namespace App\Order;

class OrderCurrency
{
    public const TWD_FORMAT = 'TWD';
    public const USD_FORMAT = 'USD';
    public const USD_EXCHANGE_RATE = 31;
    public const CURRENCY_FORMAT = [self::TWD_FORMAT, self::USD_FORMAT];
}
