<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Order\OrderChecker\NonEngilsh;
use App\Order\OrderChecker\NotCapitalized;
use App\Order\OrderChecker\OverPrice;
use App\Order\OrderChecker\WrongCurrencyFormat;
use App\Order\OrderCheckerChainFactory;

class OrderCheckerChainFactoryTest extends TestCase
{
    public function test_create_OrderCheckerChain(): void
    {
        $mockWrongCurrencyFormat = $this->mock(WrongCurrencyFormat::class);
        $mockOverPrice = $this->mock(OverPrice::class);
        $mockOverPrice->expects()->setNext($mockWrongCurrencyFormat)->once();
        $mockNotCapitalized = $this->mock(NotCapitalized::class);
        $mockNotCapitalized->expects()->setNext($mockOverPrice)->once();
        $mockNonEngilsh = $this->mock(NonEngilsh::class);
        $mockNonEngilsh->expects()->setNext($mockNotCapitalized)->once();

        $orderCheckerChainFactory = new OrderCheckerChainFactory(
            $mockNonEngilsh,
            $mockNotCapitalized,
            $mockOverPrice,
            $mockWrongCurrencyFormat
        );
        $actualFirstChecker = $orderCheckerChainFactory->create();

        $this->assertEquals($mockNonEngilsh, $actualFirstChecker);
    }
}
