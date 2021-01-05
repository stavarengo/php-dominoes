<?php

declare(strict_types=1);

namespace Test\Dominoes\LineOfPlay\ConnectionSpot;

use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpot;
use Dominoes\LineOfPlay\ConnectionSpot\Exception\CantConnectThisTile;
use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class ConnectionSpotTest extends TestCase
{
    public function testGetLineOfPlay()
    {
        $lineOfPlay = $this->createLineOfPlayStub();
        $this->assertSame($lineOfPlay, (new ConnectionSpot($lineOfPlay, true))->getLineOfPlay());
    }

    public function testGetConnectToLeft()
    {
        $lineOfPlay = $this->createLineOfPlayStub();

        $this->assertTrue((new ConnectionSpot($lineOfPlay, true))->getConnectToLeft());
        $this->assertFalse((new ConnectionSpot($lineOfPlay, false))->getConnectToLeft());
    }

    /**
     * @dataProvider canConnectToDataProvider
     */
    public function testCanConnectTo(
        LineOfPlayInterface $lineOfPlay,
        bool $connectToLeft,
        array $tiles,
        TileInterface ...$expectedResult,
    ) {
        $result = (new ConnectionSpot($lineOfPlay, $connectToLeft))->canConnectedTo(...$tiles);

        $this->assertSameSize($expectedResult, $result);
        $this->assertCollectionContainsSameTiles($expectedResult, $result);
    }

    public function testConnect()
    {
        $tileToConnect = $this->createTileStub();

        $lineOfPlay = $this->createMock(LineOfPlayInterface::class);
        $lineOfPlay->expects($this->once())
            ->method('withPrependedTile')
            ->with($tileToConnect);
        (new ConnectionSpot($lineOfPlay, true))->connect($tileToConnect);

        $lineOfPlay = $this->createMock(LineOfPlayInterface::class);
        $lineOfPlay->expects($this->once())
            ->method('withAppendedTile')
            ->with($tileToConnect);
        (new ConnectionSpot($lineOfPlay, false))->connect($tileToConnect);
    }

    public function testTryToConnectAnInvalidTile()
    {
        $tile = $this->createTileStub(equalsTo: true);
        $lineOfPlay = $this->createLineOfPlayStub($tile);

        $connectionSpot = new ConnectionSpot($lineOfPlay, true);
        $this->assertEmpty($connectionSpot->canConnectedTo($tile));

        $this->expectExceptionObject(CantConnectThisTile::create($tile, $lineOfPlay));
        $connectionSpot->connect($tile);
    }

    public function canConnectToDataProvider()
    {
        return [
            [
                $this->createLineOfPlayStub(...[]),
                true,
                $tiles = [$this->createTileStub(), $this->createTileStub()],
                ...$tiles,
            ],
            [
                $this->createLineOfPlayStub(...[]),
                false,
                $tiles = [$this->createTileStub(), $this->createTileStub()],
                ...$tiles,
            ],
            [
                $this->createLineOfPlayStub($this->createTileStub(1, 2), $this->createTileStub(2, 3)),
                true,
                $tiles = [
                    $this->createTileStub(1, 3),
                    $this->createTileStub(6, 1),
                    $this->createTileStub(3, 1),
                    $this->createTileStub(2, 2),
                    $this->createTileStub(),
                ],
                ...[$tiles[1], $tiles[2]],
            ],
            [
                $this->createLineOfPlayStub($this->createTileStub(1, 2), $this->createTileStub(2, 3)),
                false,
                $tiles = [
                    $this->createTileStub(1, 3),
                    $this->createTileStub(3, 6),
                    $this->createTileStub(3, 1),
                    $this->createTileStub(2, 2),
                    $this->createTileStub(),
                ],
                ...[$tiles[1], $tiles[2]],
            ],
            [
                $this->createLineOfPlayStub($this->createTileStub(1, 1, true)),
                true,
                [$this->createTileStub(1, 1, true)],
                ...[],
            ],
            [
                $this->createLineOfPlayStub($this->createTileStub(1, 1, true)),
                false,
                [$this->createTileStub(1, 1, true)],
                ...[],
            ],
        ];
    }

    private function createLineOfPlayStub(TileInterface ...$currentTiles): LineOfPlayInterface
    {
        $lineOfPlay = $this->createStub(LineOfPlayInterface::class);
        $lineOfPlay->method('getTiles')->willReturn($currentTiles);

        return $lineOfPlay;
    }

    /**
     * @param int|null $leftPip
     * @param int|null $rightPip
     * @param bool|null $equalsTo
     * @return TileInterface
     */
    private function createTileStub(?int $leftPip = null, ?int $rightPip = null, ?bool $equalsTo = null): TileInterface
    {
        $tile = $this->createStub(TileInterface::class);

        if ($leftPip !== null) {
            $tile->method('getLeftPip')->willReturn($leftPip);
        }
        if ($rightPip !== null) {
            $tile->method('getRightPip')->willReturn($rightPip);
        }
        if ($equalsTo !== null) {
            $tile->method('equalsTo')->willReturn($equalsTo);
        }

        return $tile;
    }

    /**
     * @param TileInterface[] $expected
     * @param TileInterface[] $current
     */
    private function assertCollectionContainsSameTiles(array $expected, array $current)
    {
        // Convert the tiles to String to make it easy to compare to the expected result
        $toString = fn(TileInterface $tile) => sprintf('%s:%s', $tile->getLeftPip(), $tile->getRightPip());
        $current = array_map($toString, $current);
        $expected = array_map($toString, $expected);
        sort($current);
        sort($expected);

        $this->assertEquals(implode(',', $expected), implode(',', $current));
    }
}
