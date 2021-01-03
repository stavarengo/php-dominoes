<?php

declare(strict_types=1);

namespace Dominoes\Tile;


class Tile implements TileInterface
{
    public function __construct(
        private int $left,
        private int $right,
    ) {
    }

    public function getId(): string
    {
        return $this->getLeftPip() . $this->getRightPip();
    }

    public function getLeftPip(): int
    {
        return $this->left;
    }

    public function getRightPip(): int
    {
        return $this->right;
    }

    public function __toString(): string
    {
        return sprintf('<%s:%s>', $this->getLeftPip(), $this->getRightPip());
    }
}