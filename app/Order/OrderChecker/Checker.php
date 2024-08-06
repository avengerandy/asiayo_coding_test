<?php

namespace App\Order\OrderChecker;

use App\Exceptions\OrderCheckerException;

abstract class Checker
{
    protected $nextChecker = null;
    public $errorMessage = '';

    abstract protected function handle($orderData): bool;

    public function check($orderData): void
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

    public function throwException(): void
    {
        throw new OrderCheckerException($this->errorMessage);
    }
}
