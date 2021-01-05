<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay\ConnectionSpot;


use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Tile\TileInterface;

interface ConnectionSpotInterface
{
    /**
     * Return all the $tiles which this spot can be connected to.
     *
     * @param TileInterface[] $tiles
     * @return TileInterface[]
     */
    public function canConnectedTo(TileInterface ...$tiles): array;

    /**
     * Connect the $tile to this spot and return a new line of play with the new tile.
     *
     * @param TileInterface $tile
     *
     * @return LineOfPlayInterface
     */
    public function connect(TileInterface $tile): LineOfPlayInterface;

    public function getLineOfPlay(): LineOfPlayInterface;

    public function getConnectToLeft(): bool;
}