<?php

declare(strict_types=1);


namespace Dominoes\Player;


use Dominoes\Tile\TileInterface;

class Player implements PlayerInterface
{

    /**
     * @var TileInterface[]
     */
    private array $tiles;

    public function __construct(private string $name, TileInterface ...$tiles)
    {
        $this->tiles = $tiles;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTiles(): array
    {
        return $this->tiles;
    }
}