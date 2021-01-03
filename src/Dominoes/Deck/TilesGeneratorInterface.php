<?php

declare(strict_types=1);


namespace Dominoes\Deck;


use Dominoes\Deck\Exception\TheHigherPipMustBeZeroOrHigher;
use Dominoes\Tile\TileInterface;

interface TilesGeneratorInterface
{
    /**
     * Generate all the tiles for a deck which the which tile pip is defined by the parameter $higherPip.
     *
     * @param int $higherPip
     *  Must be zero or bigger.
     *
     * @return TileInterface[]
     *
     * @throws TheHigherPipMustBeZeroOrHigher
     *  If $higherPip is less than zero.
     */
    public function createTiles(int $higherPip): array;
}