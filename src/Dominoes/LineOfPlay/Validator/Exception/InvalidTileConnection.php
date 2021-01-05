<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay\Validator\Exception;


use Dominoes\Tile\TileInterface;

class InvalidTileConnection extends \RuntimeException implements InvalidTileConnectionInterface
{
    public static function create(TileInterface $left, TileInterface $right): self
    {
        return new self(
            sprintf(
                'Invalid tile connection: [%s:%s][%s:%s].',
                $left->getLeftPip(),
                $left->getRightPip(),
                $right->getLeftPip(),
                $right->getRightPip()
            )
        );
    }
}