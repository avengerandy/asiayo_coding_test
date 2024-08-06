<?php

namespace App\Order\OrderChecker;

class NonEngilsh extends Checker
{
    public $errorMessage = 'Name contains non-English characters';

    protected function handle(array $orderData): bool
    {
        $pattern = '/^[A-Za-z\s]+$/';
        $isMatch = preg_match($pattern, $orderData['name']);
        return $isMatch;
    }
}
