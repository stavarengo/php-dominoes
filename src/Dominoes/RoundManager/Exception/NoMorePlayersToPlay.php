<?php

declare(strict_types=1);


namespace Dominoes\RoundManager\Exception;


class NoMorePlayersToPlay extends \RuntimeException
{

    public static function create(): self
    {
        return new self('There are no more players to play.');
    }
}