<?php

declare(strict_types=1);

return [
    'container_definitions' => [
        \Console\Application::class => \DI\factory(\Console\ApplicationFactory::class),

        \Dominoes\Deck\DeckFactoryInterface::class => \DI\autowire(\Dominoes\Deck\DeckFactory::class),
        \Dominoes\GameMediator\GameMediatorInterface::class => \DI\factory(
            \Dominoes\GameMediator\DefaultMediator\DefaultMediatorFactory::class
        ),
        \Dominoes\RoundManager\RoundManagerInterface::class => \DI\autowire(\Dominoes\RoundManager\RoundManager::class),
        \Dominoes\LineOfPlay\LineOfPlayInterface::class => \DI\autowire(\Dominoes\LineOfPlay\LineOfPlay::class),
        \Dominoes\Bot\BotInterface::class => \DI\autowire(\Dominoes\Bot\SimpleBot::class),
    ],
];
