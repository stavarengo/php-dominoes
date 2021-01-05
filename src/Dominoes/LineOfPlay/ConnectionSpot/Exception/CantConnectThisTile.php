<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay\ConnectionSpot\Exception;


use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Tile\TileInterface;

class CantConnectThisTile extends \RuntimeException
{
    public static function create(TileInterface $tile, LineOfPlayInterface $lineOfPlay): self
    {
        return new self(
            sprintf(
                'Can\'t connect the tile [%s:%s] to the line of play [%s].',
                $tile->getLeftPip(),
                $tile->getRightPip(),
                implode(
                    '-',
                    array_map(
                        fn(TileInterface $item): string => $item->getLeftPip() . ':' . $item->getRightPip(),
                        $lineOfPlay->getTiles()
                    )
                )
            )
        );
    }
}