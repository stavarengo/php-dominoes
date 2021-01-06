<?php

declare(strict_types=1);


namespace Console;


use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\LineOfPlay\ConnectionSpot\ConnectionSpotInterface;
use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\Player\PlayerInterface;
use Dominoes\Tile\TileInterface;
use Psr\Log\LoggerInterface;

class GameListener implements GameListenerInterface
{

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function playerConnectedTile(
        PlayerInterface $whoPlayed,
        TileInterface $tile,
        ConnectionSpotInterface $connectionSpot,
        LineOfPlayInterface $currentLineOfPlay,
    ): void {
        $this->logger->debug(sprintf('%s played %s', $whoPlayed->getName(), $tile));
        $this->logger->debug(sprintf('Board is now: %s', implode(' ', $currentLineOfPlay->getTiles())));
    }

    public function playerDraw(PlayerInterface $player, TileInterface $tile): void
    {
        $this->logger->debug(sprintf('%s draw %s', $player->getName(), $tile));
    }

    public function playerPassedTurn(PlayerInterface $player)
    {
        $this->logger->debug(sprintf('%s passed turn', $player->getName()));
    }
}