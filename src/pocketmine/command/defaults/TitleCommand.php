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
 * @actualauthor TesseractTeam, not cool to steal code and call it your own...
 *
*/

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;

class TitleCommand extends VanillaCommand {

	/**
	 * TitleCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.title.description",
			"%pocketmine.command.title.usage"
		);
		$this->setPermission("pocketmine.command.title");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $currentAlias
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $currentAlias, array $args){
		if($sender instanceof Player){
			if(!$this->testPermission($sender)){
				return true;
			}
			if(count($args) <= 0){
				$sender->sendMessage(new TranslationContainer("%pocketmine.command.title.usage"));
				return false;
			}
		}
	}
}
