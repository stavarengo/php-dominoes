<?php

declare(strict_types=1);


namespace Dominoes\LineOfPlay\Validator;


use Dominoes\Tile\TileInterface;

class ConnectionValidator implements ConnectionValidatorInterface
{
    public function validateConnection(TileInterface $left, TileInterface $right): void
    {
        if ($left->equalsTo($right)) {
            throw Exception\CanNotConnectOneTileToItSelf::create($left);
        }
        if ($left->getRightPip() != $right->getLeftPip()) {
            throw Exception\InvalidTileConnection::create($left, $right);
        }
    }
}