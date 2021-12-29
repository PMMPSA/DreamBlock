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

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class ChangeDimensionPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CHANGE_DIMENSION_PACKET;

	const DIMENSION_NORMAL = 0;
	const DIMENSION_NETHER = 1;
	const DIMENSION_END = 2;

	public $dimension;

	public $x;
	public $y;
	public $z;
	public $unknown; //bool

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putVarInt($this->dimension);
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putBool($this->unknown);
	}

}