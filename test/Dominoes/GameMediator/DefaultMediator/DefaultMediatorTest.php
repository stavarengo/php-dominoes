<?php

declare(strict_types=1);

namespace Test\Dominoes\GameMediator\DefaultMediator;

use Dominoes\GameMediator\DefaultMediator\DefaultMediator;
use Dominoes\GameMediator\DefaultMediator\State\AbstractState;
use Dominoes\GameMediator\DefaultMediator\State\NotStarted;
use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\RoundManager\RoundManagerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollectionInterface;
use PHPUnit\Framework\TestCase;

class DefaultMediatorTest extends TestCase
{
    public function testInitialStatus()
    {
        $mediator = new DefaultMediator(
            $this->createMock(RoundManagerInterface::class),
            $this->createStub(LineOfPlayInterface::class),
        );

        $this->assertInstanceOf(NotStarted::class, $mediator->getCurrentState());
    }

    public function testDefaultMediatorShouldDelegateCallsToTheState()
    {
        $mediator = new DefaultMediator(
            $this->createMock(RoundManagerInterface::class),
            $this->createStub(LineOfPlayInterface::class),
        );

        $state = $this->createMock(AbstractState::class);
        $state->expects($this->once())->method('getConnectionSpots');
        $mediator->changeState($state);
        $mediator->getConnectionSpots();

        $state = $this->createMock(AbstractState::class);
        $state->expects($this->once())->method('getCurrentPlayerTiles');
        $mediator->changeState($state);
        $mediator->getCurrentPlayerTiles();

        $state = $this->createMock(AbstractState::class);
        $state->expects($this->once())->method('connectTile')
            ->with(
                $this->identicalTo($tile = $this->createStub(TileInterface::class)),
                $this->identicalTo($spot = $this->createStub(ConnectionSpotInterface::class))
            );
        $mediator->changeState($state);
        $mediator->connectTile($tile, $spot);

        $state = $this->createMock(AbstractState::class);
        $state->expects($this->once())->method('start')
            ->with(
                $this->identicalTo($gameListener = $this->createStub(GameListenerInterface::class)),
                $this->identicalTo($boneyard = $this->createStub(TilesCollectionInterface::class)),
                $this->identicalTo($player = $this->createStub(PlayerInterface::class))
            );
        $mediator->changeState($state);
        $mediator->start($gameListener, $boneyard, $player);

        $state = $this->createMock(AbstractState::class);
        $state->expects($this->once())->method('drawOrPass');
        $mediator->changeState($state);
        $mediator->drawOrPass();

        $state = $this->createMock(AbstractState::class);
        $state->expects($this->once())->method('getStatus');
        $mediator->changeState($state);
        $mediator->getStatus();

        $state = $this->createMock(AbstractState::class);
        $state->expects($this->once())->method('getWinner');
        $mediator->changeState($state);
        $mediator->getWinner();
    }

    public function testGetters()
    {
        $mediator = new DefaultMediator(
            $roundManager = $this->createMock(RoundManagerInterface::class),
            $lineOfPlay = $this->createStub(LineOfPlayInterface::class)
        );

        $mediator->changeState($state = $this->createStub(AbstractState::class));
        $this->assertSame($state, $mediator->getCurrentState());

        $this->assertSame($roundManager, $mediator->getRoundManager());
        $this->assertSame($lineOfPlay, $mediator->getLineOfPlay());

        $mediator->start(
            $gameListener = $this->createStub(GameListenerInterface::class),
            $boneyard = $this->createStub(TilesCollectionInterface::class),
            ...[]
        );

        $this->assertSame($boneyard, $mediator->getBoneyard());
        $this->assertSame($gameListener, $mediator->getGameListener());
    }

    public function testSetLineOfPlay()
    {
        $mediator = new DefaultMediator(
            $this->createMock(RoundManagerInterface::class),
            $lineOfPlay1 = $this->createStub(LineOfPlayInterface::class),
        );

        $this->assertSame($lineOfPlay1, $mediator->getLineOfPlay());

        $mediator->setLineOfPlay($lineOfPlay2 = $this->createStub(LineOfPlayInterface::class));
        $this->assertSame($lineOfPlay2, $mediator->getLineOfPlay());
    }

    public function testChangeState()
    {
        $mediator = new DefaultMediator(
            $this->createMock(RoundManagerInterface::class),
            $this->createStub(LineOfPlayInterface::class),
        );

        $mediator->changeState($state1 = $this->createStub(AbstractState::class));
        $this->assertSame($state1, $mediator->getCurrentState());

        $mediator->changeState($state2 = $this->createStub(AbstractState::class));
        $this->assertSame($state2, $mediator->getCurrentState());
    }

}
