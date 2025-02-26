<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\DefaultMediator\State;


use Dominoes\GameMediator\Exception\GameDidNotStartYet;
use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollectionInterface;

class NotStarted extends AbstractState
{
    public function getConnectionSpots(): array
    {
        throw GameDidNotStartYet::create();
    }

    public function getCurrentPlayerTiles(): array
    {
        throw GameDidNotStartYet::create();
    }

    public function connectTile(TileInterface $tile, ConnectionSpotInterface $connectionSpot,): void
    {
        throw GameDidNotStartYet::create();
    }

    public function start(
        GameListenerInterface $gameListener,
        TilesCollectionInterface $boneyard,
        PlayerInterface ...$players
    ): void {
        $firstTile = $boneyard->drawRandomTile();
        $lineOfPlay = $this->gameMediator->getLineOfPlay()->withAppendedTile($firstTile);
        $this->gameMediator->setLineOfPlay($lineOfPlay);

        $this->gameMediator->getRoundManager()->setPlayers(...$players);

        $this->gameMediator->changeState(new InProgress($this->gameMediator));

        $gameListener->gameStarted($lineOfPlay, $boneyard, ...$players);
    }

    public function drawOrPass(): void
    {
        throw GameDidNotStartYet::create();
    }

    public function getStatus(): int
    {
        return GameMediatorInterface::STATUS_NOT_STARTED;
    }

    public function getWinner(): ?PlayerInterface
    {
        return null;
    }
}