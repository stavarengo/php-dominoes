<?php

declare(strict_types=1);


namespace Dominoes\RoundManager;


use Dominoes\Player\PlayerInterface;
use Dominoes\RoundManager\Exception\NoMorePlayersToPlay;

class RoundManager implements RoundManagerInterface
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