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
        (new RoundManager())->nextPlayer();
    }

    public function testNextPlayerWhenThereIsOnlyOnePlayer()
    {
        $player = $this->createStub(PlayerInterface::class);
        $round = (new RoundManager())->setPlayers($player);
        $this->assertSame($player, $round->nextPlayer());
        $this->assertSame($player, $round->nextPlayer());
        $this->assertSame($player, $round->nextPlayer());
    }

    public function testNextPlayerWithMultiplePlayers()
    {
        $players = [
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
        ];
        $round = (new RoundManager())->setPlayers(...$players);
        $this->assertSame($players[0], $round->nextPlayer());
        $this->assertSame($players[1], $round->nextPlayer());
        $this->assertSame($players[2], $round->nextPlayer());
        $this->assertSame($players[0], $round->nextPlayer());
        $this->assertSame($players[1], $round->nextPlayer());
        $this->assertSame($players[2], $round->nextPlayer());
    }

    public function testGetCurrentPlayer()
    {
        $players = [
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
        ];

        $round = (new RoundManager())->setPlayers(...$players);
        $this->assertSame($players[0], $round->getCurrentPlayer());
        $this->assertSame($players[0], $round->getCurrentPlayer());

        $this->assertSame($players[1], $round->nextPlayer());
        $this->assertSame($players[1], $round->getCurrentPlayer());
        $this->assertSame($players[1], $round->getCurrentPlayer());

        $this->expectExceptionObject(NoMorePlayersToPlay::create());
        (new RoundManager())->getCurrentPlayer();
    }

    public function testCountPlayers()
    {
        $player1 = $this->createStub(PlayerInterface::class);
        $player2 = $this->createStub(PlayerInterface::class);

        $this->assertEquals(0, (new RoundManager())->countPlayers());
        $this->assertEquals(0, (new RoundManager())->setPlayers(...[])->countPlayers());
        $this->assertEquals(1, (new RoundManager())->setPlayers($player1)->countPlayers());
        $this->assertEquals(2, (new RoundManager())->setPlayers($player1, $player2)->countPlayers());
    }

    public function testGetPlayers()
    {
        $player1 = $this->createStub(PlayerInterface::class);
        $player2 = $this->createStub(PlayerInterface::class);

        $this->assertEmpty((new RoundManager())->getPlayers());

        $roundManager = (new RoundManager())->setPlayers($player1, $player2);
        $this->assertCount(2, $roundManager->getPlayers());
        $this->assertSame($player1, $roundManager->getPlayers()[0]);
        $this->assertSame($player2, $roundManager->getPlayers()[1]);
    }

    public function testSetPlayers()
    {
        $player1 = $this->createStub(PlayerInterface::class);
        $player2 = $this->createStub(PlayerInterface::class);

        $this->assertEquals(1, (new RoundManager())->setPlayers($player1)->countPlayers());
        $this->assertEquals(2, (new RoundManager())->setPlayers($player1, $player2)->countPlayers());
    }

}
