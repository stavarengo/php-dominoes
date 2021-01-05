<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay;


use Dominoes\LineOfPlay\Validator\ConnectionValidatorInterface;
use Dominoes\Tile\TileInterface;

class LineOfPlay implements LineOfPlayInterface
{
    /**
     * @var TileInterface[]
     */
    private array $tiles = [];

    public function __construct(private ConnectionValidatorInterface $validator)
    {
    }

    public function getTiles(): array
    {
        return $this->tiles;
    }

    public function connectLeft(TileInterface $tile): LineOfPlayInterface
    {
        if ($this->tiles) {
            $this->validator->validateConnection($tile, $this->tiles[0]);
        }

        $this->tiles = [$tile, ...$this->tiles];

        return $this;
    }

    public function connectRight(TileInterface $tile): LineOfPlayInterface
    {
        if ($this->tiles) {
            $this->validator->validateConnection(end($this->tiles), $tile);
        }

        $this->tiles[] = $tile;

        return $this;
    }
}