<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay\Validator\Exception;


use Dominoes\Tile\TileInterface;

class CanNotConnectOneTileToItSelf extends \RuntimeException implements InvalidTileConnectionInterface
{
    public static function create(TileInterface $tile): self
    {
        return new self(
            sprintf(
                'You can\'t connect a tile to it self. You\'re trying to connect the tile [%s:%s] to it self.',
                $tile->getLeftPip(),
                $tile->getRightPip(),
            )
        );
    }
}