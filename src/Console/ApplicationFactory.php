<?php

declare(strict_types=1);


namespace Console;


use Dominoes\Bot\BotInterface;
use Dominoes\Deck\DeckFactoryInterface;
use Dominoes\GameMediator\GameMediatorInterface;

class ApplicationFactory
{
    public function __invoke(
        DeckFactoryInterface $deckFactory,
        GameMediatorInterface $mediator,
        BotInterface $bot,
        StdOutLogger $logger
    ) {
        return new Application($deckFactory, $mediator, $bot, new GameListener($logger), $logger);
    }
}