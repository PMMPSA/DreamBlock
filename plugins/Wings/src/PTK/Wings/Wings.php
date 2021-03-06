<?php

namespace PTK\Wings;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\level\Position;
use pocketmine\level\particle\FlameParticle;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\entity\Arrow;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;


class Main extends PluginBase implements Listener{

public function onEnable(){
$this->getServer()->getScheduler()->scheduleRepeatingTask(new Task($this), 20);

$this->map = [];
$handle = fopen($this->getDataFolder()."Hinh", "Hinh");
$lines = explode("\n", rtrim(stream_get_contents($handle)));

$height = count($lines);
foreach($lines as $lineNumber => $line){
  $len = strlen($line);
  for($i = 0; $i < $len; ++$i){
    if($line{$i} === "X"){
      $this->map[] = new Vector2($i, $height - $lineNumber - 1);
    }
  }
}

}




public function Second(){
foreach($this->getServer()->getOnlinePlayers() as $player){
$scale = 0.2;
$particle = new FlameParticle(new Vector3);
$yaw = $player->yaw / 180 * M_PI;
$xFactor = -sin($yaw) * $scale;
$zFactor = cos($yaw) * $scale;
foreach($this->map as $vector){
  $particle->y = $vector->y;
  $particle->x = $xFactor * $vector->x;
  $particle->z = $zFactor * $vector->x;
  var_dump($particle->x);
  $player->getLevel()->addParticle($particle);
  $player->getLevel()->addParticle(new FlameParticle(new Vector3($player->x, $player->y + 2, $player->z)));
}}


}


}
