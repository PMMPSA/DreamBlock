<?php

namespace PTKCustomEnchantsShop;

use PTKCustomEnchantsShop\Economy\BasicEconomy;
use PTKCustomEnchantsShop\Economy\EconomyAPI;
use PTKCustomEnchantsShop\Provider\YAMLProvider;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

/**
 * Class Main
 * @package PTKCustomEnchantsShop
 */
class Main extends PluginBase
{
    public $ce;
    public $economy;
    public $provider;

    private $economymanager;

    public function onEnable()
    {
        if ($this->checkDependents()) {
            $this->saveDefaultConfig();
            switch ($this->economy->getName()) {
                case "EconomyAPI":
                    $this->economymanager = new EconomyAPI($this, $this->economy);
                    break;
            }
            switch ($this->getConfig()->getNested("provider")) {
                case "yml":
                case "yaml":
                default:
                    $this->provider = new YAMLProvider($this);
                    break;
            }
            $this->provider->initShops();
            $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
            $this->getLogger()->info(TextFormat::GREEN . "Đã Hoạt Động.");
        }
    }

    /**
     * @return bool
     */
    public function checkDependents()
    {
        $this->ce = $this->getServer()->getPluginManager()->getPlugin("PTKCustomEnchants");
        if (is_null($this->ce)) {
            $this->getLogger()->critical("Không Tìm Thấy PTKCustomEnchants.");
            $this->getPluginLoader()->disablePlugin($this);
            return false;
        }
        $this->economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if (is_null($this->economy)) {
            $this->getLogger()->critical("Không Tìm Thấy EconomyAPI.");
            $this->getPluginLoader()->disablePlugin($this);
            return false;
        }
        return true;
    }

    /**
     * @return Plugin
     */
    public function getEconomy()
    {
        return $this->economy;
    }

    /**
     * @return BasicEconomy
     */
    public function getEconomyManager()
    {
        return $this->economymanager;
    }

    /**
     * @return YAMLProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }

}