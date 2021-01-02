<?php

declare(strict_types=1);


namespace Dominoes\Deck;


use Dominoes\Tile\Tile;

class TilesGenerator implements TilesGeneratorInterface
{
    public function createTiles(int $higherPip): array
    {
        if ($higherPip < 0) {
            throw Exception\TheHigherPipMustBeZeroOrHigher::create($higherPip);
        }

        $tiles = [];

        for ($left = 0; $left <= $higherPip; $left++) {
            for ($right = 0; $right <= $left; $right++) {
                $tiles[] = new Tile($left, $right);
            }
        }

        return $tiles;
    }

}