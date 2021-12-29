<?php

/*
 *
 *  _______                  _             _          __      ___   _            
 * |__   __|                (_)           | |         \ \    / / \ | |          
 *    | | ___ _ __ _ __ ___  _ _ __   __ _| |_ ___  _ _\ \  / /|  \| |  
 *    | |/ _ \ '__| '_ ` _ \| | '_ \ / _` | __/ _ \| '__\ \/ / | . ` | 
 *    | |  __/ |  | | | | | | | | | | (_| | || (_) | |   \  /  | |\  |
 *    |_|\___|_|  |_| |_| |_|_|_| |_|\__,_|\__\___/|_|    \/   |_| \_| 
 *                    
 *                     
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TerminatorVNTeam
 * @link https://github.com/TerminatorVN/TerminatorVN
 *
 *
*/

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Purpur extends Solid {

	protected $id = self::PURPUR;

	/**
	 * Purpur constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 1.5;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		static $names = [
			0 => "Purpur Block",
			2 => "Purpur Pillar",
		];

		return $names[$this->meta & 0x0f] ?? "Purpur Block"; //TODO fix properly;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= Tool::TIER_WOODEN){

			return [
				[$this->id, $this->meta & 0x0f, 1],
			];

		}else{

			return [];

		}
	}

}