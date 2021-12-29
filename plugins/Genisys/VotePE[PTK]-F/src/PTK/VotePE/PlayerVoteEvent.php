<?php
namespace PTK\VotePE;
use PTK\VotePE\Main as VotePE;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;
class PlayerVoteEvent extends PluginEvent implements Cancellable {
  public static $handlerList = null;
  protected $player;
  public function __construct(VotePE $plugin,Player $player,$data) {
    parent::__construct($plugin);
    $this->player = $player;
  }
}
