<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay\Validator;


use Dominoes\Tile\TileInterface;

interface ConnectionValidatorInterface
{
    /**
     * @param TileInterface $left
     * @param TileInterface $right
     *
     * @throws Exception\InvalidTileConnectionInterface
     */
    public function validateConnection(TileInterface $left, TileInterface $right): void;
}