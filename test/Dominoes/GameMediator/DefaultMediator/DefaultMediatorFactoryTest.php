<?php

declare(strict_types=1);

namespace Test\Dominoes\GameMediator\DefaultMediator;

use Dominoes\GameMediator\DefaultMediator\DefaultMediator;
use Dominoes\GameMediator\DefaultMediator\DefaultMediatorFactory;
use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\RoundManager\RoundManagerInterface;
use PHPUnit\Framework\TestCase;

class DefaultMediatorFactoryTest extends TestCase
{
    public function testFactory()
    {
        $factory = new DefaultMediatorFactory();
        $this->assertIsCallable($factory);

        $result = $factory->__invoke(
            $roundManager = $this->createStub(RoundManagerInterface::class),
            $lineOfPlay = $this->createStub(LineOfPlayInterface::class)
        );

        $this->assertInstanceOf(DefaultMediator::class, $result);
        $this->assertSame($roundManager, $result->getRoundManager());
        $this->assertSame($lineOfPlay, $result->getLineOfPlay());
    }

}
