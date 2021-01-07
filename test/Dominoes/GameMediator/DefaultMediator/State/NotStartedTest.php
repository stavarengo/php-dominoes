<?php

declare(strict_types=1);

namespace Test\Dominoes\GameMediator\DefaultMediator\State;

use Dominoes\GameMediator\DefaultMediator\DefaultMediator;
use Dominoes\GameMediator\DefaultMediator\State\InProgress;
use Dominoes\GameMediator\DefaultMediator\State\NotStarted;
use Dominoes\GameMediator\Exception\GameDidNotStartYet;
use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\RoundManager\RoundManagerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollectionInterface;
use PHPUnit\Framework\TestCase;

class NotStartedTest extends TestCase
{
    public function testGetConnectionSpots()
    {
        $this->expectExceptionObject(GameDidNotStartYet::create());
        $state = new NotStarted($this->createStub(DefaultMediator::class));
        $state->getConnectionSpots();
    }

    public function testGetCurrentPlayerTiles()
    {
        $this->expectExceptionObject(GameDidNotStartYet::create());
        $state = new NotStarted($this->createStub(DefaultMediator::class));
        $state->getCurrentPlayerTiles();
    }

    public function testConnectTile()
    {
        $this->expectExceptionObject(GameDidNotStartYet::create());
        $state = new NotStarted($this->createStub(DefaultMediator::class));
        $state->connectTile($this->createStub(TileInterface::class), $this->createStub(ConnectionSpotInterface::class));
    }

    public function testStart()
    {
        $player = $this->createStub(PlayerInterface::class);

        $roundManager = $this->createMock(RoundManagerInterface::class);
        $roundManager->expects($this->once())
            ->method('setPlayers')
            ->with($this->identicalTo($player));

        $firstTile = $this->createStub(TileInterface::class);

        $lineOfPlay = $this->createMock(LineOfPlayInterface::class);
        $lineOfPlay->expects($this->once())
            ->method('withAppendedTile')
            ->willReturn($lineOfPlay)
            ->with($firstTile);

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->expects($this->once())
            ->method('changeState')
            ->with($this->isInstanceOf(InProgress::class));
        $gameMediator->expects($this->once())
            ->method('getRoundManager')
            ->willReturn($roundManager);
        $gameMediator->expects($this->once())
            ->method('getLineOfPlay')
            ->willReturn($lineOfPlay);

        $boneyard = $this->createMock(TilesCollectionInterface::class);
        $boneyard->expects($this->once())
            ->method('drawRandomTile')
            ->willReturn($firstTile);

        $gameListener = $this->createMock(GameListenerInterface::class);
        $gameListener->expects($this->once())
            ->method('gameStarted')
            ->with($this->identicalTo($lineOfPlay), $this->identicalTo($boneyard), $this->identicalTo($player));

        $state = new NotStarted($gameMediator);
        $state->start($gameListener, $boneyard, $player);
    }

    public function testDrawOrPass()
    {
        $this->expectExceptionObject(GameDidNotStartYet::create());
        $state = new NotStarted($this->createStub(DefaultMediator::class));
        $state->drawOrPass();
    }

    public function testGetStatus()
    {
        $state = new NotStarted($this->createStub(DefaultMediator::class));
        $this->assertEquals(GameMediatorInterface::STATUS_NOT_STARTED, $state->getStatus());
    }

    public function testGetWinner()
    {
        $state = new NotStarted($this->createStub(DefaultMediator::class));
        $this->assertNull($state->getWinner());
    }
}
