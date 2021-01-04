<?php

declare(strict_types=1);

namespace Player;

use Dominoes\Player\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
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
