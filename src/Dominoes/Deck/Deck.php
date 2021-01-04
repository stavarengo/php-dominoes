<?php

declare(strict_types=1);


namespace Dominoes\Deck;


use Dominoes\Tile\TileInterface;

class Deck implements DeckInterface
{
    /**
     * @var TileInterface[]
     */
    private array $tiles;

    public function __construct(TileInterface ...$tiles)
    {
        $this->tiles = $tiles;
    }

    public function countTiles(): int
    {
        return count($this->tiles);
    }

    public function drawRandomTile(): TileInterface
    {
        if ($this->countTiles() === 0) {
            throw Exception\CantDrawFromAnEmptyDeck::create();
        }

        $key = mt_rand(0, $this->countTiles() - 1);

        $tile = $this->tiles[$key];
        unset($this->tiles[$key]);
        $this->tiles = array_values($this->tiles);

        return $tile;
    }

    public function drawRandomTiles(int $count): array
    {
        $tiles = [];
        while (count($tiles) < $count) {
            $tiles[] = $this->drawRandomTile();
        }

        return $tiles;
    }
}