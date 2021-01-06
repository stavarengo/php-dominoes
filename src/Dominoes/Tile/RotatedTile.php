<?php

declare(strict_types=1);


namespace Dominoes\Tile;


class RotatedTile implements TileInterface
{
    public function __construct(
        private TileInterface $tile,
    ) {
    }

    public function getLeftPip(): int
    {
        return $this->tile->getRightPip();
    }

    public function getRightPip(): int
    {
        return $this->tile->getLeftPip();
    }

    public function equalsTo(?TileInterface $other): bool
    {
        return $this->tile->equalsTo($other);
    }

    public function __toString(): string
    {
        return sprintf('<%s:%s>', $this->getLeftPip(), $this->getRightPip());
    }
}