<?php

declare(strict_types=1);


namespace Dominoes\Tile;

interface TileInterface extends \Stringable
{
    /**
     * Must return an unique ID for this pile based or its pips.
     * @return string
     */
    public function getId(): string;

    public function getLeftPip(): int;

    public function getRightPip(): int;
}