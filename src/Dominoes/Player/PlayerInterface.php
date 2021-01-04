<?php

declare(strict_types=1);


namespace Dominoes\Player;


use Dominoes\Tile\TileInterface;

interface PlayerInterface
{
    public function getName(): string;

    /**
     * @return TileInterface[]
     */
    public function getTiles(): array;
}