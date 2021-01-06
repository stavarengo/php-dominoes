<?php

declare(strict_types=1);


namespace Dominoes\Tile;


class TilesCollection implements TilesCollectionInterface
{
    /**
     * @var TileInterface[]
     */
    private array $tiles;

    public function __construct(TileInterface ...$tiles)
    {
        $this->tiles = [];
        foreach ($tiles as $tile) {
            $this->addTile($tile);
        }
    }

    public function countTiles(): int
    {
        return count($this->tiles);
    }

    public function removeTile(TileInterface $tileToRemove): TilesCollectionInterface
    {
        foreach ($this->tiles as $key => $tile) {
            if ($tile->equalsTo($tileToRemove)) {
                $this->unsetByKey($key);
                break;
            }
        }

        return $this;
    }

    public function drawRandomTile(): TileInterface
    {
        if ($this->countTiles() === 0) {
            throw Exception\CantDrawFromAnEmptyCollection::create();
        }

        $key = mt_rand(0, $this->countTiles() - 1);

        $tile = $this->tiles[$key];
        $this->unsetByKey($key);

        return $tile;
    }

    public function drawRandomTiles(int $count): TilesCollectionInterface
    {
        $tiles = [];
        while (count($tiles) < $count) {
            $tiles[] = $this->drawRandomTile();
        }

        return new self(...$tiles);
    }

    public function getItems(): array
    {
        return $this->tiles;
    }

    public function getTilesWithPips(int ...$pips): array
    {
        $tiles = [];
        foreach ($this->tiles as $tile) {
            if (in_array($tile->getLeftPip(), $pips) || in_array($tile->getRightPip(), $pips)) {
                $tiles[] = $tile;
            }
        }

        return $tiles;
    }

    public function addTile(TileInterface $newTile): TilesCollectionInterface
    {
        foreach ($this->tiles as $tile) {
            if ($tile->equalsTo($newTile)) {
                return $this;
            }
        }

        $this->tiles[] = $newTile;

        return $this;
    }

    private function unsetByKey(int $key): void
    {
        unset($this->tiles[$key]);
        $this->tiles = array_values($this->tiles);
    }

    public function contains(TileInterface $tile): bool
    {
        foreach ($this->tiles as $item) {
            if ($item->equalsTo($tile)) {
                return true;
            }
        }

        return false;
    }

}