<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay\ConnectionSpot;


use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Tile\TileInterface;

class ConnectionSpot implements ConnectionSpotInterface
{
    public function __construct(
        private LineOfPlayInterface $lineOfPlay,
        private bool $connectToLeft
    ) {
    }

    public function getLineOfPlay(): LineOfPlayInterface
    {
        return $this->lineOfPlay;
    }

    public function getConnectToLeft(): bool
    {
        return $this->connectToLeft;
    }

    public function canConnectedTo(TileInterface ...$tiles): array
    {
        $currentTiles = $this->lineOfPlay->getTiles();
        if (!$currentTiles) {
            return $tiles;
        }

        $lineOfPlayTile = end($currentTiles);
        $lineOfPlayOpenPip = end($currentTiles)->getRightPip();
        if ($this->connectToLeft) {
            $lineOfPlayTile = $currentTiles[0];
            $lineOfPlayOpenPip = $lineOfPlayTile->getLeftPip();
        }

        return array_filter(
            $tiles,
            function (TileInterface $tile) use ($lineOfPlayTile, $lineOfPlayOpenPip): bool {
                $tilePip = $this->connectToLeft ? $tile->getRightPip() : $tile->getLeftPip();

                return !$lineOfPlayTile->equalsTo($tile) && $lineOfPlayOpenPip === $tilePip;
            }
        );
    }

    public function connect(TileInterface $tile): LineOfPlayInterface
    {
        if (!$this->canConnectedTo($tile)) {
            throw Exception\CantConnectThisTile::create($tile, $this->lineOfPlay);
        }

        if ($this->connectToLeft) {
            return $this->lineOfPlay->withPrependedTile($tile);
        } else {
            return $this->lineOfPlay->withAppendedTile($tile);
        }
    }
}