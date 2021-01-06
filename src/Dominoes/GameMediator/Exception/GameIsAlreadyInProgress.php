<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\Exception;


class GameIsAlreadyInProgress extends GameException
{

    public static function create(): self
    {
        return new self('Operation not allowed. The game is already in progress.');
    }
}