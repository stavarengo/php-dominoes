<?php

declare(strict_types=1);

namespace Test\Dominoes\Tile;

use Dominoes\Tile\Exception\CantDrawFromAnEmptyCollection;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollection;
use PHPUnit\Framework\TestCase;

class TilesCollectionTest extends TestCase
{
    /**
     * @dataProvider tilesDataProvider
     */
    public function testCountTiles(array $tiles)
    {
        $collection = new TilesCollection(...$tiles);
        $this->assertEquals(count($tiles), $collection->countTiles());
    }

    public function testDrawRandomTile()
    {
        $tiles = $this->createTiles(2);
        $expectedTilesLeft = count($tiles);

        $collection = new TilesCollection(...$tiles);
        $this->assertEquals($expectedTilesLeft, $collection->countTiles());

        $tile1 = $collection->drawRandomTile();
        $this->assertEquals(--$expectedTilesLeft, $collection->countTiles());

        $tile2 = $collection->drawRandomTile();
        $this->assertEquals(--$expectedTilesLeft, $collection->countTiles());

        $this->assertNotSame($tile1, $tile2);

        $this->expectExceptionObject(CantDrawFromAnEmptyCollection::create());
        $collection->drawRandomTile();
    }

    public function testDrawRandomTiles()
    {
        $allTiles = $this->createTiles(3);
        $collection = new TilesCollection(...$allTiles);

        $drawCount = 2;
        $drawTiles = $collection->drawRandomTiles($drawCount);

        $this->assertEquals(1, $collection->countTiles());
        $this->assertEquals($drawCount, $drawTiles->countTiles());
        $this->assertContains($drawTiles->getItems()[0], $allTiles);
        $this->assertContains($drawTiles->getItems()[1], $allTiles);

        $this->expectExceptionObject(CantDrawFromAnEmptyCollection::create());
        $collection->drawRandomTiles($collection->countTiles() + 1);
    }

    /**
     * @dataProvider tilesDataProvider
     */
    public function testGetTiles(array $tiles)
    {
        $collection = new TilesCollection(...$tiles);
        $this->assertJsonStringEqualsJsonString(json_encode($tiles), json_encode($collection->getItems()));
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
