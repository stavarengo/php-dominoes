<?php

declare(strict_types=1);

namespace Test\Dominoes\GameMediator\DefaultMediator\State;

use Dominoes\GameMediator\DefaultMediator\DefaultMediator;
use Dominoes\GameMediator\DefaultMediator\State\GameOver;
use Dominoes\GameMediator\DefaultMediator\State\InProgress;
use Dominoes\GameMediator\Exception\GameIsAlreadyInProgress;
use Dominoes\GameMediator\Exception\ThisPlayerDoesntHaveThisTile;
use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\RoundManager\RoundManagerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollection;
use Dominoes\Tile\TilesCollectionInterface;
use PHPUnit\Framework\TestCase;

class InProgressTest extends TestCase
{
    public function testGetConnectionSpots()
    {
        $expectedResult = [$this->createStub(ConnectionSpotInterface::class)];

        $lineOfPlay = $this->createMock(LineOfPlayInterface::class);
        $lineOfPlay->expects($this->once())
            ->method('getConnectionSpots')
            ->willReturn($expectedResult);

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->expects($this->once())
            ->method('getLineOfPlay')
            ->willReturn($lineOfPlay);

        $state = new InProgress($gameMediator);
        $actualResult = $state->getConnectionSpots();
        $this->assertSameSize($expectedResult, $actualResult);
        $this->assertSame($expectedResult[0], $actualResult[0]);
    }

    public function testGetCurrentPlayerTiles()
    {
        $expectedResult = [$this->createStub(TilesCollection::class), $this->createStub(TilesCollection::class)];

        $currentPlayerTiles = $this->createMock(TilesCollectionInterface::class);
        $currentPlayerTiles->expects($this->once())
            ->method('getItems')
            ->willReturn($expectedResult);

        $currentPlayer = $this->createMock(PlayerInterface::class);
        $currentPlayer->expects($this->once())
            ->method('getTiles')
            ->willReturn($currentPlayerTiles);

        $roundManager = $this->createMock(RoundManagerInterface::class);
        $roundManager->expects($this->once())
            ->method('getCurrentPlayer')
            ->willReturn($currentPlayer);

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->expects($this->once())
            ->method('getRoundManager')
            ->willReturn($roundManager);

        $state = new InProgress($gameMediator);
        $actualResult = $state->getCurrentPlayerTiles();
        $this->assertSameSize($expectedResult, $actualResult);
        $this->assertSame($expectedResult[0], $actualResult[0]);
        $this->assertSame($expectedResult[1], $actualResult[1]);
    }

    public function testTryToConnectTileThatTheCurrentUserDontHave()
    {
        $newTile = $this->createStub(TileInterface::class);

        $currentPlayerTiles = $this->createMock(TilesCollectionInterface::class);
        $currentPlayerTiles->expects($this->once())
            ->method('contains')
            ->with($this->identicalTo($newTile))
            ->willReturn(false);

        $currentPlayer = $this->createStub(PlayerInterface::class);
        $currentPlayer->method('getTiles')
            ->willReturn($currentPlayerTiles);

        $roundManager = $this->createStub(RoundManagerInterface::class);
        $roundManager->method('getCurrentPlayer')
            ->willReturn($currentPlayer);

        $this->expectExceptionObject(ThisPlayerDoesntHaveThisTile::create($currentPlayer, $newTile));

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->expects($this->once())
            ->method('getRoundManager')
            ->willReturn($roundManager);

        $state = new InProgress($gameMediator);
        $state->connectTile($newTile, $this->createStub(ConnectionSpotInterface::class));
    }

    public function testConnectTile()
    {
        $currentPlayerTiles = $this->createStub(TilesCollectionInterface::class);
        $currentPlayerTiles->method('contains')->willReturn(true);

        $currentPlayer = $this->createStub(PlayerInterface::class);
        $currentPlayer->method('getTiles')->willReturn($currentPlayerTiles);

        $roundManager = $this->createMock(RoundManagerInterface::class);
        $roundManager->expects($this->once())
            ->method('getCurrentPlayer')
            ->willReturn($currentPlayer);
        $roundManager->expects($this->once())
            ->method('nextPlayer');

        $newTile = $this->createStub(TileInterface::class);

        $connectionSpot = $this->createMock(ConnectionSpotInterface::class);
        $connectionSpot->expects($this->once())
            ->method('connect')
            ->with($this->identicalTo($newTile))
            ->willReturn($newLineOfPlay = $this->createStub(LineOfPlayInterface::class));

        $gameListener = $this->createMock(GameListenerInterface::class);
        $gameListener->expects($this->once())
            ->method('playerConnectedTile')
            ->with(
                $this->identicalTo($currentPlayer),
                $this->identicalTo($newTile),
                $this->identicalTo($connectionSpot),
                $this->identicalTo($newLineOfPlay)
            );

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $gameMediator->method('getGameListener')->willReturn($gameListener);
        $gameMediator->expects($this->once())
            ->method('setLineOfPlay')
            ->with($this->identicalTo($newLineOfPlay));

        $state = new InProgress($gameMediator);
        $state->connectTile($newTile, $connectionSpot);
    }

    public function testConnectTileMustChangeStateWhenPlayerDoesNotHasMoreTiles()
    {
        $currentPlayerTiles = $this->createStub(TilesCollectionInterface::class);
        $currentPlayerTiles->method('countTiles')->willReturnOnConsecutiveCalls(1, 0);
        $currentPlayerTiles->method('contains')->willReturn(true);

        $currentPlayer = $this->createStub(PlayerInterface::class);
        $currentPlayer->method('getTiles')->willReturn($currentPlayerTiles);

        $roundManager = $this->createStub(RoundManagerInterface::class);
        $roundManager->method('getCurrentPlayer')->willReturn($currentPlayer);

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $gameMediator->expects($this->exactly(0))->method('changeState');
        $state = new InProgress($gameMediator);

        $state->connectTile($this->createStub(TileInterface::class), $this->createStub(ConnectionSpotInterface::class));

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $gameMediator->expects($this->once())->method('changeState')->with($this->isInstanceOf(GameOver::class));
        $state = new InProgress($gameMediator);

        $state->connectTile($this->createStub(TileInterface::class), $this->createStub(ConnectionSpotInterface::class));
    }

    public function testStart()
    {
        $this->expectExceptionObject(GameIsAlreadyInProgress::create());
        $state = new InProgress($this->createStub(DefaultMediator::class));
        $state->start();
    }

    public function testDrawOrPassShouldDrawIfDeckIsNotEmpty()
    {
        $tileFromDeck = $this->createStub(TileInterface::class);

        $deck = $this->createMock(TilesCollectionInterface::class);
        $deck->expects($this->once())
            ->method('countTiles')
            ->willReturn(1);
        $deck->expects($this->once())
            ->method('drawRandomTile')
            ->willReturn($tileFromDeck);

        $currentPlayerTiles = $this->createMock(TilesCollectionInterface::class);
        $currentPlayerTiles->expects($this->once())
            ->method('addTile')
            ->with($this->identicalTo($tileFromDeck));

        $currentPlayer = $this->createStub(PlayerInterface::class);
        $currentPlayer->method('getTiles')->willReturn($currentPlayerTiles);

        $roundManager = $this->createStub(RoundManagerInterface::class);
        $roundManager->method('getCurrentPlayer')->willReturn($currentPlayer);

        $gameListener = $this->createMock(GameListenerInterface::class);
        $gameListener->expects($this->once())
            ->method('playerDraw')
            ->with($this->identicalTo($currentPlayer), $this->identicalTo($tileFromDeck),);

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $gameMediator->method('getGameListener')->willReturn($gameListener);
        $gameMediator->method('getDeck')->willReturn($deck);
        $gameMediator->expects($this->exactly(0))->method('changeState');
        $state = new InProgress($gameMediator);

        $state->drawOrPass();
    }

    public function testDrawOrPassShouldPassIfDeckIsEmpty()
    {
        $deck = $this->createMock(TilesCollectionInterface::class);
        $deck->expects($this->once())
            ->method('countTiles')
            ->willReturn(0);

        $currentPlayer = $this->createStub(PlayerInterface::class);

        $roundManager = $this->createMock(RoundManagerInterface::class);
        $roundManager->method('getCurrentPlayer')->willReturn($currentPlayer);
        $roundManager->expects($this->once())
            ->method('nextPlayer');

        $gameListener = $this->createMock(GameListenerInterface::class);
        $gameListener->expects($this->once())
            ->method('playerPassedTurn')
            ->with($this->identicalTo($currentPlayer));

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $gameMediator->method('getGameListener')->willReturn($gameListener);
        $gameMediator->method('getDeck')->willReturn($deck);

        $state = new InProgress($gameMediator);
        $state->drawOrPass();
    }

    public function testDrawOrPassShouldChangeStatusIfEverybodyPassTurnInSequence()
    {
        $deck = $this->createStub(TilesCollectionInterface::class);
        $deck->method('countTiles')->willReturn(0);

        $roundManager = $this->createStub(RoundManagerInterface::class);
        $roundManager->method('countPlayers')->willReturn(2);

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $gameMediator->expects($this->exactly(0))->method('changeState');

        $state = new InProgress($gameMediator);
        $state->drawOrPass();

        $gameMediator = $this->createMock(DefaultMediator::class);
        $gameMediator->method('getRoundManager')->willReturn($roundManager);
        $gameMediator->expects($this->once())
            ->method('changeState')
            ->with($this->isInstanceOf(GameOver::class));

        $state = new InProgress($gameMediator);
        $state->drawOrPass();
        $state->drawOrPass();
        $state->drawOrPass();
    }

    public function testGetStatus()
    {
        $state = new InProgress($this->createStub(DefaultMediator::class));
        $this->assertEquals(GameMediatorInterface::STATUS_IN_PROGRESS, $state->getStatus());
    }

    public function testGetWinner()
    {
        $state = new InProgress($this->createStub(DefaultMediator::class));
        $this->assertNull($state->getWinner());
    }
}
