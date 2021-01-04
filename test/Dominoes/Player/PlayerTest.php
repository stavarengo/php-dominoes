<?php

declare(strict_types=1);

namespace Test\Dominoes\Player;

use Dominoes\Player\Player;
use Dominoes\Tile\TilesCollectionInterface;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    public function testGetTiles()
    {
        $tiles = $this->createStub(TilesCollectionInterface::class);

        $player = new Player('', $tiles);

        $this->assertSame($tiles, $player->getTiles());
    }

    /**
     * @dataProvider nameDataProvider
     */
    public function testGetName(string $name)
    {
        $this->assertEquals($name, (new Player($name, $this->createStub(TilesCollectionInterface::class)))->getName());
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
