<?php

declare(strict_types=1);


namespace Dominoes\Round;


use Dominoes\Player\PlayerInterface;
use Dominoes\Round\Exception\NoMorePlayersToPlay;

interface RoundInterface
{
    /**
     * @return PlayerInterface
     *
     * @throws NoMorePlayersToPlay
     */
    public function nextPlayer(): PlayerInterface;
}