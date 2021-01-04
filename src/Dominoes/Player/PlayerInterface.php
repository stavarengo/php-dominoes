<?php

declare(strict_types=1);


namespace Dominoes\Player;


use Dominoes\Tile\TilesCollectionInterface;

interface PlayerInterface
{
    public function getName(): string;

    /**
     * @return TilesCollectionInterface
     */
    public function getTiles(): TilesCollectionInterface;
}