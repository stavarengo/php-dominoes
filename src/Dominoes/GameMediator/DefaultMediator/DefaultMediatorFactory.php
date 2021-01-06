<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\DefaultMediator;


use Dominoes\LineOfPlay\LineOfPlayInterface;
use Dominoes\RoundManager\RoundManagerInterface;

class DefaultMediatorFactory
{
    public function __invoke(RoundManagerInterface $roundManager, LineOfPlayInterface $lineOfPlay)
    {
        return new DefaultMediator($roundManager, $lineOfPlay);
    }
}