<?php

declare(strict_types=1);


namespace Dominoes\Tile;

interface TileInterface extends \Stringable
{
    public function getLeftPip(): int;

    public function getRightPip(): int;

    public function equalsTo(?TileInterface $other): bool;
}