<?php

namespace emanuele0204\PlayerSize;

use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender};
use pocketmine\entity\Entity;
use pocketmine\{Server, Player};

class PlayerSize extends PluginBase{
    
    public $b = array();
    public function onEnable(){
        $this->getLogger()->info("§8» §ePlayerSize attivo");
        $this->getServer()->getCommandMap()->register("size", new pSize($this));
    }
    
    public function respawn(PlayerRespawnEvent $e){
        $o = $e->getPlayer();
        if(!empty($this->b[$o->getName()])){
            $nomep = $this->b[$o->getName()];
            $o->setDataProperty(Entity::DATA_SCALE, Entity::DATA_TYPE_FLOAT, $nomep);
        }
    }
}

class pSize extends Command{
    
    private $p;
    public function __construct($plugin){
        $this->p = $plugin;
        parent::__construct("size", "PlayerSize by emanuele0204");
    }
    
    public function execute(CommandSender $g, $label, array $args){
        if($g->hasPermission("playersize.size")){
            if(isset($args[0])){
                if(is_numeric($args[0])){
                  if ($args[0] >= 0 && $args[0] <= 20) {
                    $this->p->b[$g->getName()] = $args[0];
                    $g->setDataProperty(Entity::DATA_SCALE, Entity::DATA_TYPE_FLOAT, $args[0]);
                    $g->sendMessage("§8» §fSize cambiata in §e".$args[0]." §f!");
                }elseif($args[0] == "reset"){
                    if(!empty($this->p->b[$g->getName()])){
                        unset($this->p->b[$g->getName()]);
                        $g->setDataProperty(Entity::DATA_SCALE, Entity::DATA_TYPE_FLOAT, 1.0);
                        $g->sendMessage("§8» §aGrandezza ritornata normale!");
                    }else{
                        $g->sendMessage("§8» §cUsa §f/size §ereset §coppure §f/size §e<size>");
                    }
                }else{
                    $g->sendMessage("§8» §eLa grandezza deve essere tra f0 §ee §f20 ");
               }
            }
         }
      }
   }
}
