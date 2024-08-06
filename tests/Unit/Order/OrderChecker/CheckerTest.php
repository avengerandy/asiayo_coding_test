<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Order\OrderChecker\Checker;
use App\Order\OrderChecker\NonEngilsh;
use App\Exceptions\OrderCheckerException;

class CheckerTest extends TestCase
{
    public function test_errorMessage_is_empty(): void
    {
        $fakeChecker = new class() extends Checker {
            protected function handle(array $orderData): bool {
                return true;
            }
        };
        $expectErrorMessage = '';
        $actualErrorMessage = $fakeChecker->errorMessage;

        $this->assertEquals($expectErrorMessage, $actualErrorMessage);
    }

    public function test_check_call_next_when_handle_return_true_and_next_is_not_null(): void
    {
        $fakeChecker = new class() extends Checker {
            protected function handle(array $orderData): bool {
                return true;
            }
        };

        $orderData = ['name' => 'Melody Holiday Inn'];
        $mockNextChecker = $this->mock(NonEngilsh::class);
        $mockNextChecker->expects()->check($orderData)->once();
        $fakeChecker->setNext($mockNextChecker);
        $fakeChecker->check($orderData);
    }

    public function test_check_not_call_next_when_handle_return_true_and_next_is_null(): void
    {
        $fakeChecker = new class() extends Checker {
            protected function handle(array $orderData): bool {
                return true;
            }
        };

        $orderData = ['name' => 'Melody Holiday Inn'];
        $this->assertNull($fakeChecker->check($orderData));
    }

    public function test_check_throw_exception_when_handle_return_false(): void
    {
        $fakeChecker = new class() extends Checker {
            protected function handle(array $orderData): bool {
                return false;
            }
        };

        $orderData = ['name' => 'Melody Holiday Inn'];
        $this->assertThrows(
            fn () => $fakeChecker->check($orderData),
            fn (OrderCheckerException $e) => $e->getMessage() === $fakeChecker->errorMessage
        );
    }
}
