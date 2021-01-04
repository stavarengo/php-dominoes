<?php

declare(strict_types=1);

namespace Deck;

use Dominoes\Deck\Deck;
use Dominoes\Deck\Exception\CantDrawFromAnEmptyDeck;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{
    /**
     * @dataProvider tilesDataProvider
     */
    public function testCountTiles(array $tiles)
    {
        $deck = new Deck(...$tiles);
        $this->assertEquals(count($tiles), $deck->countTiles());
    }

    public function testDrawRandomTile()
    {
        $tiles = $this->createTiles(2);
        $expectedTilesLeft = count($tiles);

        $deck = new Deck(...$tiles);
        $this->assertEquals($expectedTilesLeft, $deck->countTiles());

        $tile1 = $deck->drawRandomTile();
        $this->assertEquals(--$expectedTilesLeft, $deck->countTiles());

        $tile2 = $deck->drawRandomTile();
        $this->assertEquals(--$expectedTilesLeft, $deck->countTiles());

        $this->assertNotSame($tile1, $tile2);

        $this->expectExceptionObject(CantDrawFromAnEmptyDeck::create());
        $deck->drawRandomTile();
    }

    public function testDrawRandomTiles()
    {
        $allTiles = $this->createTiles(3);
        $deck = new Deck(...$allTiles);

        $drawCount = 2;
        $drawTiles = $deck->drawRandomTiles($drawCount);

        $this->assertEquals(1, $deck->countTiles());
        $this->assertCount($drawCount, $drawTiles);
        $this->assertContains($drawTiles[0], $allTiles);
        $this->assertContains($drawTiles[1], $allTiles);

        $this->expectExceptionObject(CantDrawFromAnEmptyDeck::create());
        $deck->drawRandomTiles($deck->countTiles() + 1);
    }

    public function tilesDataProvider(): array
    {
        return [
            [[]],
            [$this->createTiles(1)],
            [$this->createTiles(5)],
        ];
    }

    /**
     * @param int $howMuch
     * @return TileInterface[]
     */
    private function createTiles(int $howMuch): array
    {
        return array_map(fn() => $this->createStub(TileInterface::class), range(0, $howMuch - 1));
    }
}
