<?php

declare(strict_types=1);


namespace Dominoes\Player;


use Dominoes\Tile\TilesCollectionInterface;

class Player implements PlayerInterface
{
    public function __construct(private string $name, private TilesCollectionInterface $tiles)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTiles(): TilesCollectionInterface
    {
        return $this->tiles;
    }
}