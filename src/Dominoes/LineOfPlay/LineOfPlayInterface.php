<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay;


use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\Tile\TileInterface;

interface LineOfPlayInterface
{
    /**
     * Returns tiles in the order they are connected.
     *
     * @return TileInterface[]
     */
    public function getTiles(): array;

    /**
     * Returns a new line of play with the $tile connected to the leftmost side.
     *
     * @param TileInterface $tile
     * @return LineOfPlayInterface
     */
    public function withPrependedTile(TileInterface $tile): self;

    /**
     * Returns a new line of play with the $tile connected to the rightmost side.
     *
     * @param TileInterface $tile
     * @return LineOfPlayInterface
     */
    public function withAppendedTile(TileInterface $tile): self;

    /**
     * @return ConnectionSpotInterface[]
     */
    public function getConnectionSpots(): array;
}