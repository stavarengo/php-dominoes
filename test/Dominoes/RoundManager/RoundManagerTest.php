<?php

declare(strict_types=1);

namespace Test\Dominoes\RoundManager;

use Dominoes\Player\PlayerInterface;
use Dominoes\RoundManager\Exception\NoMorePlayersToPlay;
use Dominoes\RoundManager\RoundManager;
use PHPUnit\Framework\TestCase;

class RoundManagerTest extends TestCase
{
    public function testThrowExceptionWhenThereIsNoNextPlayer()
    {
        $this->expectExceptionObject(NoMorePlayersToPlay::create());
        (new RoundManager(...[]))->nextPlayer();
    }

    public function testNextPlayerWhenThereIsOnlyOnePlayer()
    {
        $players = [$this->createStub(PlayerInterface::class)];
        $round = new RoundManager(...$players);
        $this->assertSame($players[0], $round->nextPlayer());
        $this->assertSame($players[0], $round->nextPlayer());
        $this->assertSame($players[0], $round->nextPlayer());
    }

    public function testNextPlayerWithMultiplePlayers()
    {
        $players = [
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
        ];
        $round = new RoundManager(...$players);
        $this->assertSame($players[0], $round->nextPlayer());
        $this->assertSame($players[1], $round->nextPlayer());
        $this->assertSame($players[2], $round->nextPlayer());
        $this->assertSame($players[0], $round->nextPlayer());
        $this->assertSame($players[1], $round->nextPlayer());
        $this->assertSame($players[2], $round->nextPlayer());
    }
}
