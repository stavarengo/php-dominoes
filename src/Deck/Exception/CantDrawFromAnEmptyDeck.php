<?php

declare(strict_types=1);


namespace Dominoes\Deck\Exception;


class CantDrawFromAnEmptyDeck extends \Exception
{

    public static function create()
    {
        return new self('This deck is empty. There are no more tiles to draw.');
    }
}