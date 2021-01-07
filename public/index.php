<?php

declare(strict_types=1);

chdir(dirname(__DIR__));

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException(
        'Did you forget to run `composer install`?' . PHP_EOL . 'Unable to load the "./vendor/autoload.php".'
    );
}
require __DIR__ . '/../vendor/autoload.php';

/**
 * I'm using a self-called anonymous function to create its own scope and keep the the variables created here away from
 * the global scope.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = include_once __DIR__ . '/../config/container.php';

    $input = getopt('', long_options: ['highestPip::', 'tilesPerPlayer::', 'players::']);
    $highestDeckPip = (int)($input['highestPip'] ?? 6);
    $tilesPerPlayer = (int)($input['tilesPerPlayer'] ?? 7);
    $players = trim((((string)$input['players']) ?? null));
    if (!$players) {
        $players = 'Alice,Bob';
    }
    $playersName = array_filter(explode(',', $players));


    $app = $container->get(\Console\Application::class);
    $app->execute($highestDeckPip, $tilesPerPlayer, ...$playersName);
})();
