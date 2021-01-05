<?php

declare(strict_types=1);

namespace Test\Dominoes\LineOfPlay;

use Dominoes\LineOfPlay\LineOfPlay;
use Dominoes\LineOfPlay\Validator\ConnectionValidatorInterface;
use Dominoes\LineOfPlay\Validator\Exception\InvalidTileConnection;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class LineOfPlayTest extends TestCase
{
    public function testGetTiles()
    {
        $lineOfPlay = new LineOfPlay($this->createStub(ConnectionValidatorInterface::class));
        $this->assertCount(0, $lineOfPlay->getTiles());

        $lineOfPlay->connectLeft($tile1 = $this->createStub(TileInterface::class));
        $this->assertCount(1, $lineOfPlay->getTiles());
        $this->assertSame($tile1, $lineOfPlay->getTiles()[0]);

        $lineOfPlay->connectRight($tile2 = $this->createStub(TileInterface::class));
        $this->assertCount(2, $lineOfPlay->getTiles());
        $this->assertSame($tile1, $lineOfPlay->getTiles()[0]);
        $this->assertSame($tile2, $lineOfPlay->getTiles()[1]);

        $lineOfPlay->connectLeft($tile3 = $this->createStub(TileInterface::class));
        $this->assertCount(3, $lineOfPlay->getTiles());
        $this->assertSame($tile3, $lineOfPlay->getTiles()[0]);
        $this->assertSame($tile1, $lineOfPlay->getTiles()[1]);
        $this->assertSame($tile2, $lineOfPlay->getTiles()[2]);

        $lineOfPlay->connectRight($tile4 = $this->createStub(TileInterface::class));
        $this->assertCount(4, $lineOfPlay->getTiles());
        $this->assertSame($tile3, $lineOfPlay->getTiles()[0]);
        $this->assertSame($tile1, $lineOfPlay->getTiles()[1]);
        $this->assertSame($tile2, $lineOfPlay->getTiles()[2]);
        $this->assertSame($tile4, $lineOfPlay->getTiles()[3]);

        $lineOfPlay->connectLeft($tile5 = $this->createStub(TileInterface::class));
        $this->assertCount(5, $lineOfPlay->getTiles());
        $this->assertSame($tile5, $lineOfPlay->getTiles()[0]);
        $this->assertSame($tile3, $lineOfPlay->getTiles()[1]);
        $this->assertSame($tile1, $lineOfPlay->getTiles()[2]);
        $this->assertSame($tile2, $lineOfPlay->getTiles()[3]);
        $this->assertSame($tile4, $lineOfPlay->getTiles()[4]);
    }

    public function testConnectFirstTile()
    {
        $tile = $this->createStub(TileInterface::class);
        $validator = $this->createStub(ConnectionValidatorInterface::class);

        $lineOfPlay = new LineOfPlay($validator);
        $lineOfPlay->connectLeft($tile);
        $this->assertCount(1, $lineOfPlay->getTiles());
        $this->assertSame($tile, $lineOfPlay->getTiles()[0]);

        $lineOfPlay = new LineOfPlay($validator);
        $lineOfPlay->connectRight($tile);
        $this->assertCount(1, $lineOfPlay->getTiles());
        $this->assertSame($tile, $lineOfPlay->getTiles()[0]);
    }

    public function testConnectTilesMustAddTileToTheCorrectSide()
    {
        $validator = $this->createStub(ConnectionValidatorInterface::class);

        $tile1 = $this->createStub(TileInterface::class);
        $tile2 = $this->createStub(TileInterface::class);
        $tile3 = $this->createStub(TileInterface::class);

        $lineOfPlay = new LineOfPlay($validator);
        $lineOfPlay->connectRight($tile1);


        $lineOfPlay->connectRight($tile2);
        $this->assertCount(2, $lineOfPlay->getTiles());
        $this->assertSame($tile2, $lineOfPlay->getTiles()[1]);

        $lineOfPlay->connectLeft($tile3);
        $this->assertCount(3, $lineOfPlay->getTiles());
        $this->assertSame($tile3, $lineOfPlay->getTiles()[0]);
        $this->assertSame($tile2, $lineOfPlay->getTiles()[2]);
    }

    public function testConnectTilesMustInvokeTheValidator()
    {
        $tile1 = $this->createStub(TileInterface::class);
        $tile2 = $this->createStub(TileInterface::class);
        $tile3 = $this->createStub(TileInterface::class);
        $tile4 = $this->createStub(TileInterface::class);
        $tile5 = $this->createStub(TileInterface::class);

        $validator = $this->createMock(ConnectionValidatorInterface::class);
        $validator->expects($this->exactly(4))
            ->method('validateConnection')
            ->withConsecutive(
                [$this->identicalTo($tile2), $this->identicalTo($tile1)],
                [$this->identicalTo($tile1), $this->identicalTo($tile3)],
                [$this->identicalTo($tile4), $this->identicalTo($tile2)],
                [$this->identicalTo($tile3), $this->identicalTo($tile5)],
            );

        $lineOfPlay = new LineOfPlay($validator);
        $lineOfPlay->connectLeft($tile1);

        $lineOfPlay->connectLeft($tile2);
        $lineOfPlay->connectRight($tile3);
        $lineOfPlay->connectLeft($tile4);
        $lineOfPlay->connectRight($tile5);
    }
    public function testConnectLeftMustThrowExceptionIfConnectionIsNotValid()
    {
        $exception = new InvalidTileConnection();
        $validator = $this->createMock(ConnectionValidatorInterface::class);
        $validator->expects($this->exactly(1))
            ->method('validateConnection')
            ->willThrowException($exception);

        $lineOfPlay = new LineOfPlay($validator);
        $lineOfPlay->connectLeft($this->createStub(TileInterface::class));

        $this->expectExceptionObject($exception);
        $lineOfPlay->connectLeft($this->createStub(TileInterface::class));

    }

    public function testConnectRightMustThrowExceptionIfConnectionIsNotValid()
    {
        $exception = new InvalidTileConnection();
        $validator = $this->createMock(ConnectionValidatorInterface::class);
        $validator->expects($this->exactly(1))
            ->method('validateConnection')
            ->willThrowException($exception);

        $lineOfPlay = new LineOfPlay($validator);
        $lineOfPlay->connectRight($this->createStub(TileInterface::class));

        $this->expectExceptionObject($exception);
        $lineOfPlay->connectRight($this->createStub(TileInterface::class));

    }
}
