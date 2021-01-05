<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay;


use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpot;
use Dominoes\Tile\TileInterface;

class LineOfPlay implements LineOfPlayInterface
{
    /**
     * @var TileInterface[]
     */
    private array $tiles = [];

    public function getTiles(): array
    {
        return $this->tiles;
    }

    public function withPrependedTile(TileInterface $tile): LineOfPlayInterface
    {
        $newLineOfPlay = new self();

        $newLineOfPlay->tiles = [$tile, ...$this->tiles];

        return $newLineOfPlay;
    }

    public function withAppendedTile(TileInterface $tile): LineOfPlayInterface
    {
        $newLineOfPlay = new self();

        $newLineOfPlay->tiles = [...$this->tiles, $tile];

        return $newLineOfPlay;
    }

    public function getConnectionSpots(): array
    {
        return [
            new ConnectionSpot($this, false),
            new ConnectionSpot($this, true),
        ];
    }
}