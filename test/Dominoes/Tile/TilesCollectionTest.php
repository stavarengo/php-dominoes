<?php

declare(strict_types=1);

namespace Test\Dominoes\Tile;

use Dominoes\Tile\Exception\CantDrawFromAnEmptyCollection;
use Dominoes\Tile\TileInterface;
use Dominoes\Tile\TilesCollection;
use PHPUnit\Framework\TestCase;

class TilesCollectionTest extends TestCase
{
    public function testTryInitializeCollectionWithDuplicatedTiles()
    {
        $tileA = $this->createStub(TileInterface::class);
        $tileA->method('equalsTo')->willReturn(true);

        $tileB = $this->createStub(TileInterface::class);
        $tileB->method('equalsTo')->willReturn(false);

        $collection = new TilesCollection($tileA, $tileB);
        $this->assertEquals(1, $collection->countTiles());
        $this->assertContains($tileA, $collection->getItems());
    }

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

    public function testAddTile()
    {
        $collection = new TilesCollection(...[]);
        $this->assertEmpty($collection->getItems());

        $tileOneTwo = $this->createTileStub(1, 2);
        $tileOneThree = $this->createTileStub(1, 3);

        $collection->addTile($tileOneTwo);
        $this->assertEquals(1, $collection->countTiles());
        $this->assertContains($tileOneTwo, $collection->getItems());

        $collection->addTile($tileOneThree);
        $this->assertEquals(2, $collection->countTiles());
        $this->assertContains($tileOneTwo, $collection->getItems());
        $this->assertContains($tileOneThree, $collection->getItems());
    }

    public function testAddDuplicatedTile()
    {
        $tileA = $this->createStub(TileInterface::class);
        $tileA->method('equalsTo')->willReturn(true);
        $tileB = $this->createStub(TileInterface::class);
        $tileB->method('equalsTo')->willReturn(false);

        $collection = new TilesCollection($tileA);
        $collection->addTile($tileB);
        $this->assertEquals(1, $collection->countTiles());
        $this->assertContains($tileA, $collection->getItems());
    }

    public function testRemoveTile()
    {
        $collection = new TilesCollection(
            $tileOneTwo = $this->createTileStub(1, 2),
            $tileOneThree = $this->createTileStub(1, 3),
        );
        $this->assertEquals(2, $collection->countTiles());

        $collection->removeTile($this->createStub(TileInterface::class));
        $this->assertEquals(2, $collection->countTiles());
        $this->assertContains($tileOneTwo, $collection->getItems());
        $this->assertContains($tileOneThree, $collection->getItems());

        $collection->removeTile($tileOneTwo);
        $this->assertEquals(1, $collection->countTiles());
        $this->assertNotContains($tileOneTwo, $collection->getItems());
        $this->assertContains($tileOneThree, $collection->getItems());

        $collection->removeTile($tileOneThree);
        $this->assertEmpty($collection->countTiles());
        $this->assertNotContains($tileOneTwo, $collection->getItems());
        $this->assertNotContains($tileOneThree, $collection->getItems());

        $collection->removeTile($this->createStub(TileInterface::class));
        $this->assertEmpty($collection->countTiles());
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
        $tile->method('getLeftPip')->willReturn($left);
        $tile->method('getRightPip')->willReturn($right);
        $tile->method('equalsTo')->willReturnCallback(
            fn(TileInterface $other): bool => $other->getRightPip() == $right && $other->getLeftPip() == $left
        );

        return $tile;
    }
}
