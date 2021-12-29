<?php

namespace PTKCustomEnchantsShop\Economy;

use PTKCustomEnchantsShop\Main;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

/**
 * Class EconomyAPI
 * @package PTKCustomEnchantsShop\Economy
 */
class EconomyAPI implements BasicEconomy
{
    private $plugin;
    private $economy;

    /**
     * EconomyAPI constructor.
     * @param Main $plugin
     * @param Plugin $economy
     */
    public function __construct(Main $plugin, Plugin $economy)
    {
        $this->plugin = $plugin;
        $this->economy = $economy;
    }

    /**
     * @param Player $player
     * @param int $amount
     * @return mixed
     */
    public function takeMoney(Player $player,  $amount)
    {
        return $this->economy->reduceMoney($player, $amount, true);
    }

    /**
     * @param Player $player
     * @return mixed
     */
    public function getMoney(Player $player)
    {
        return $this->economy->myMoney($player);
    }

    /**
     * @return mixed
     */
    public function getMonetaryUnit()
    {
        return $this->economy->getMonetaryUnit();
    }
}