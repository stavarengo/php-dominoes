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
        $this->assertEquals("$left$right", $tile->getId());
        $this->assertEquals($left, $tile->getLeftPip());
        $this->assertEquals($right, $tile->getRightPip());
        $this->assertEquals(sprintf('<%s:%s>', $tile->getLeftPip(), $tile->getRightPip()), $tile->__toString());
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
