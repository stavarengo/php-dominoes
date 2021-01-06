<?php

declare(strict_types=1);


namespace Dominoes\Bot;


use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\Tile\RotatedTile;
use Dominoes\Tile\TileInterface;

class SimpleBot implements BotInterface
{
    public function play(GameMediatorInterface $gameMediator): void
    {
        $connectionSpots = $gameMediator->getConnectionSpots();
        $allPlayerTiles = $gameMediator->getCurrentPlayerTiles();

        $allPlayerTilesPossibilities = [
            ...$allPlayerTiles,
            ...array_map(fn(TileInterface $tile): TileInterface => new RotatedTile($tile), $allPlayerTiles),
        ];

        foreach ($connectionSpots as $connectionSpot) {
            if ($candidateTiles = $connectionSpot->canConnectedTo(...$allPlayerTilesPossibilities)) {
                $gameMediator->connectTile(reset($candidateTiles), $connectionSpot);

                return;
            }
        }

        $gameMediator->drawOrPass();
    }
}