<?php

declare(strict_types=1);

namespace Test\Dominoes\Tile;

use Dominoes\Tile\RotatedTile;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class RotatedTileTest extends TestCase
{
    public function testGetLeft()
    {
        $originalTile = $this->createMock(TileInterface::class);
        $originalTile->expects($this->never())
            ->method('getLeftPip');

        $expectedPip = 2;
        $originalTile->expects($this->once())
            ->method('getRightPip')
            ->willReturn($expectedPip);

        $rotatedTile = new RotatedTile($originalTile);
        $this->assertEquals($expectedPip, $rotatedTile->getLeftPip());
    }

    public function testGetRight()
    {
        $originalTile = $this->createMock(TileInterface::class);
        $expectedPip = 1;
        $originalTile->expects($this->once())
            ->method('getLeftPip')
            ->willReturn($expectedPip);

        $originalTile->expects($this->never())
            ->method('getRightPip');

        $rotatedTile = new RotatedTile($originalTile);
        $this->assertEquals($expectedPip, $rotatedTile->getRightPip());
    }

    public function testEqualsTo()
    {
        $expectedResult = true;

        $originalTile = $this->createMock(TileInterface::class);
        $originalTile->expects($this->once())
            ->method('equalsTo')
            ->with($this->identicalTo($anotherTile = $this->createStub(TileInterface::class)))
            ->willReturn($expectedResult);

        $rotatedTile = new RotatedTile($originalTile);
        $this->assertEquals($expectedResult, $rotatedTile->equalsTo($anotherTile));
    }

    public function testToString()
    {
        $originalTile = $this->createStub(TileInterface::class);
        $originalTile->method('getLeftPip')->willReturn(1);
        $originalTile->method('getRightPip')->willReturn(2);

        $rotatedTile = new RotatedTile($originalTile);
        $this->assertEquals(
            sprintf('<%s:%s>', $rotatedTile->getLeftPip(), $rotatedTile->getRightPip()),
            $rotatedTile->__toString()
        );
    }
}
