<?php

namespace App\Order;

use App\Order\OrderChecker\Checker;
use App\Order\OrderChecker\NonEngilsh;
use App\Order\OrderChecker\NotCapitalized;
use App\Order\OrderChecker\OverPrice;
use App\Order\OrderChecker\WrongCurrencyFormat;

class OrderCheckerChainFactory
{
    private $nonEngilsh;
    private $notCapitalized;
    private $overPrice;
    private $wrongCurrencyFormat;

    public function __construct(NonEngilsh $nonEngilsh, NotCapitalized $notCapitalized, OverPrice $overPrice, WrongCurrencyFormat $wrongCurrencyFormat)
    {
        $this->nonEngilsh = $nonEngilsh;
        $this->notCapitalized = $notCapitalized;
        $this->overPrice = $overPrice;
        $this->wrongCurrencyFormat = $wrongCurrencyFormat;
    }

    public function create(): Checker
    {
        $this->nonEngilsh->setNext($this->notCapitalized);
        $this->notCapitalized->setNext($this->overPrice);
        $this->overPrice->setNext($this->wrongCurrencyFormat);
        return $this->nonEngilsh;
    }
}
