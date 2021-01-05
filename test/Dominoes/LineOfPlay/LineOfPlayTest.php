<?php

declare(strict_types=1);

namespace Test\Dominoes\LineOfPlay;

use Dominoes\LineOfPlay\LineOfPlay;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class LineOfPlayTest extends TestCase
{
    public function testGetTiles()
    {
        $lineOfPlay = new LineOfPlay();
        $this->assertCount(0, $lineOfPlay->getTiles());

        $tile = $this->createStub(TileInterface::class);

        $lineOfPlay = $lineOfPlay->withAppendedTile($tile);
        $this->assertCount(1, $lineOfPlay->getTiles());
        $this->assertSame($tile, $lineOfPlay->getTiles()[0]);
    }

    public function testWithPrependedTile()
    {
        $lineOfPlay = new LineOfPlay();
        $this->assertEmpty($lineOfPlay->getTiles());

        $tile1 = $this->createTileStub(1, 2);
        $tile2 = $this->createTileStub(3, 4);
        $tile3 = $this->createTileStub(5, 6);

        $lineOfPlay1 = $lineOfPlay->withPrependedTile($tile1);
        $lineOfPlay2 = $lineOfPlay1->withPrependedTile($tile2);
        $lineOfPlay3 = $lineOfPlay2->withPrependedTile($tile3);

        $this->assertNotSame($lineOfPlay, $lineOfPlay1);
        $this->assertNotSame($lineOfPlay1, $lineOfPlay2);
        $this->assertNotSame($lineOfPlay2, $lineOfPlay3);

        $this->assertCollectionContainsSameTilesInTheSameOrder([$tile1], $lineOfPlay1->getTiles());
        $this->assertCollectionContainsSameTilesInTheSameOrder([$tile2, $tile1], $lineOfPlay2->getTiles());
        $this->assertCollectionContainsSameTilesInTheSameOrder([$tile3, $tile2, $tile1], $lineOfPlay3->getTiles());
    }

    public function testWithAppendedTile()
    {
        $lineOfPlay = new LineOfPlay();
        $this->assertEmpty($lineOfPlay->getTiles());

        $tile1 = $this->createTileStub(1, 2);
        $tile2 = $this->createTileStub(3, 4);
        $tile3 = $this->createTileStub(5, 6);

        $lineOfPlay1 = $lineOfPlay->withAppendedTile($tile1);
        $lineOfPlay2 = $lineOfPlay1->withAppendedTile($tile2);
        $lineOfPlay3 = $lineOfPlay2->withAppendedTile($tile3);

        $this->assertNotSame($lineOfPlay, $lineOfPlay1);
        $this->assertNotSame($lineOfPlay1, $lineOfPlay2);
        $this->assertNotSame($lineOfPlay2, $lineOfPlay3);

        $this->assertCollectionContainsSameTilesInTheSameOrder([$tile1], $lineOfPlay1->getTiles());
        $this->assertCollectionContainsSameTilesInTheSameOrder([$tile1, $tile2], $lineOfPlay2->getTiles());
        $this->assertCollectionContainsSameTilesInTheSameOrder([$tile1, $tile2, $tile3], $lineOfPlay3->getTiles());
    }

    public function testGetConnectionSpots()
    {
        $lineOfPlay = new LineOfPlay();

        $connectionSpots = $lineOfPlay->getConnectionSpots();

        $this->assertCount(2, $connectionSpots);

        $this->assertSame($lineOfPlay, $connectionSpots[0]->getLineOfPlay());
        $this->assertSame($lineOfPlay, $connectionSpots[1]->getLineOfPlay());
        $this->assertFalse($connectionSpots[0]->getConnectToLeft());
        $this->assertTrue($connectionSpots[1]->getConnectToLeft());
    }

    /**
     * @param TileInterface[] $expected
     * @param TileInterface[] $current
     */
    private function assertCollectionContainsSameTilesInTheSameOrder(array $expected, array $current)
    {
        // Convert the tiles to String to make it easy to compare to the expected result
        $toString = fn(TileInterface $tile) => sprintf('%s:%s', $tile->getLeftPip(), $tile->getRightPip());
        $current = array_map($toString, $current);
        $expected = array_map($toString, $expected);

        $this->assertEquals(implode(',', $expected), implode(',', $current));
    }

    private function createTileStub(int $leftPip, int $rightPip): TileInterface
    {
        $tile = $this->createStub(TileInterface::class);

        $tile->method('getLeftPip')->willReturn($leftPip);
        $tile->method('getRightPip')->willReturn($rightPip);

        return $tile;
    }
}
