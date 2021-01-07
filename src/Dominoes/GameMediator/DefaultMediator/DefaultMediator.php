<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\DefaultMediator;


use Dominoes\GameMediator\DefaultMediator\State\AbstractState;
use Dominoes\GameMediator\DefaultMediator\State\NotStarted;
use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\RoundManager\RoundManagerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollectionInterface;

class DefaultMediator implements GameMediatorInterface
{
    private AbstractState $state;

    private GameListenerInterface $gameListener;

    private TilesCollectionInterface $boneyard;

    public function __construct(
        private RoundManagerInterface $roundManager,
        private LineOfPlayInterface $lineOfPlay,
    ) {
        $this->state = new NotStarted($this);
    }

    public function start(
        GameListenerInterface $gameListener,
        TilesCollectionInterface $boneyard,
        PlayerInterface ...$players
    ): void {
        $this->gameListener = $gameListener;
        $this->boneyard = $boneyard;
        $this->state->start($gameListener, $boneyard, ...$players);
    }

    public function getConnectionSpots(): array
    {
        return $this->state->getConnectionSpots();
    }

    public function getCurrentPlayerTiles(): array
    {
        return $this->state->getCurrentPlayerTiles();
    }

    public function connectTile(TileInterface $tile, ConnectionSpotInterface $connectionSpot): void
    {
        $this->state->connectTile($tile, $connectionSpot);
    }

    public function drawOrPass(): void
    {
        $this->state->drawOrPass();
    }

    public function getStatus(): int
    {
        return $this->state->getStatus();
    }

    public function getWinner(): ?PlayerInterface
    {
        return $this->state->getWinner();
    }

    public function getCurrentState(): AbstractState
    {
        return $this->state;
    }

    public function changeState(AbstractState $state)
    {
        $this->state = $state;
    }

    public function getLineOfPlay(): LineOfPlayInterface
    {
        return $this->lineOfPlay;
    }

    public function getGameListener(): GameListenerInterface
    {
        return $this->gameListener;
    }

    public function getRoundManager(): RoundManagerInterface
    {
        return $this->roundManager;
    }

    public function setLineOfPlay(LineOfPlayInterface $lineOfPlay): void
    {
        $this->lineOfPlay = $lineOfPlay;
    }

    public function getBoneyard(): TilesCollectionInterface
    {
        return $this->boneyard;
    }
}