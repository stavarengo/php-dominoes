<?php

declare(strict_types=1);

namespace Test\Dominoes\Tile;

use Dominoes\Tile\Tile;
use PHPUnit\Framework\TestCase;

class TileTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testTile(int $left, int $right)
    {
        $tile = new Tile($left, $right);
        $this->assertEquals($left, $tile->getLeftPip());
        $this->assertEquals($right, $tile->getRightPip());
        $this->assertEquals(sprintf('<%s:%s>', $tile->getLeftPip(), $tile->getRightPip()), $tile->__toString());
    }

    public function testEqualsTo()
    {
        $tile = new Tile(1, 2);

        $this->assertTrue($tile->equalsTo($tile));
        $this->assertTrue($tile->equalsTo(clone $tile));
        $this->assertTrue($tile->equalsTo(new Tile($tile->getLeftPip(), $tile->getRightPip())));
        $this->assertTrue($tile->equalsTo(new Tile($tile->getRightPip(), $tile->getLeftPip())));

        $this->assertFalse($tile->equalsTo(new Tile($tile->getLeftPip(), $tile->getRightPip() + 1)));
        $this->assertFalse($tile->equalsTo(new Tile($tile->getLeftPip() + 1, $tile->getRightPip())));
        $this->assertFalse($tile->equalsTo(new Tile($tile->getLeftPip() + 1, $tile->getRightPip() + 1)));
        $this->assertFalse($tile->equalsTo(null));
    }

    public function dataProvider(): array
    {
        return [
            [0, 1],
            [15, 40],
            [1, 1],
        ];
    }
}
