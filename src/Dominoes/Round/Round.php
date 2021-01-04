<?php

declare(strict_types=1);


namespace Dominoes\Round;


use Dominoes\Player\PlayerInterface;
use Dominoes\Round\Exception\NoMorePlayersToPlay;

class Round implements RoundInterface
{
    /**
     * @var PlayerInterface[]
     */
    private array $players;

    private int $currentPlayerKey;

    public function __construct(PlayerInterface ...$players)
    {
        $this->players = $players;
        $this->currentPlayerKey = -1;
    }

    public function nextPlayer(): PlayerInterface
    {
        if (!$this->players) {
            throw NoMorePlayersToPlay::create();
        }

        if (++$this->currentPlayerKey == count($this->players)) {
            $this->currentPlayerKey = 0;
        }

        return $this->players[$this->currentPlayerKey];
    }
}