<?php

namespace PTKCustomEnchants\Tasks;


use PTKCustomEnchants\CustomEnchants\CustomEnchants;
use PTKCustomEnchants\Main;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

/**
 * Class MeditationTask
 * @package PTKCustomEnchants\Tasks
 */
class MeditationTask extends PluginTask
{
    private $plugin;

    /**
     * MeditationTask constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($plugin);
    }

    /**
     * @param int $currentTick
     */
    public function onRun ($currentTick)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $enchantment = $this->plugin->getEnchantment($player->getInventory()->getHelmet(), CustomEnchants::MEDITATION);
            if ($enchantment !== null) {
                if (!isset($this->plugin->meditationTick[$player->getLowerCaseName()])) {
                    $this->plugin->meditationTick[$player->getLowerCaseName()] = 0;
                }
                $this->plugin->meditationTick[$player->getLowerCaseName()]++;
                $time = $this->plugin->meditationTick[$player->getLowerCaseName()] / 40;
                $player->sendTip(TextFormat::DARK_GREEN . "Meditating...\n " . TextFormat::GREEN . str_repeat("▌", $time));
                if ($this->plugin->meditationTick[$player->getLowerCaseName()] >= 20 * 20) {
                    $this->plugin->meditationTick[$player->getLowerCaseName()] = 0;
                    $event = new EntityRegainHealthEvent($player, $enchantment->getLevel(), EntityRegainHealthEvent::CAUSE_MAGIC);
                    if (!$event->isCancelled()) {
                        $player->heal($event);
                    }
                    $player->setFood($player->getFood() + $enchantment->getLevel() > 20 ? 20 : $player->getFood() + $enchantment->getLevel());
                }
            }
        }
    }
}