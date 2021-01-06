<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\DefaultMediator\State;


use Dominoes\GameMediator\Exception\GameIsAlreadyOver;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\Tile\TileInterface;

class GameOver extends AbstractState
{

    public function getConnectionSpots(): array
    {
        throw GameIsAlreadyOver::create();
    }

    public function getCurrentPlayerTiles(): array
    {
        throw GameIsAlreadyOver::create();
    }

    public function connectTile(TileInterface $tile, ConnectionSpotInterface $connectionSpot,): void
    {
        throw GameIsAlreadyOver::create();
    }

    public function start(PlayerInterface ...$players): void
    {
        throw GameIsAlreadyOver::create();
    }

    public function drawOrPass(): void
    {
        throw GameIsAlreadyOver::create();
    }

    public function getStatus(): int
    {
        return GameMediatorInterface::STATUS_GAME_IS_OVER;
    }

    public function getWinner(): ?PlayerInterface
    {
        foreach ($this->gameMediator->getRoundManager()->getPlayers() as $player) {
            if ($player->getTiles()->countTiles() === 0) {
                return $player;
            }
        }

        return null;
    }
}