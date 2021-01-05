<?php

declare(strict_types=1);

namespace LineOfPlay\Validator;

use Dominoes\LineOfPlay\Validator\ConnectionValidator;
use Dominoes\LineOfPlay\Validator\Exception\CanNotConnectOneTileToItSelf;
use Dominoes\LineOfPlay\Validator\Exception\InvalidTileConnection;
use Dominoes\LineOfPlay\Validator\Exception\InvalidTileConnectionInterface;
use Dominoes\Tile\TileInterface;
use PHPUnit\Framework\TestCase;

class ConnectionValidatorTest extends TestCase
{
    /**
     * @dataProvider validConnectionsDataProvider
     */
    public function testValidConnections(TileInterface $left, TileInterface $right)
    {
        $connectionValidator = new ConnectionValidator();

        $connectionValidator->validateConnection($left, $right);

        $this->expectNotToPerformAssertions();
    }

    /**
     * @dataProvider invalidConnectionsDataProvider
     */
    public function testInvalidConnections(
        TileInterface $left,
        TileInterface $right,
        InvalidTileConnectionInterface $expectedException
    ) {
        $connectionValidator = new ConnectionValidator();

        /** @var \Exception $expectedException */
        $this->expectExceptionObject($expectedException);

        $connectionValidator->validateConnection($left, $right);
    }

    public function validConnectionsDataProvider(): array
    {
        return [
            [
                $this->createTileStub(5, 5, false),
                $this->createTileStub(5, 6, false),
            ],
            [
                $tile = $this->createTileStub(5, 5, false),
                $tile
            ],
            [
                $this->createTileStub(0, 0, false),
                $this->createTileStub(0, 0, false),
            ],
        ];
    }

    public function invalidConnectionsDataProvider(): array
    {
        return [
            [
                $left = $this->createTileStub(0, 0, false),
                $right = $this->createTileStub(1, 1, false),
                InvalidTileConnection::create($left, $right),
            ],
            [
                $left = $this->createTileStub(0, 0, true),
                $this->createTileStub(0, 1, true),
                CanNotConnectOneTileToItSelf::create($left),
            ],
        ];
    }

    private function createTileStub(int $left, int $right, bool $equalsTo): TileInterface
    {
        $tile = $this->createStub(TileInterface::class);
        $tile->method('getLeftPip')->willReturn($left);
        $tile->method('getRightPip')->willReturn($right);
        $tile->method('equalsTo')->willReturn($equalsTo);

        return $tile;
    }
}
