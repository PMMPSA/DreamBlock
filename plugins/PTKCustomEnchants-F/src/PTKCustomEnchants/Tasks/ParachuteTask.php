<?php

namespace PTKCustomEnchants\Tasks;


use PTKCustomEnchants\CustomEnchants\CustomEnchants;
use PTKCustomEnchants\Main;
use pocketmine\math\Vector3;
use pocketmine\scheduler\PluginTask;

/**
 * Class ParachuteTask
 * @package PTKCustomEnchants\Tasks
 */
class ParachuteTask extends PluginTask
{
    private $plugin;

    /**
     * ParachuteTask constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    /**
     * @param int $currentTick
     */
    public function onRun ($currentTick)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $chestplate = $player->getInventory()->getChestplate();
            $enchantment = $this->plugin->getEnchantment($chestplate, CustomEnchants::PARACHUTE);
            if ($enchantment !== null) {
                $motion = $player->getMotion();
                if ($this->plugin->checkBlocks($player, 0, 3)) {
                    $player->setMotion(new Vector3(0, $motion->y * 0.75, 0));
                    $player->resetFallDistance();
                }
            }
        }
    }
}