<?php

declare(strict_types=1);


namespace Dominoes\Bot;


use Dominoes\GameMediator\GameMediatorInterface;

interface BotInterface
{
    /**
     * Decide which move to perform based on the current $game.
     *
     * @param GameMediatorInterface $gameMediator
     */
    public function play(GameMediatorInterface $gameMediator): void;
}