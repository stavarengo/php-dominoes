<?php

declare(strict_types=1);


namespace Dominoes\Deck\Exception;


class TheHigherPipMustBeZeroOrHigher extends \InvalidArgumentException
{

    public static function create(int $higherPip): self
    {
        return new self(sprintf('The higher pip number must be zero or higher. You passed "%s".', $higherPip));
    }
}