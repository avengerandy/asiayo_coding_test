<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Order\OrderChecker\OverPrice;
use App\Exceptions\OrderCheckerException;

class OverPriceTest extends TestCase
{
    public function test_errorMessage_is_over_price(): void
    {
        $overPrice = new OverPrice();
        $expectErrorMessage = 'Price is over 2000';
        $actualErrorMessage = $overPrice->errorMessage;

        $this->assertEquals($expectErrorMessage, $actualErrorMessage);
    }

    public function test_check_pass_when_price_not_greater_than_2000(): void
    {
        $orderData = ['price' => '1000'];
        $overPrice = new OverPrice();
        $mockNextChecker = $this->mock(OverPrice::class);
        $mockNextChecker->expects()->check($orderData)->once();
        $overPrice->setNext($mockNextChecker);
        $overPrice->check($orderData);

        $orderData = ['price' => '2000'];
        $overPrice = new OverPrice();
        $mockNextChecker = $this->mock(OverPrice::class);
        $mockNextChecker->expects()->check($orderData)->once();
        $overPrice->setNext($mockNextChecker);
        $overPrice->check($orderData);
    }

    public function test_check_fail_when_price_greater_than_2000(): void
    {
        $orderData = ['price' => '2100'];
        $overPrice = new OverPrice();

        $this->assertThrows(
            fn () => $overPrice->check($orderData),
            fn (OrderCheckerException $e) => $e->getMessage() === $overPrice->errorMessage
        );
    }
}
