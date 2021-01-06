<?php

declare(strict_types=1);


namespace Dominoes\RoundManager;


use Dominoes\Player\PlayerInterface;
use Dominoes\RoundManager\Exception\NoMorePlayersToPlay;

interface RoundManagerInterface
{
    /**
     * @return PlayerInterface
     *
     * @throws NoMorePlayersToPlay
     */
    public function nextPlayer(): PlayerInterface;
}