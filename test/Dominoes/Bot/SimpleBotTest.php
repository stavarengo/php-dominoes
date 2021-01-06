<?php

declare(strict_types=1);

namespace Test\Dominoes\Bot;

use Dominoes\Bot\SimpleBot;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class SimpleBotTest extends TestCase
{
    public function testPlayerHasTilesToPlay()
    {
        $tile = $this->createStub(TileInterface::class);

        $connectionSpot = $this->createStub(ConnectionSpotInterface::class);
        $connectionSpot->method('canConnectedTo')->willReturn([$tile]);

        $mediator = $this->createMock(GameMediatorInterface::class);
        $mediator->method('getConnectionSpots')->willReturn([$connectionSpot]);

        $mediator->expects($this->never())
            ->method('drawOrPass');
        $mediator->expects($this->once())
            ->method('connectTile')
            ->with($this->identicalTo($tile), $this->identicalTo($connectionSpot));

        (new SimpleBot())->play($mediator);
    }

    public function testNoneOfThePlayerTitlesCanBePlayed()
    {
        $connectionSpot = $this->createStub(ConnectionSpotInterface::class);
        $connectionSpot->method('canConnectedTo')->willReturn([]);

        $mediator = $this->createMock(GameMediatorInterface::class);
        $mediator->method('getConnectionSpots')->willReturn([$connectionSpot]);

        $mediator->expects($this->never())
            ->method('connectTile');
        $mediator->expects($this->once())
            ->method('drawOrPass');

        (new SimpleBot())->play($mediator);
    }

    public function testThereAreNoSpotsForConnection()
    {
        $mediator = $this->createMock(GameMediatorInterface::class);
        $mediator->method('getConnectionSpots')->willReturn([]);

        $mediator->expects($this->never())
            ->method('connectTile');
        $mediator->expects($this->once())
            ->method('drawOrPass');

        (new SimpleBot())->play($mediator);
    }

}
