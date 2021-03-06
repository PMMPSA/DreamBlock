<?php

namespace onebone\itemcloud;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\CallbackTask;

class MainClass extends PluginBase implements Listener{
	/**
	 * @var MainClass
	 */
	private static $instance;

	/**
	 * @var ItemCloud[]
	 */
	private $clouds;

	/**
	 * @return MainClass
	 */
	public static function getInstance(){
		return self::$instance;
	}

	/**
	 * @param Player|string $player
	 *
	 * @return ItemCloud|bool
	 */
	public function getCloudForPlayer($player){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		if(isset($this->clouds[$player])){
			return $this->clouds[$player];
		}
		return false;
	}

	/**************************   Below part is a non-API part   ***********************************/

	public function onEnable(){
		if(!self::$instance instanceof MainClass){
			self::$instance = $this;
		}
		@mkdir($this->getDataFolder());
		if(!is_file($this->getDataFolder()."ItemCloud.dat")){
			file_put_contents($this->getDataFolder()."ItemCloud.dat", serialize([]));
		}
		$data = unserialize(file_get_contents($this->getDataFolder()."ItemCloud.dat"));

		$this->saveDefaultConfig();
		if(is_numeric($interval = $this->getConfig()->get("auto-save-interval"))){
			$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new CallbackTask([$this, "save"], []), $interval * 1200, 1);
		}
		
		$this->clouds = [];
		foreach($data as $datam){
			$this->clouds[$datam[1]] = new ItemCloud($datam[0], $datam[1]);
		}
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $params){
		switch($command->getName()){
			case "itemcloud":
				if(!$sender instanceof Player){
					$sender->sendMessage("Please run this command in-game");
					return true;
				}
				$sub = array_shift($params);
				switch($sub){
					case "register":
						if(isset($this->clouds[strtolower($sender->getName())])){
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] B???n ???? c?? m???t t??i kho???n t??? tr?????c");
							break;
						}
						$this->clouds[strtolower($sender->getName())] = new ItemCloud([], $sender->getName());
						$sender->sendMessage("[Ng??n H??ng V???t Ph???m] B???n ???? ????ng k?? t??i kho???n th??nh c??ng");
						break;
					case "upload":
						if(!isset($this->clouds[strtolower($sender->getName())])){
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] B???n h??y ????ng k?? m???t t??i kho???n tr?????c.");
							break;
						}
						$id = array_shift($params);
						$amount = array_shift($params);
						if(trim($id) === "" or !is_numeric($amount)){
							usage:
							$sender->sendMessage("S??? d???ng l???nh: /itemcloud upload <ID ?????[:th??? t???]> <s??? l?????ng>");
							break;
						}
						$amount = (int) $amount;
						$e = explode(":", $id);
						if(!isset($e[1])){
							$e[1] = 0;
						}
						if(!is_numeric($e[0]) or !is_numeric($e[1])){
							goto usage;
						}

						$count = 0;
						foreach($sender->getInventory()->getContents() as $item){
							if($item->getID() == $e[0] and $item->getDamage() == $e[1]){
								$count += $item->getCount();
							}
						}
						if($amount <= $count){
							$this->clouds[strtolower($sender->getName())]->addItem($e[0], $e[1], $amount, true);
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] ???? upload v???t ph???m l??n t??i kho???n c???a b???n.");
						}else{
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] B???n kh??ng c?? v???t ph???m ????? upload.");
						}
						break;
					case "download":
						$name = strtolower($sender->getName());
						if(!isset($this->clouds[$name])){
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] H??y ????ng k?? t??i kho???n tr?????c.");
							break;
						}
						$id = array_shift($params);
						$amount = array_shift($params);
						if(trim($id) === "" or !is_numeric($amount)){
							usage2:
							$sender->sendMessage("S??? d???ng: /itemcloud download <ID ?????[:th??? t???]> <s??? l?????ng>");
							break;
						}
						$amount = (int)$amount;
						$e = explode(":", $id);
						if(!isset($e[1])){
							$e[1] = 0;
						}
						if(!is_numeric($e[0]) or !is_numeric($e[1])){
							goto usage2;
						}
						
						if(!$this->clouds[$name]->itemExists($e[0], $e[1], $amount)){
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] B???n kh??ng c?? v???t ph???m trong t??i kho???n.");
							break;
						}
						$item = Item::get((int)$e[0], (int)$e[1], $amount);
						if($sender->getInventory()->canAddItem($item)){
							$this->clouds[$name]->removeItem($e[0], $e[1], $amount);
							$sender->getInventory()->addItem($item);
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] B???n ???? download v???t ph???m xu???ng.");
						}else{
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] B???n kh??ng c?? ????? s??? l?????ng v???t ph???m ????? download.");
						}
						break;
					case "list":
						$name = strtolower($sender->getName());
						if(!isset($this->clouds[$name])){
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] H??y ????ng k?? t??i kho???n tr?????c.");
							break;
						}
						$output = "[ItemCloud] Item list : \n";
						foreach($this->clouds[$name]->getItems() as $item => $count){
							$output .= "$item : $count\n";
						}
						$sender->sendMessage($output);
						break;
					case "count":
						$name = strtolower($sender->getName());
						if(!isset($this->clouds[$name])){
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] H??y ????ng k?? t??i kho???n tr?????c.");
							break;
						}
						$id = array_shift($params);
						$e = explode(":", $id);
						if(!isset($e[1])){
							$e[1] = 0;
						}

						if(($count = $this->clouds[$name]->getCount($e[0], $e[1])) === false){
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] Kh??ng c?? ".$e[0].":".$e[1]." trong t??i kho???n c???a b???n.");
							break;
						}else{
							$sender->sendMessage("[Ng??n H??ng V???t Ph???m] S??? l?????ng c???a ".$e[0].":".$e[1]." = ".$count);
						}
						break;
					default:
						$sender->sendMessage("[Ng??n H??ng V???t Ph???m] S??? d???ng: ".$command->getUsage());
				}
				return true;
		}
		return false;
	}

	public function save(){
		$save = [];
		foreach($this->clouds as $cloud){
			$save[] = $cloud->getAll();
		}
		file_put_contents($this->getDataFolder()."ItemCloud.dat", serialize($save));
	}

	public function onDisable(){
		$this->save();
		$this->clouds = [];
	}
}