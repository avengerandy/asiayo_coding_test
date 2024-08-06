<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Order\OrderCurrency;
use App\Order\OrderChecker\WrongCurrencyFormat;
use App\Exceptions\OrderCheckerException;

class WrongCurrencyFormatTest extends TestCase
{
    public function test_errorMessage_is_wrong_currency_format(): void
    {
        $wrongCurrencyFormat = new WrongCurrencyFormat();
        $expectErrorMessage = 'Currency format is wrong';
        $actualErrorMessage = $wrongCurrencyFormat->errorMessage;

        $this->assertEquals($expectErrorMessage, $actualErrorMessage);
    }

    public function test_check_pass_when_currency_format_correct(): void
    {
        $orderData = ['currency' => OrderCurrency::TWD_FORMAT];
        $wrongCurrencyFormat = new WrongCurrencyFormat();
        $mockNextChecker = $this->mock(WrongCurrencyFormat::class);
        $mockNextChecker->expects()->check($orderData)->once();
        $wrongCurrencyFormat->setNext($mockNextChecker);
        $wrongCurrencyFormat->check($orderData);

        $orderData = ['currency' => OrderCurrency::USD_FORMAT];
        $wrongCurrencyFormat = new WrongCurrencyFormat();
        $mockNextChecker = $this->mock(WrongCurrencyFormat::class);
        $mockNextChecker->expects()->check($orderData)->once();
        $wrongCurrencyFormat->setNext($mockNextChecker);
        $wrongCurrencyFormat->check($orderData);
    }

    public function test_check_fail_when_currency_format_wrong(): void
    {
        $orderData = ['currency' => 'wrong currency'];
        $wrongCurrencyFormat = new WrongCurrencyFormat();

        $this->assertThrows(
            fn () => $wrongCurrencyFormat->check($orderData),
            fn (OrderCheckerException $e) => $e->getMessage() === $wrongCurrencyFormat->errorMessage
        );
    }
}
