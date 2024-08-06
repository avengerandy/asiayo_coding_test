<?php

namespace App\Order\OrderChecker;

class NotCapitalized extends Checker
{
    public $errorMessage = 'Name is not capitalized';

    protected function handle(array $orderData): bool
    {
        $pattern = '/^[A-Z][a-z]*(?:\s[A-Z][a-z]*)*$/';
        $isMatch = preg_match($pattern, $orderData['name']);
        return $isMatch;
    }
}
