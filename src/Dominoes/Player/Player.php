<?php

declare(strict_types=1);


namespace Dominoes\Player;


class Player implements PlayerInterface
{

    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}