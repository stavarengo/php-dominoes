<?php

declare(strict_types=1);


namespace Dominoes\Tile\Exception;


class CantDrawFromAnEmptyCollection extends \Exception
{

    public static function create()
    {
        return new self('This collection is empty. There are no more tiles to draw.');
    }
}