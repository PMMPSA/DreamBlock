<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\block;


use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\sound\FizzSound;
use pocketmine\math\Vector3;

abstract class Liquid extends Transparent {

	/** @var Vector3 */
	private $temporalVector = null;

	/**
	 * @return bool
	 */
	public function hasEntityCollision(){
		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function isBreakable(Item $item){
		return false;
	}

	/**
	 * @return bool
	 */
	public function canBeReplaced(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function isSolid(){
		return false;
	}

	public $adjacentSources = 0;
	public $isOptimalFlowDirection = [0, 0, 0, 0];
	public $flowCost = [0, 0, 0, 0];

	/**
	 * @return float|int
	 */
	public function getFluidHeightPercent(){
		$d = $this->meta;
		if($d >= 8){
			$d = 0;
		}

		return ($d + 1) / 9;
	}

	/**
	 * @param Vector3 $pos
	 *
	 * @return int
	 */
	protected function getFlowDecay(Vector3 $pos){
		if(!($pos instanceof Block)){
			$pos = $this->getLevel()->getBlock($pos);
		}

		if($pos->getId() !== $this->getId()){
			return -1;
		}else{
			return $pos->getDamage();
		}
	}

	/**
	 * @param Vector3 $pos
	 *
	 * @return int
	 */
	protected function getEffectiveFlowDecay(Vector3 $pos){
		if(!($pos instanceof Block)){
			$pos = $this->getLevel()->getBlock($pos);
		}

		if($pos->getId() !== $this->getId()){
			return -1;
		}

		$decay = $pos->getDamage();

		if($decay >= 8){
			$decay = 0;
		}

		return $decay;
	}

	/**
	 * @return Vector3
	 */
	public function getFlowVector(){
		$vector = new Vector3(0, 0, 0);

		if($this->temporalVector === null){
			$this->temporalVector = new Vector3(0, 0, 0);
		}

		$decay = $this->getEffectiveFlowDecay($this);

		for($j = 0; $j < 4; ++$j){

			$x = $this->x;
			$y = $this->y;
			$z = $this->z;

			if($j === 0){
				--$x;
			}elseif($j === 1){
				++$x;
			}elseif($j === 2){
				--$z;
			}elseif($j === 3){
				++$z;
			}
			$sideBlock = $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y, $z));
			$blockDecay = $this->getEffectiveFlowDecay($sideBlock);

			if($blockDecay < 0){
				if(!$sideBlock->canBeFlowedInto()){
					continue;
				}

				$blockDecay = $this->getEffectiveFlowDecay($this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y - 1, $z)));

				if($blockDecay >= 0){
					$realDecay = $blockDecay - ($decay - 8);
					$vector->x += ($sideBlock->x - $this->x) * $realDecay;
					$vector->y += ($sideBlock->y - $this->y) * $realDecay;
					$vector->z += ($sideBlock->z - $this->z) * $realDecay;
				}

				continue;
			}else{
				$realDecay = $blockDecay - $decay;
				$vector->x += ($sideBlock->x - $this->x) * $realDecay;
				$vector->y += ($sideBlock->y - $this->y) * $realDecay;
				$vector->z += ($sideBlock->z - $this->z) * $realDecay;
			}
		}

		if($this->getDamage() >= 8){
			$falling = false;

			if(!$this->getLevel()->getBlock($this->temporalVector->setComponents($this->x, $this->y, $this->z - 1))->canBeFlowedInto()){
				$falling = true;
			}elseif(!$this->getLevel()->getBlock($this->temporalVector->setComponents($this->x, $this->y, $this->z + 1))->canBeFlowedInto()){
				$falling = true;
			}elseif(!$this->getLevel()->getBlock($this->temporalVector->setComponents($this->x - 1, $this->y, $this->z))->canBeFlowedInto()){
				$falling = true;
			}elseif(!$this->getLevel()->getBlock($this->temporalVector->setComponents($this->x + 1, $this->y, $this->z))->canBeFlowedInto()){
				$falling = true;
			}elseif(!$this->getLevel()->getBlock($this->temporalVector->setComponents($this->x, $this->y + 1, $this->z - 1))->canBeFlowedInto()){
				$falling = true;
			}elseif(!$this->getLevel()->getBlock($this->temporalVector->setComponents($this->x, $this->y + 1, $this->z + 1))->canBeFlowedInto()){
				$falling = true;
			}elseif(!$this->getLevel()->getBlock($this->temporalVector->setComponents($this->x - 1, $this->y + 1, $this->z))->canBeFlowedInto()){
				$falling = true;
			}elseif(!$this->getLevel()->getBlock($this->temporalVector->setComponents($this->x + 1, $this->y + 1, $this->z))->canBeFlowedInto()){
				$falling = true;
			}

			if($falling){
				$vector = $vector->normalize()->add(0, -6, 0);
			}
		}

		return $vector->normalize();
	}

	/**
	 * @param Entity  $entity
	 * @param Vector3 $vector
	 */
	public function addVelocityToEntity(Entity $entity, Vector3 $vector){
		$flow = $this->getFlowVector();
		$vector->x += $flow->x;
		$vector->y += $flow->y;
		$vector->z += $flow->z;
	}

	/**
	 * @return int
	 */
	public function tickRate() : int{
		if($this instanceof Water){
			return 5;
		}elseif($this instanceof Lava){
			return 30;
		}

		return 0;
	}

	/**
	 * @param int $type
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$this->checkForHarden();
			$this->getLevel()->scheduleUpdate($this, $this->tickRate());
		}elseif($type === Level::BLOCK_UPDATE_SCHEDULED){
			if($this->temporalVector === null){
				$this->temporalVector = new Vector3(0, 0, 0);
			}

			$decay = $this->getFlowDecay($this);
			$multiplier = $this instanceof Lava ? 2 : 1;

			$flag = true;

			if($decay > 0){
				$smallestFlowDecay = -100;
				$this->adjacentSources = 0;
				$smallestFlowDecay = $this->getSmallestFlowDecay($this->level->getBlock($this->temporalVector->setComponents($this->x, $this->y, $this->z - 1)), $smallestFlowDecay);
				$smallestFlowDecay = $this->getSmallestFlowDecay($this->level->getBlock($this->temporalVector->setComponents($this->x, $this->y, $this->z + 1)), $smallestFlowDecay);
				$smallestFlowDecay = $this->getSmallestFlowDecay($this->level->getBlock($this->temporalVector->setComponents($this->x - 1, $this->y, $this->z)), $smallestFlowDecay);
				$smallestFlowDecay = $this->getSmallestFlowDecay($this->level->getBlock($this->temporalVector->setComponents($this->x + 1, $this->y, $this->z)), $smallestFlowDecay);

				$k = $smallestFlowDecay + $multiplier;

				if($k >= 8 or $smallestFlowDecay < 0){
					$k = -1;
				}

				if(($topFlowDecay = $this->getFlowDecay($this->level->getBlock($this->level->getBlock($this->temporalVector->setComponents($this->x, $this->y + 1, $this->z))))) >= 0){
					if($topFlowDecay >= 8){
						$k = $topFlowDecay;
					}else{
						$k = $topFlowDecay | 0x08;
					}
				}

				if($this->adjacentSources >= 2 and $this instanceof Water){
					$bottomBlock = $this->level->getBlock($this->level->getBlock($this->temporalVector->setComponents($this->x, $this->y - 1, $this->z)));
					if($bottomBlock->isSolid()){
						$k = 0;
					}elseif($bottomBlock instanceof Water and $bottomBlock->getDamage() === 0){
						$k = 0;
					}
				}

				if($this instanceof Lava and $decay < 8 and $k < 8 and $k > 1 and mt_rand(0, 4) !== 0){
					$k = $decay;
					$flag = false;
				}

				if($k !== $decay){
					$decay = $k;
					if($decay < 0){
						$this->getLevel()->setBlock($this, new Air(), true);
					}else{
						$this->getLevel()->setBlock($this, Block::get($this->id, $decay), true);
						$this->getLevel()->scheduleUpdate($this, $this->tickRate());
					}
				}elseif($flag){
					//$this->getLevel()->scheduleUpdate($this, $this->tickRate());
					//$this->updateFlow();
				}
			}else{
				//$this->updateFlow();
			}

			$bottomBlock = $this->level->getBlock($this->temporalVector->setComponents($this->x, $this->y - 1, $this->z));

			if($bottomBlock->canBeFlowedInto() or $bottomBlock instanceof Liquid){
				if($this instanceof Lava and $bottomBlock instanceof Water){
					$this->getLevel()->setBlock($bottomBlock, Block::get(Item::STONE), true);
					$this->triggerLavaMixEffects($bottomBlock);
					return;
				}

				if($decay >= 8){
					//$this->getLevel()->setBlock($bottomBlock, Block::get($this->id, $decay), true);
					//$this->getLevel()->scheduleUpdate($bottomBlock, $this->tickRate());
					$this->flowIntoBlock($bottomBlock, $decay);
				}else{
					//$this->getLevel()->setBlock($bottomBlock, Block::get($this->id, $decay + 8), true);
					//$this->getLevel()->scheduleUpdate($bottomBlock, $this->tickRate());
					$this->flowIntoBlock($bottomBlock, $decay | 0x08);
				}
			}elseif($decay >= 0 and ($decay === 0 or !$bottomBlock->canBeFlowedInto())){
				$flags = $this->getOptimalFlowDirections();

				$l = $decay + $multiplier;

				if($decay >= 8){
					$l = 1;
				}

				if($l >= 8){
					$this->checkForHarden();
					return;
				}

				if($flags[0]){
					$this->flowIntoBlock($this->level->getBlock($this->temporalVector->setComponents($this->x - 1, $this->y, $this->z)), $l);
				}

				if($flags[1]){
					$this->flowIntoBlock($this->level->getBlock($this->temporalVector->setComponents($this->x + 1, $this->y, $this->z)), $l);
				}

				if($flags[2]){
					$this->flowIntoBlock($this->level->getBlock($this->temporalVector->setComponents($this->x, $this->y, $this->z - 1)), $l);
				}

				if($flags[3]){
					$this->flowIntoBlock($this->level->getBlock($this->temporalVector->setComponents($this->x, $this->y, $this->z + 1)), $l);
				}
			}

			$this->checkForHarden();

		}
	}

	/**
	 * @param Block $block
	 * @param       $newFlowDecay
	 */
	private function flowIntoBlock(Block $block, $newFlowDecay){
		if($block->canBeFlowedInto()){
			if($block instanceof Lava){
				$this->triggerLavaMixEffects($block);
			}elseif($block->getId() > 0){
				$this->getLevel()->useBreakOn($block);
			}

			$this->getLevel()->setBlock($block, Block::get($this->getId(), $newFlowDecay), true);
			$this->getLevel()->scheduleUpdate($block, $this->tickRate());
		}
	}

	/**
	 * @param Block $block
	 * @param       $accumulatedCost
	 * @param       $previousDirection
	 *
	 * @return int
	 */
	private function calculateFlowCost(Block $block, $accumulatedCost, $previousDirection){
		$cost = 1000;

		for($j = 0; $j < 4; ++$j){
			if(
				($j === 0 and $previousDirection === 1) or
				($j === 1 and $previousDirection === 0) or
				($j === 2 and $previousDirection === 3) or
				($j === 3 and $previousDirection === 2)
			){
				$x = $block->x;
				$y = $block->y;
				$z = $block->z;

				if($j === 0){
					--$x;
				}elseif($j === 1){
					++$x;
				}elseif($j === 2){
					--$z;
				}elseif($j === 3){
					++$z;
				}
				$blockSide = $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y, $z));

				if(!$blockSide->canBeFlowedInto() and !($blockSide instanceof Liquid)){
					continue;
				}elseif($blockSide instanceof Liquid and $blockSide->getDamage() === 0){
					continue;
				}elseif($this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y - 1, $z))->canBeFlowedInto()){
					return $accumulatedCost;
				}

				if($accumulatedCost >= 4){
					continue;
				}

				$realCost = $this->calculateFlowCost($blockSide, $accumulatedCost + 1, $j);

				if($realCost < $cost){
					$cost = $realCost;
				}
			}
		}

		return $cost;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 100;
	}

	/**
	 * @return array
	 */
	private function getOptimalFlowDirections(){
		if($this->temporalVector === null){
			$this->temporalVector = new Vector3(0, 0, 0);
		}

		for($j = 0; $j < 4; ++$j){
			$this->flowCost[$j] = 1000;

			$x = $this->x;
			$y = $this->y;
			$z = $this->z;

			if($j === 0){
				--$x;
			}elseif($j === 1){
				++$x;
			}elseif($j === 2){
				--$z;
			}elseif($j === 3){
				++$z;
			}
			$block = $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y, $z));

			if(!$block->canBeFlowedInto() and !($block instanceof Liquid)){
				continue;
			}elseif($block instanceof Liquid and $block->getDamage() === 0){
				continue;
			}elseif($this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y - 1, $z))->canBeFlowedInto()){
				$this->flowCost[$j] = 0;
			}else{
				$this->flowCost[$j] = $this->calculateFlowCost($block, 1, $j);
			}
		}

		$minCost = $this->flowCost[0];

		for($i = 1; $i < 4; ++$i){
			if($this->flowCost[$i] < $minCost){
				$minCost = $this->flowCost[$i];
			}
		}

		for($i = 0; $i < 4; ++$i){
			$this->isOptimalFlowDirection[$i] = ($this->flowCost[$i] === $minCost);
		}

		return $this->isOptimalFlowDirection;
	}

	/**
	 * @param Vector3 $pos
	 * @param         $decay
	 *
	 * @return int
	 */
	private function getSmallestFlowDecay(Vector3 $pos, $decay){
		$blockDecay = $this->getFlowDecay($pos);

		if($blockDecay < 0){
			return $decay;
		}elseif($blockDecay === 0){
			++$this->adjacentSources;
		}elseif($blockDecay >= 8){
			$blockDecay = 0;
		}

		return ($decay >= 0 && $blockDecay >= $decay) ? $decay : $blockDecay;
	}

	private function checkForHarden(){
       if($this instanceof Water){
           $colliding = false;
           for($side = 0; $side <= 5 and !$colliding; ++$side){
               $colliding = $this->getSide($side) instanceof Fence;
           }
           if($colliding){
           	if($this->getDamage() === 0){
               }elseif($this->getDamage() <= 4){
                   //random spawn ore
                   $orerand = mt_rand(1, 100);
				   /*if(($orerand >=1 and $orerand <=4) or ($orerand >=21 and $orerand <=24) or( $orerand >=91 and $orerand <=94)or ($orerand >=81 and $orerand <=84) or( $orerand >=21 and $orerand <=24) or ($orerand >=96 and $orerand) <=9 or ($orerand >=41 and $orerand <=44) or ($orerand >=46 and $orerand <=49)){$this->getLevel()->setBlock($this, Block::get(Item::STONE), true);
				   }elseif($orerand =5 or $orerand =95 or $orerand =85 or $orerand =75 or $orerand =65 or $orerand =55 or $orerand =45 or $orerand =15 or $orerand =35 or $orerand =25){$this->getLevel()->setBlock($this, Block::get(Item::EMERALD_ORE), true);
				   }elseif($orerand =20 or $orerand =100 or $orerand =90 or $orerand =80 or $orerand =70 or $orerand =50 or $orerand =60 or $orerand =30 or $orerand =40){$this->getLevel()->setBlock($this, Block::get(Item::DIAMOND_ORE), true);
				   }elseif($orerand >=26 and $orerand <=29){$this->getLevel()->setBlock($this, Block::get(Item::OBSIDIAN), true);
				   }elseif($orerand >=31 and $orerand <=34){$this->getLevel()->setBlock($this, Block::get(Item::LAPIS_ORE), true);
				   }elseif($orerand >=26 and $orerand <=29){$this->getLevel()->setBlock($this, Block::get(Item::OBSIDIAN), true);
				   }elseif($orerand >=16 and $orerand <=19){$this->getLevel()->setBlock($this, Block::get(Item::NETHER_QUARTZ_ORE), true);
				   }elseif($orerand >=71 and $orerand <=74){$this->getLevel()->setBlock($this, Block::get(Item::REDSTONE_ORE), true);
				   }elseif(($orerand >=11 and $orerand <=14)  or ($orerand >=36 and $orerand <=39) or ($orerand >=51 and $orerand <=54)){$this->getLevel()->setBlock($this, Block::get(Item::GOLD_BLOCK), true); 
				   }elseif(($orerand >=61 and $orerand <=64) or ($orerand >=56 and $orerand <=59) or ($orerand >=76 and $orerand <=79))
				   {$this->getLevel()->setBlock($this, Block::get(Item::COAL_ORE), true);
				   }elseif(($orerand >=6 and $orerand <=9) or ($orerand >=86 and $orerand <=89))
				   {$this->getLevel()->setBlock($this, Block::get(Item::IRON_ORE), true);
				}*/
				if($orerand <=5){
                       $this->getLevel()->setBlock($this, Block::get(Item::DIAMOND_BLOCK), true);
                   }elseif($orerand <=6){
                       $this->getLevel()->setBlock($this, Block::get(Item::EMERALD_BLOCK), true);
                   }elseif($orerand <=10){
                       $this->getLevel()->setBlock($this, Block::get(Item::LAPIS_BLOCK), true);
				   }elseif($orerand <=15){
                       $this->getLevel()->setBlock($this, Block::get(Item::REDSTONE_BLOCK), true);
                   }elseif($orerand <=25){
                       $this->getLevel()->setBlock($this, Block::get(Item::IRON_BLOCK), true);
                   }elseif($orerand <=28){
                       $this->getLevel()->setBlock($this, Block::get(Item::COAL_BLOCK), true);
                   }elseif($orerand >=50){
                       $this->getLevel()->setBlock($this, Block::get(Item::STONE), true);
                   }elseif($orerand >=40){
                       $this->getLevel()->setBlock($this, Block::get(Item::GOLD_BLOCK), true);
                       }
			}
		}
	   }
	}
	/**
	 * @return null
	 */
	public function getBoundingBox(){
		return null;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [];
	}

	/**
	 * Creates fizzing sound and smoke. Used when lava flows over block or mixes with water.
	 *
	 * @param Vector3 $pos
	 */
	protected function triggerLavaMixEffects(Vector3 $pos){
		$this->getLevel()->addSound(new FizzSound($pos->add(0.5, 0.5, 0.5), 2.5 + mt_rand(0, 1000) / 1000 * 0.8));

		for($i = 0; $i < 8; ++$i){
			$this->getLevel()->addParticle(new SmokeParticle($pos->add(mt_rand(0, 80) / 100, 0.5, mt_rand(0, 80) / 100)));
		}
	}
}