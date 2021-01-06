<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\Exception;


class GameDidNotStartYet extends GameException
{

    public static function create(): self
    {
        return new self("Operation not allowed. The game hasn't start yet.");
    }
}