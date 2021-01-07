<?php

declare(strict_types=1);


namespace Dominoes\GameMediator;


use Dominoes\GameMediator\Exception\GameException;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollectionInterface;

interface GameMediatorInterface
{
    public const STATUS_NOT_STARTED = 1;
    public const STATUS_IN_PROGRESS = 2;
    public const STATUS_GAME_IS_OVER = 3;

    /**
     * @return ConnectionSpotInterface[]
     *
     * @throws GameException
     */
    public function getConnectionSpots(): array;

    /**
     * @return TileInterface[]
     * @throws GameException
     */
    public function getCurrentPlayerTiles(): array;

    /**
     * @param TileInterface $tile
     * @param ConnectionSpotInterface $connectionSpot
     * @throws GameException
     */
    public function connectTile(TileInterface $tile, ConnectionSpotInterface $connectionSpot,): void;

    /**
     * @param GameListenerInterface $gameListener
     * @param TilesCollectionInterface $boneyard
     * @param PlayerInterface ...$players
     * @throws GameException
     */
    public function start(
        GameListenerInterface $gameListener,
        TilesCollectionInterface $boneyard,
        PlayerInterface ...$players
    ): void;

    /**
     * @throws GameException
     */
    public function drawOrPass(): void;

    /**
     * Return the value of one of the constants `STATUS_*`
     *
     * @return int
     * @throws GameException
     */
    public function getStatus(): int;

    /**
     * @return PlayerInterface|null
     * @throws GameException
     */
    public function getWinner(): ?PlayerInterface;
}