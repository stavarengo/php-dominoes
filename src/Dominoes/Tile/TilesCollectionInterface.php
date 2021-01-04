<?php

declare(strict_types=1);


namespace Dominoes\Tile;


interface TilesCollectionInterface
{
    public function countTiles(): int;

    /**
     * Returns a random tile and remove it for its internal set of tiles.
     *
     * @return TileInterface
     *
     * @throws Exception\CantDrawFromAnEmptyCollection
     *  If the deck is out of tiles.
     */
    public function drawRandomTile(): TileInterface;

    /**
     * @param int $count
     *  How many tiles should be draw.
     *
     * @return TilesCollectionInterface
     *
     * @throws Exception\CantDrawFromAnEmptyCollection
     *  If $count is bigger than {@link #countTiles()}.
     */
    public function drawRandomTiles(int $count): TilesCollectionInterface;

    /**
     * @return TileInterface[]
     */
    public function getItems(): array;
}