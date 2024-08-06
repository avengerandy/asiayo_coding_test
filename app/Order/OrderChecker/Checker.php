<?php

namespace App\Order\OrderChecker;

use App\Exceptions\OrderCheckerException;

abstract class Checker
{
    protected $nextChecker = null;
    public $errorMessage = '';

    abstract protected function handle(array $orderData): bool;

    public function check(array $orderData): void
    {
        $result = $this->handle($orderData);
        if (!$result) {
            $this->throwException();
        }
        if ($result && !is_null($this->nextChecker)) {
            $this->nextChecker->check($orderData);
        }
    }

    public function setNext(Checker $checker): void
    {
        $this->nextChecker = $checker;
    }

    protected function throwException(): void
    {
        throw new OrderCheckerException($this->errorMessage);
    }
}
