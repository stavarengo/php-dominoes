<?php

declare(strict_types=1);


namespace Console;


use Dominoes\Bot\BotInterface;
use Dominoes\Deck\DeckFactoryInterface;
use Dominoes\GameMediator\GameListenerInterface;
use Dominoes\GameMediator\GameMediatorInterface;
use Dominoes\Player\Player;
use Dominoes\Player\PlayerInterface;
use Psr\Log\LoggerInterface;

class Application
{

    public function __construct(
        private DeckFactoryInterface $deckFactory,
        private GameMediatorInterface $mediator,
        private BotInterface $bot,
        private GameListenerInterface $gameListener,
        private LoggerInterface $logger,
    ) {
    }

    public function execute(int $highestDeckPip, int $tilesPerPlayer, string ...$playersName)
    {
        $deck = $this->deckFactory->createDeck($highestDeckPip);

        $this->mediator->start(
            $this->gameListener,
            $deck,
            ...array_map(
                fn(string $playerName): PlayerInterface => new Player(
                    $playerName,
                    $deck->drawRandomTiles($tilesPerPlayer)
                ),
                $playersName
            ),
        );

        while ($this->mediator->getStatus() == GameMediatorInterface::STATUS_IN_PROGRESS) {
            $this->bot->play($this->mediator);
        }

        if ($winner = $this->mediator->getWinner()) {
            $this->logger->debug(sprintf('%s has won!', $winner->getName()));
        } else {
            $this->logger->debug('It\'s a tie. Nobody wins!');
        }
    }
}