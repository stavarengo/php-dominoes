<?php

declare(strict_types=1);


namespace Dominoes\Deck;


use Dominoes\Deck\Exception\CantDrawFromAnEmptyDeck;
use Dominoes\Tile\TileInterface;

interface DeckInterface
{
    public function countTiles(): int;

    /**
     * Returns a random tile and remove it for its internal set of tiles.
     *
     * @return TileInterface
     *
     * @throws CantDrawFromAnEmptyDeck
     *  If the deck is out of tiles.
     */
    public function drawRandomTile(): TileInterface;

    /**
     * @param int $count
     *  How many tiles should be draw.
     * @return TileInterface[]
     * @throws CantDrawFromAnEmptyDeck
     *  If $count is bigger than {@link #countTiles()}.
     */
    public function drawRandomTiles(int $count): array;
}