<?php

declare(strict_types=1);

namespace Test\Dominoes\Deck;

use Dominoes\Deck\Exception\TheHighestPipMustBeZeroOrHigher;
use Dominoes\Deck\DeckFactory;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class DeckFactoryTest extends TestCase
{
    public function testNegativeHighestPip()
    {
        $highestPip = -1;
        $this->expectExceptionObject(TheHighestPipMustBeZeroOrHigher::create($highestPip));

        (new DeckFactory())->createDeck($highestPip);
    }

    /**
     * @dataProvider createTilesDataProvider
     */
    public function testCreateDeck(int $highestPip, array $expectedTiles)
    {
        $deck = (new DeckFactory())->createDeck($highestPip);

        $this->assertEquals(count($expectedTiles), $deck->countTiles());

        // Convert the tiles to String to make it easy to compare to the expected result
        $tilesResult = array_map(
            fn(TileInterface $tile) => sprintf('%s:%s', $tile->getLeftPip(), $tile->getRightPip()),
            $deck->drawRandomTiles($deck->countTiles())->getItems()
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
