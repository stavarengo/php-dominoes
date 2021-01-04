<?php

declare(strict_types=1);

namespace Test\Dominoes\Round;

use Dominoes\Player\PlayerInterface;
use Dominoes\Round\Exception\NoMorePlayersToPlay;
use Dominoes\Round\Round;
use PHPUnit\Framework\TestCase;

class RoundTest extends TestCase
{
    public function testThrowExceptionWhenThereIsNoNextPlayer()
    {
        $this->expectExceptionObject(NoMorePlayersToPlay::create());
        (new Round(...[]))->nextPlayer();
    }

    public function testNextPlayerWhenThereIsOnlyOnePlayer()
    {
        $players = [$this->createStub(PlayerInterface::class)];
        $round = new Round(...$players);
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
        $round = new Round(...$players);
        $this->assertSame($players[0], $round->nextPlayer());
        $this->assertSame($players[1], $round->nextPlayer());
        $this->assertSame($players[2], $round->nextPlayer());
        $this->assertSame($players[0], $round->nextPlayer());
        $this->assertSame($players[1], $round->nextPlayer());
        $this->assertSame($players[2], $round->nextPlayer());
    }
}
