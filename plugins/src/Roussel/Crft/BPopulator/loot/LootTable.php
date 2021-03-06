<?php

namespace Roussel\Crft\BPopulator\loot;

use pocketmine\inventory\BaseInventory;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\utils\Config;
use pocketmine\utils\Random;

/*
 * This class is used for loot setting.
 * Please note that they AREN'T as powerful as PC ones due to some implementations limitations.
 * Loot table format:
 * {
 * "max": Max number of loots (storable amount)
 * "example": {
 * "percentage": Chance of appearing(in percent)
 * "minCount": Minimal count
 * "maxCount": Maximal count
 * "id": Id of the item
 * "data": Item damage
 * "tags": {"display": {"Name": "Example NBT data"}}. This parameter is optional
 * "minStacks": If chosen, the minimum amount of stacks that can be found
 * "maxStacks": If chosen the maximum number of stacks that can be chosen
 * }
 * }
 */
class LootTable {
	const LOOT_NAMES = [
			"igloo",
			"mineshaft" 
	];
	const LOOT_SAVE = [
			"chest",
			"chest"
	];
	const LOOT_IGLOO = 0;
	const LOOT_MINESHAFT = 1;
	
	/**
	 * Asynchronous method to build a loot table
	 *
	 * @param Vector3 $place
	 * @param int $type
	 * @param Random $random
	 * @return void
	 */
	public static function buildLootTable(Vector3 $place, int $type, Random $random) {
		if($place->y < 1) return; // Making sure sometimes that it doesn't write for nothing
		$cfg = new Config(self::getPluginFolder() . "processingLoots.json", Config::JSON);
		$lootsFromJson = json_decode(file_get_contents(self::getResourcesFolder() . "loots/" . self::LOOT_NAMES[$type] . ".json"), true);
		$loots =[];
		foreach($lootsFromJson as $loot) {
			if(is_array($loot) && $random->nextBoundedInt(101) < $loot["percentage"])
				$loots[] = $loot;
		}
		if($lootsFromJson["max"] < count($loots)) {
			while($lootsFromJson["max"] < count($loots))
				unset($loots[array_rand($loots)]);
		}
		$loots["saveAs"] = self::LOOT_SAVE[$type];
		$cfg->set($place->x . ";" . $place->y . ";" . $place->z, $loots);
		$cfg->save();
	}
	
	/**
	 * Synchronous inventory filling method
	 *
	 * @param BaseInventory $inv
	 * @param Vector3 $pos
	 * @return void
	 */
	public static function fillChest(BaseInventory $inv, Vector3 $pos) {
		$cfg = new Config(self::getPluginFolder() . "processingLoots.json", Config::JSON);	
		if($cfg->exists($pos->x . ";" . $pos->y . ";" . $pos->z)) {
			$loots = $cfg->get($pos->x . ";" . $pos->y . ";" . $pos->z);
			$items = [];
			foreach($loots as $loot) {
				if(!is_array($loot)) continue;
				$randCount = rand($loot["minStacks"], $loot["maxStacks"]);
				for($i = 0; $i <= $randCount; $i++) {
					$rand = rand(0, count($loots));
					$items[$rand] = Item::get($loot["id"], $loot["data"], rand($loot["minCount"], $loot["maxCount"]));
					if(isset($loot["tags"])) $items[$rand]->setCompoundTag(NBT::parseJSON($loot["tags"]));
				}
			}
			$inv->setContents($items);
			$cfg->remove($pos->x . ";" . $pos->y . ";" . $pos->z);
			$cfg->save();
		}
	}
	
	/**
	 * Returns the plugins folder.
	 * @return string
	 */
	public static function getPluginFolder(): string {
		$dir = explode(DIRECTORY_SEPARATOR, __DIR__);
		$c = count($dir);
		unset($dir[$c - 1], $dir[$c - 2], $dir[$c - 3], $dir[$c - 4], $dir[$c - 5]);
		return str_ireplace("phar://", "", implode(DIRECTORY_SEPARATOR, $dir)) . DIRECTORY_SEPARATOR . "BetterGen" . DIRECTORY_SEPARATOR;
	}
	
	/**
	 * Returns the resources folder.
	 * @return string
	 */
	public static function getResourcesFolder(): string {
		$dir = explode(DIRECTORY_SEPARATOR, __DIR__);
		$c = count($dir);
		unset($dir[$c - 1], $dir[$c - 2], $dir[$c - 3], $dir[$c - 4]);
		return str_ireplace("phar://", "", implode(DIRECTORY_SEPARATOR, $dir)) . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR;
	}
}
