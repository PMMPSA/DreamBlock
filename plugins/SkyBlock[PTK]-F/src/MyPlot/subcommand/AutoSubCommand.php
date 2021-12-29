<?php
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class AutoSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.auto");
    }

    public function execute(CommandSender $sender, array $args) {
        if (!empty($args)) {
            return false;
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $levelName = $player->getLevel()->getName();
        if (!$this->getPlugin()->isLevelLoaded($levelName)) {
           // $sender->sendMessage(TextFormat::RED . $this->translateString("auto.notplotworld"));
            $levelName = "sb";
        }
        if (($plot = $this->getPlugin()->getProvider()->getNextFreePlot($levelName)) !== null) {
            $this->getPlugin()->teleportPlayerToPlot($player, $plot);
            $sender->sendMessage($this->translateString("auto.success", [$plot->X, $plot->Z]));
			$sender->sendMessage(TextFormat::GREEN . "§b[§bDream§aBlock§b-§aSkyBlock§b]§a Xin hãy sử dụng §e/sb claim §ađể sở hữu đảo");
        } else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("auto.noplots"));
        }
        return true;
        }
    }