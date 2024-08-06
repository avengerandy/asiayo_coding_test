<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Order\OrderChecker\NotCapitalized;
use App\Exceptions\OrderCheckerException;

class NotCapitalizedTest extends TestCase
{
    public function test_errorMessage_is_not_capitalized(): void
    {
        $notCapitalized = new NotCapitalized();
        $expectErrorMessage = 'Name is not capitalized';
        $actualErrorMessage = $notCapitalized->errorMessage;

        $this->assertEquals($expectErrorMessage, $actualErrorMessage);
    }

    public function test_check_pass_when_name_is_capitalized(): void
    {
        $orderData = ['name' => 'Melody Holiday Inn'];
        $notCapitalized = new NotCapitalized();
        $mockNextChecker = $this->mock(NotCapitalized::class);
        $mockNextChecker->expects()->check($orderData)->once();
        $notCapitalized->setNext($mockNextChecker);
        $notCapitalized->check($orderData);
    }

    public function test_check_fail_when_name_is_not_capitalized(): void
    {
        $orderData = ['name' => 'Melody holiday Inn'];
        $notCapitalized = new NotCapitalized();

        $this->assertThrows(
            fn () => $notCapitalized->check($orderData),
            fn (OrderCheckerException $e) => $e->getMessage() === $notCapitalized->errorMessage
        );
    }
}
