<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\DefaultMediator\State;


use Dominoes\GameMediator\Exception\GameIsAlreadyInProgress;
use Dominoes\GameMediator\Exception\ThisPlayerDoesntHaveThisTile;
use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollectionInterface;

class InProgress extends AbstractState
{
    private int $passTurnCount = -1;

    public function getConnectionSpots(): array
    {
        return $this->gameMediator->getLineOfPlay()->getConnectionSpots();
    }

    public function getCurrentPlayerTiles(): array
    {
        return $this->gameMediator->getRoundManager()->getCurrentPlayer()->getTiles()->getItems();
    }

    public function connectTile(TileInterface $tile, ConnectionSpotInterface $connectionSpot,): void
    {
        $player = $this->gameMediator->getRoundManager()->getCurrentPlayer();

        if (!$player->getTiles()->contains($tile)) {
            throw ThisPlayerDoesntHaveThisTile::create($player, $tile);
        }

        $player->getTiles()->removeTile($tile);

        $newLineOfPlay = $connectionSpot->connect($tile);
        $this->gameMediator->setLineOfPlay($newLineOfPlay);

        $this->gameMediator->getGameListener()->playerConnectedTile($player, $tile, $connectionSpot, $newLineOfPlay);

        $this->gameMediator->getRoundManager()->nextPlayer();

        if ($player->getTiles()->countTiles() === 0) {
            $this->gameMediator->changeState(new GameOver($this->gameMediator));
        }

        $this->passTurnCount = 0;
    }

    public function start(
        GameListenerInterface $gameListener,
        TilesCollectionInterface $boneyard,
        PlayerInterface ...$players
    ): void {
        throw GameIsAlreadyInProgress::create();
    }

    public function drawOrPass(): void
    {
        if ($this->gameMediator->getBoneyard()->countTiles() > 0) {
            $newTile = $this->gameMediator->getBoneyard()->drawRandomTile();
            $player = $this->gameMediator->getRoundManager()->getCurrentPlayer();

            $player->getTiles()->addTile($newTile);

            $this->gameMediator->getGameListener()->playerDraw($player, $newTile);
        } else {
            $this->gameMediator->getGameListener()->playerPassedTurn(
                $this->gameMediator->getRoundManager()->getCurrentPlayer()
            );
            $this->gameMediator->getRoundManager()->nextPlayer();
            $this->passTurnCount++;

            if ($this->passTurnCount >= $this->gameMediator->getRoundManager()->countPlayers()) {
                $this->gameMediator->changeState(new GameOver($this->gameMediator));
            }
        }
    }

    public function getStatus(): int
    {
        return GameMediatorInterface::STATUS_IN_PROGRESS;
    }

    public function getWinner(): ?PlayerInterface
    {
        return null;
    }
}