<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\DefaultMediator\State;


use Dominoes\GameMediator\DefaultMediator\DefaultMediator;
use Dominoes\GameMediator\GameMediatorInterface;

abstract class AbstractState implements GameMediatorInterface
{
    public function __construct(protected DefaultMediator $gameMediator)
    {
    }
}