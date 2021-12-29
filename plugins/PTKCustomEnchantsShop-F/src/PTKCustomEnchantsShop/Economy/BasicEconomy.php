<?php

namespace PTKCustomEnchantsShop\Economy;

use pocketmine\Player;

/**
 * Class BasicEconomy
 * @package PTKCustomEnchantsShop\Economy
 */
interface BasicEconomy
{
    /**
     * @param Player $player
     * @param int $amount
     * @return mixed
     */
    public function takeMoney(Player $player,  $amount);

    /**
     * @param Player $player
     * @return mixed
     */
    public function getMoney(Player $player);

    public function getMonetaryUnit();

}