<?php

declare(strict_types=1);


namespace Dominoes\GameMediator;


use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollectionInterface;

interface GameListenerInterface
{
    public function gameStarted(
        LineOfPlayInterface $lineOfPlay,
        TilesCollectionInterface $boneyard,
        PlayerInterface ...$players
    ): void;

    public function playerConnectedTile(
        PlayerInterface $whoPlayed,
        TileInterface $tile,
        ConnectionSpotInterface $connectionSpot,
        LineOfPlayInterface $currentLineOfPlay,
    ): void;

    public function playerDraw(PlayerInterface $player, TileInterface $tile): void;

    public function playerPassedTurn(PlayerInterface $player);
}