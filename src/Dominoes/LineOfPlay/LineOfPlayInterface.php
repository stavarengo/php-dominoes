<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay;


use Dominoes\LineOfPlay\Validator\Exception\InvalidTileConnection;
use Dominoes\Tile\TileInterface;

interface LineOfPlayInterface
{
    /**
     * Returns tiles in the order they are connected.
     *
     * @return TileInterface[]
     */
    public function getTiles(): array;

    /**
     * Connect the $tile to the left side of the line of play.
     * The $tile will be automatically rotated if necessary.
     *
     * @param TileInterface $tile
     *
     * @return $this
     *
     * @throws InvalidTileConnection
     *  If the $tile can't be connected to the left side even after it was rotated.
     */
    public function connectLeft(TileInterface $tile): self;

    /**
     * Connect the $tile to the right side of the line of play.
     * The $tile will be automatically rotated if necessary.
     *
     * @param TileInterface $tile
     *
     * @return $this
     *
     * @throws InvalidTileConnection
     *  If the $tile can't be connected to the right side even after it was rotated.
     */
    public function connectRight(TileInterface $tile): self;
}