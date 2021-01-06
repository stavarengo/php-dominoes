<?php

declare(strict_types=1);


namespace Dominoes\GameMediator\Exception;


use Dominoes\Player\PlayerInterface;
use Dominoes\Tile\TileInterface;

class ThisPlayerDoesntHaveThisTile extends GameException
{
    public static function create(PlayerInterface $player, TileInterface $tile): self
    {
        return new self(sprintf('The player "%s" doesn\'t have the tile "%s".', $player->getName(), $tile));
    }
}