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
    private array $players = [];

    private int $currentPlayerKey;

    public function __construct()
    {
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

    public function getCurrentPlayer(): PlayerInterface
    {
        if (!$this->players) {
            throw NoMorePlayersToPlay::create();
        }

        if ($this->currentPlayerKey < 0) {
            $this->currentPlayerKey = 0;
        }

        return $this->players[$this->currentPlayerKey];
    }

    public function countPlayers(): int
    {
        return count($this->players);
    }

    public function setPlayers(PlayerInterface ...$players): RoundManagerInterface
    {
        $this->players = $players;

        return $this;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }
}