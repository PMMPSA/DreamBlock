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
use pocketmine\math\Vector3;
use pocketmine\Player;

class EndRod extends Flowable {

	protected $id = self::END_ROD;

	/**
	 * EndRod constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 14;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "End Rod";
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 0;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 0;
	}

	/**
	 * @param Item        $item
	 * @param Block       $block
	 * @param Block       $target
	 * @param int         $face
	 * @param float       $fx
	 * @param float       $fy
	 * @param float       $fz
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$faces = [
			0 => 0,
			1 => 1,
			2 => 3,
			3 => 2,
			4 => 5,
			5 => 4,
		];
		$this->meta = ($target->getId() === self::END_ROD && $faces[$face] == $target->getDamage()) ? Vector3::getOppositeSide($faces[$face]) : $faces[$face];
		$this->getLevel()->setBlock($block, $this, true, true);
		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[$this->id, 0, 1],
		];
	}

}
