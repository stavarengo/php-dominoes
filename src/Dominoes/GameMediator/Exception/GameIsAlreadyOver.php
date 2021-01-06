<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\Exception;


class GameIsAlreadyOver extends GameException
{

    public static function create(): self
    {
        return new self('Operation not allowed. The game is already over.');
    }
}