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
        $tiles = $this->createTilesStub(2);
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
        $allTiles = $this->createTilesStub(3);
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

    public function testGetTilesWithPips()
    {
        $collection = new TilesCollection(...[]);

        $this->assertEmpty($collection->getTilesWithPips(0));

        $collection = new TilesCollection(
            $tileOneTwo = $this->createTileStub(1, 2),
            $tileOneThree = $this->createTileStub(1, 3),
            $tileFiveSix = $this->createTileStub(5, 6),
            $tileSixSix = $this->createTileStub(6, 6),
        );

        $this->assertEmpty($collection->getTilesWithPips(0, 4));

        $tiles = $collection->getTilesWithPips(5);
        $this->assertCount(1, $tiles);
        $this->assertSame($tileFiveSix, $tiles[0]);

        $tiles = $collection->getTilesWithPips(2);
        $this->assertCount(1, $tiles);
        $this->assertSame($tileOneTwo, $tiles[0]);

        $tiles = $collection->getTilesWithPips(6);
        $this->assertCount(2, $tiles);
        $this->assertContains($tileFiveSix, $tiles);
        $this->assertContains($tileSixSix, $tiles);

        $tiles = $collection->getTilesWithPips(3, 5);
        $this->assertCount(2, $tiles);
        $this->assertContains($tileOneThree, $tiles);
        $this->assertContains($tileFiveSix, $tiles);
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
            [$this->createTilesStub(1)],
            [$this->createTilesStub(5)],
        ];
    }

    /**
     * @param int $howMuch
     * @return TileInterface[]
     */
    private function createTilesStub(int $howMuch): array
    {
        return array_map(fn(int $number) => $this->createTileStub($number, $number + 1), range(0, $howMuch - 1));
    }

    private function createTileStub(int $left, int $right): TileInterface
    {
        $tile = $this->createStub(TileInterface::class);
        $tile->method('getId')->willReturn("$left:$right");
        $tile->method('getLeftPip')->willReturn($left);
        $tile->method('getRightPip')->willReturn($right);

        return $tile;
    }
}
