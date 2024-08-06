<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Order\OrderChecker\NonEngilsh;
use App\Exceptions\OrderCheckerException;

class NonEngilshTest extends TestCase
{
    public function test_errorMessage_is_non_English(): void
    {
        $nonEngilsh = new NonEngilsh();
        $expectErrorMessage = 'Name contains non-English characters';
        $actualErrorMessage = $nonEngilsh->errorMessage;

        $this->assertEquals($expectErrorMessage, $actualErrorMessage);
    }

    public function test_check_pass_when_name_is_English_only(): void
    {
        $orderData = ['name' => 'Melody Holiday Inn'];
        $nonEngilsh = new NonEngilsh();
        $mockNextChecker = $this->mock(NonEngilsh::class);
        $mockNextChecker->expects()->check($orderData)->once();
        $nonEngilsh->setNext($mockNextChecker);
        $nonEngilsh->check($orderData);
    }

    public function test_check_fail_when_name_is_contains_non_English(): void
    {
        $orderData = ['name' => 'Melody Ho5liday I_nn'];
        $nonEngilsh = new NonEngilsh();

        $this->assertThrows(
            fn () => $nonEngilsh->check($orderData),
            fn (OrderCheckerException $e) => $e->getMessage() === $nonEngilsh->errorMessage
        );
    }
}
