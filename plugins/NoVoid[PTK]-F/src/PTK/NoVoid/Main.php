<?php

namespace PTK\NoVoid;

    use pocketmine\plugin\PluginBase;
    use pocketmine\command\Command;
    use pocketmine\command\CommandExecutor;
    use pocketmine\command\CommandSender;
    use pocketmine\command\ConsoleCommandSender;
    use pocketmine\command\ConsoleCommandExecutor;
    use pocketmine\event\Listener;
    use pocketmine\level\Position;
    use pocketmine\level\Level;
    use pocketmine\Player;
    use pocketmine\entity\Entity;
    use pocketmine\math\Vector3;
    use pocketmine\utils\Config;
	use pocketmine\event\player\PlayerMoveEvent;

class Main extends PluginBase implements Listener{
    
public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveDefaultConfig();
    $this->getResource("config.yml");
    $this->getLogger()->info("Plugin NoVoid[PTK] Đã Chạy!");

}
    public function onVoidLoop(PlayerMoveEvent $event){
        if($event->getTo()->getFloorY() < 3){
            $SuDungConfig = $this->getConfig()->get("Sử Dụng Config");
            $X = $this->getConfig()->get("X");
            $Y = "71";
            $Z = $this->getConfig()->get("Z");
            $TheGioi = $this->getConfig()->get("Thế Giới");
			$TinNhan =$this->getConfig()->get("Tin Nhắn");
            $player = $event->getPlayer();
            if($SuDungConfig === false){
                $player->teleport($this->getServer()->getDefaultLevel()->getSpawn());
				$player->setHealth($this->getConfig()->get("Máu"));
				$player->sendMessage($TinNhan);
            }else{
                $player->teleport(new Vector3($X, $Y, $Z, $TheGioi));
                $player->setHealth($this->getConfig()->get("Máu"));
				$player->sendMessage($TinNhan);
            }
        }
    }
    
    public function onDisable(){
        $this->getConfig()->save();
        $this->getLogger()->info("Plugin NoVoid[PTK] Đã Tắt!");
    }
}
