<?php

declare(strict_types=1);

namespace Test\Dominoes\GameMediator\DefaultMediator\State;

use Dominoes\GameMediator\DefaultMediator\DefaultMediator;
use Dominoes\GameMediator\DefaultMediator\State\GameOver;
use Dominoes\GameMediator\DefaultMediator\State\NotStarted;
use Dominoes\GameMediator\Exception\GameIsAlreadyOver;
use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\RoundManager\RoundManagerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollectionInterface;
use PHPUnit\Framework\TestCase;

class GameOverTest extends TestCase
{
    public function testGetConnectionSpots()
    {
        $this->expectExceptionObject(GameIsAlreadyOver::create());
        $state = new GameOver($this->createStub(DefaultMediator::class));
        $state->getConnectionSpots();
    }

    public function testGetCurrentPlayerTiles()
    {
        $this->expectExceptionObject(GameIsAlreadyOver::create());
        $state = new GameOver($this->createStub(DefaultMediator::class));
        $state->getCurrentPlayerTiles();
    }

    public function testConnectTile()
    {
        $this->expectExceptionObject(GameIsAlreadyOver::create());
        $state = new GameOver($this->createStub(DefaultMediator::class));
        $state->connectTile($this->createStub(TileInterface::class), $this->createStub(ConnectionSpotInterface::class));
    }

    public function testStart()
    {
        $this->expectExceptionObject(GameIsAlreadyOver::create());
        $state = new GameOver($this->createStub(DefaultMediator::class));
        $state->start(
            $this->createStub(GameListenerInterface::class),
            $this->createStub(TilesCollectionInterface::class),
            ...
            []
        );
    }

    public function testDrawOrPass()
    {
        $this->expectExceptionObject(GameIsAlreadyOver::create());
        $state = new GameOver($this->createStub(DefaultMediator::class));
        $state->drawOrPass();
    }

    public function testGetStatus()
    {
        $state = new NotStarted($this->createStub(DefaultMediator::class));
        $this->assertEquals(GameMediatorInterface::STATUS_NOT_STARTED, $state->getStatus());
    }

    public function testGetWinner()
    {
        $createPlayer = function (int $countPlayerTiles): PlayerInterface {
            $tiles = $this->createStub(TilesCollectionInterface::class);
            $tiles->method('countTiles')->willReturn($countPlayerTiles);

            $player = $this->createStub(PlayerInterface::class);
            $player->method('getTiles')->willReturn($tiles);

            return $player;
        };

        $looser1 = $createPlayer(1);
        $looser2 = $createPlayer(10);
        $winner1 = $createPlayer(0);
        $winner2 = $createPlayer(0);

        $roundManager = $this->createMock(RoundManagerInterface::class);
        $roundManager->method('getPlayers')->willReturn([$looser1, $looser2, $winner1]);
        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $this->assertSame($winner1, (new GameOver($gameMediator))->getWinner());

        $roundManager = $this->createMock(RoundManagerInterface::class);
        $roundManager->method('getPlayers')->willReturn([$looser1, $winner2, $winner1]);
        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $this->assertSame($winner2, (new GameOver($gameMediator))->getWinner());

        $roundManager = $this->createMock(RoundManagerInterface::class);
        $roundManager->method('getPlayers')->willReturn([$looser1, $looser2]);
        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $this->assertNull((new GameOver($gameMediator))->getWinner());

        $this->assertNull((new GameOver($this->createStub(DefaultMediator::class)))->getWinner());
    }
}
