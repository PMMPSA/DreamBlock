<?php
namespace EnderBoxie\WorldGM;

use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

class WorldGM extends PluginBase{
    
    public $cfg;
    
    public function onEnable(){
        $this->saveResource('config.yml');
        $this->cfg = new Config($this->getDataFolder() . 'config.yml', Config::YAML, array());
        $this->getLogger()->info(TF::GREEN . 'WorldGM is ready!');
        $this->getServer()->getPluginManager()->registerEvents(new WorldGMListener($this), $this);
    }

    public function getGamemodes(){
        return (array) $this->cfg->get('gamemodes');
    }

    public function getGamemode(Level $level){
        $i = $this->getGamemodes();
        return $i[$level->getName()];
    }

    public function getJoinGamemode(){
        return (int) $this->cfg->get('join.gamemode');
    }

    public function getDefaultGamemode(){
        return (int) $this->cfg->get('default.gamemode');
    }

}
