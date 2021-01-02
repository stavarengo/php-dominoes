<?php

declare(strict_types=1);

namespace Deck;

use Dominoes\Deck\Exception\TheHigherPipMustBeZeroOrHigher;
use Dominoes\Deck\TilesGenerator;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class TilesGeneratorTest extends TestCase
{
    public function testNegativeHigherPip()
    {
        $higherPip = -1;
        $this->expectExceptionObject(TheHigherPipMustBeZeroOrHigher::create($higherPip));

        (new TilesGenerator())->createTiles($higherPip);
    }

    /**
     * @dataProvider createTilesDataProvider
     */
    public function testCreateTiles(int $higherPip, array $expectedTiles)
    {
        $tiles = (new TilesGenerator())->createTiles($higherPip);

        $this->assertCount(count($expectedTiles), $tiles);;

        // Convert the tiles to String to make it easy to compare to the expected result
        $tilesResult = array_map(
            fn(TileInterface $tile) => sprintf('%s:%s', $tile->getLeftPip(), $tile->getRightPip()),
            $tiles
        );
        sort($tilesResult);

        $this->assertJsonStringEqualsJsonString(json_encode($expectedTiles), json_encode($tilesResult));
    }

    public function createTilesDataProvider()
    {
        return [
            [0, ['0:0']],
            [1, ['0:0', '1:0', '1:1']],
            [2, ['0:0', '1:0', '1:1', '2:0', '2:1', '2:2']],
        ];
    }
}
