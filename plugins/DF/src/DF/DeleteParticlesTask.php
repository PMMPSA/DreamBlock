<?php

namespace DF;

use pocketmine\scheduler\PluginTask;
use pocketmine\level\Level;

class DeleteParticlesTask extends PluginTask {
	public $owner, $particle, $level;
	public function __construct(DF $owner, $particle, Level $level) {
		$this->owner = $owner;
		$this->particle = $particle;
		$this->level = $level;
	}
	public function onRun($currentTick) {
		$this->owner->deleteParticles ( $this->particle, $this->level );
	}
}

?>