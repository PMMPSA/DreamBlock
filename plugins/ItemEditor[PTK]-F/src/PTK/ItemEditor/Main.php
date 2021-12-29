<?php


namespace PTK\ItemEditor;
use pocketmine\event\player\{PlayerInteractEvent, PlayerJoinEvent};
use pocketmine\utils\TextFormat as TF;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\level\sound\ExpPickupSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class Main extends PluginBase implements Listener{

  public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
    if(!$sender instanceof Player) return;
    switch(strtolower($cmd->getName())){
      case "iname":
       if($sender->hasPermission("itemeditor.iname")){
        $name = implode(" ",$args);
          $item = $sender->getInventory()->getItemInHand();
          $sender->sendMessage("§b•»§a Vật Phẩm Trên Tay Bạn Đã Được Đổi Tên Thành" . $name . "§r§a Thành Công!");
          $item->setCustomName($name);
          $sender->getInventory()->setItemInHand($item);
          $sender->getLevel()->addSound(new EndermanTeleportSound($sender), [$sender]);
  }else{
      $sender->sendMessage("§cDu hast keine Erlaubnis um dein Item umzubennen!");
  }
  break;
      case "addlore":
       if($sender->hasPermission("itemeditor.addlore")){
          $item = $sender->getInventory()->getItemInHand();
		  $meta = $item->getItemMeta();
	      $newlore = array(implode(" ",$args));
		  $oldlore = $item->getLore();
		  $meta->setLore($oldlore, $newlore);
		  $item->setItemMeta($meta);
          $sender->getInventory()->setItemInHand($item);
          $sender->getLevel()->addSound(new EndermanTeleportSound($sender), [$sender]);
    
  }else{
      $sender->sendMessage("§cDu hast keine Erlaubnis um dein Item umzubennen!");
  }
  
  break;
}}}