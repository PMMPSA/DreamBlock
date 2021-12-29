<?php 
namespace DF;
${"\x47L\x4f\x42A\x4cS"}["\x6e\x6f\x67c\x6d\x6ea"]="pa\x72\x74\x69\x63le";
${"G\x4c\x4f\x42A\x4cS"}["\x74p\x6egd\x62o"]="\x65\x76\x65\x6e\x74";
${"\x47LO\x42\x41LS"}["o\x76\x66vg\x77\x67\x76\x63\x6ef"]="\x64\x61m\x61\x67e\x50\x61r\x74i\x63\x6c\x65";
${"GL\x4fB\x41L\x53"}["\x76\x72\x6a\x6a\x73\x71xj\x77\x71"]="po\x73";
${"GL\x4f\x42\x41\x4c\x53"}["swrfd\x6cb\x65\x71m"]="\x63o\x6c\x6fr";
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\entity\Entity;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
class DF extends PluginBase implements Listener
{public function onEnable(){$this->getLogger()->info(": §b§l---------------------§e\x44\x46§b---------------------");
$this->getLogger()->info(": §b§l---------------------§ePTK-KienPham§b---------------------");
$this->getLogger()->info(": §b§l---------------------§eTMK-NetWork---------------------");
$this->getServer()->getPluginManager()->registerEvents($this,$this);
}
public function onDamage(EntityDamageEvent$event){
	${"\x47L\x4f\x42A\x4c\x53"}["e\x69kh\x69v\x78"]="p\x6fs";
	${"\x47\x4cOB\x41\x4c\x53"}["\x79\x70\x6cl\x76\x72"]="\x68\x65\x61l\x74\x68Par\x74\x69\x63\x6c\x65";
	$jdvzbomn="\x64\x61m\x61\x67\x65P\x61r\x74\x69\x63\x6c\x65";
	if(!$event->getEntity()instanceof Entity)
		return;
	if($event->getDamage()<3){$nagescbpjfn="col\x6f\x72";
	${$nagescbpjfn}=TextFormat::GREEN;
	}else if($event->getDamage()<6){
		${"\x47\x4c\x4fB\x41\x4c\x53"}["\x6e\x70\x76b\x74\x6c\x73\x67\x77\x6bi"]="col\x6fr";
		${${"\x47\x4cO\x42\x41\x4cS"}["n\x70\x76\x62t\x6c\x73gw\x6b\x69"]}=TextFormat::YELLOW;
		}else{
			${${"\x47LO\x42\x41\x4c\x53"}["\x73\x77\x72f\x64l\x62\x65\x71\x6d"]}=TextFormat::RED;
			}
			${${"\x47\x4c\x4f\x42\x41LS"}["\x65\x69\x6b\x68\x69\x76x"]}=
			$event->getEntity()->add(0.1*mt_rand(1,9)*mt_rand(-1,1),0.1*mt_rand(5,9),0.1*mt_rand(1,9)*mt_rand(-1,1));
			$jkdmsef="\x63o\x6c\x6f\x72";${$jdvzbomn}=new FloatingTextParticle(${${"GLO\x42\x41\x4cS"}["\x76\x72j\x6a\x73q\x78\x6awq"]},"",${$jkdmsef}."§c-".$event->getDamage()." Máu\n§e".($event->getEntity()->getHealth() - $event->getDamage())."/".$event->getEntity()->getMaxHealth());
			if($event->getEntity()->getHealth()<7){
				$svtsmngws="\x63\x6f\x6c\x6fr";
				${$svtsmngws}=TextFormat::RED;
				}
				else if($event->getEntity()->getHealth()<14){${${"G\x4cO\x42\x41\x4c\x53"}["s\x77\x72\x66\x64\x6cbeqm"]}=TextFormat::YELLOW;}else{${"G\x4cOB\x41\x4c\x53"}["\x62\x71\x63\x70\x76\x79\x75\x73\x75\x6c"]="col\x6fr";${${"\x47\x4c\x4f\x42\x41L\x53"}["bqc\x70vy\x75\x73\x75\x6c"]}=TextFormat::GREEN;}${${"\x47\x4c\x4fB\x41LS"}["v\x72\x6a\x6a\x73\x71xj\x77\x71"]}=$event->getEntity()->add(0,2.5,0);${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x79\x70l\x6cvr"]}=new FloatingTextParticle(${${"\x47\x4cO\x42AL\x53"}["\x76\x72\x6a\x6as\x71\x78j\x77q"]},"",${${"\x47\x4cO\x42\x41\x4cS"}["\x73wr\x66\x64l\x62\x65\x71m"]}.($event->getEntity()->getHealth()-$event->getDamage())."\x20/ ".$event->getEntity()->getMaxHealth());$this->getServer()->getScheduler()->scheduleDelayedTask(new EventCheckTask($this,${${"\x47L\x4f\x42\x41\x4cS"}["\x6fvf\x76\x67\x77\x67\x76cn\x66"]},$event->getEntity()->getLevel(),${${"\x47\x4c\x4f\x42A\x4cS"}["\x74\x70\x6egdb\x6f"]}),1);${"\x47\x4cO\x42\x41L\x53"}["a\x75\x6f\x68j\x78\x67"]="\x68ea\x6c\x74\x68P\x61\x72\x74i\x63\x6ce";$this->getServer()->getScheduler()->scheduleDelayedTask(new EventCheckTask($this,${${"\x47LO\x42A\x4c\x53"}["a\x75\x6f\x68\x6a\x78\x67"]},$event->getEntity()->getLevel(),${${"\x47L\x4f\x42\x41\x4cS"}["\x74\x70\x6e\x67\x64b\x6f"]}),1);}public function eventCheck(FloatingTextParticle$particle,Level$level,$event){${"\x47\x4c\x4f\x42\x41L\x53"}["\x72\x69t\x69\x6al"]="p\x61r\x74\x69c\x6ce";${"\x47\x4c\x4fBA\x4c\x53"}["f\x72sk\x74\x67"]="\x65\x76\x65\x6et";if(${${"\x47LO\x42\x41\x4cS"}["\x66\x72\x73k\x74\x67"]} instanceof EntityDamageEvent){if($event->isCancelled())return;}$level->addParticle(${${"\x47\x4c\x4f\x42\x41\x4cS"}["r\x69t\x69\x6a\x6c"]});$this->getServer()->getScheduler()->scheduleDelayedTask(new DeleteParticlesTask($this,${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x6e\x6f\x67\x63m\x6ea"]},$event->getEntity()->getLevel()),20);}public function deleteParticles(FloatingTextParticle$particle,Level$level){$eipbycfmrd="\x70art\x69\x63\x6ce";$particle->setInvisible();$level->addParticle(${$eipbycfmrd});}}
?>