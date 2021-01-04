<?php

declare(strict_types=1);


namespace Dominoes\Deck;


use Dominoes\Deck\Exception\TheHighestPipMustBeZeroOrHigher;
use Dominoes\Tile\TilesCollectionInterface;

interface DeckFactoryInterface
{
    /**
     * Generate all the tiles for a deck which the which tile pip is defined by the parameter $highestPip.
     *
     * @param int $highestPip
     *  Must be zero or bigger.
     *
     * @return TilesCollectionInterface
     *
     * @throws TheHighestPipMustBeZeroOrHigher If $highestPip is less than zero.
     */
    public function createDeck(int $highestPip): TilesCollectionInterface;
}