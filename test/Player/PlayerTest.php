<?php

declare(strict_types=1);

namespace Player;

use Dominoes\Player\Player;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    public function testGetTiles()
    {
        $player = new Player('', ...[]);
        $this->assertCount(0, $player->getTiles());

        $tiles = [
            $this->createStub(TileInterface::class),
            $this->createStub(TileInterface::class),
        ];
        $player = new Player('', ...$tiles);
        $this->assertCount(2, $player->getTiles());
        $this->assertContains($tiles[0], $player->getTiles());
        $this->assertContains($tiles[1], $player->getTiles());
    }

    /**
     * @dataProvider nameDataProvider
     */
    public function testGetName(string $name)
    {
        $this->assertEquals($name, (new Player($name))->getName());
    }

    public function nameDataProvider(): array
    {
        return [
            ['Balrog'],
            ['Beorning'],
            ['Bree'],
            ['Dale'],
        ];
    }
}
