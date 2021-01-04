<?php

declare(strict_types=1);


namespace Dominoes\Deck;


use Dominoes\Tile\Tile;

class DeckFactory implements DeckFactoryInterface
{
    public function createDeck(int $highestPip): DeckInterface
    {
        if ($highestPip < 0) {
            throw Exception\TheHighestPipMustBeZeroOrHigher::create($highestPip);
        }

        $tiles = [];

        for ($left = 0; $left <= $highestPip; $left++) {
            for ($right = 0; $right <= $left; $right++) {
                $tiles[] = new Tile($left, $right);
            }
        }

        return new Deck(...$tiles);
    }

}