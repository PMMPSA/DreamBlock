<?php

namespace PTKCustomEnchantsShop;

use PTKCustomEnchants\CustomEnchants\CustomEnchants;
use pocketmine\block\SignPost;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class EventListener
 * @package PTKCustomEnchantsShop
 */
class EventListener implements Listener
{
    private $plugin;

    private $tap;

    /**
     * EventListener constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param BlockBreakEvent $event
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if ($block instanceof SignPost) {
            if (!is_null($shop = $this->plugin->getProvider()->getShop($block->x, $block->y, $block->z))) {
                if (!$player->hasPermission("ptkcustomenchantsshop.breaksign")) {
                    $player->sendMessage(TextFormat::RED . "Bạn Không Có Quyền Làm Việc Này.");
                    $event->setCancelled();
                } else {
                    $this->plugin->getProvider()->removeShop($shop);
                }
            }
        }
    }

    /**
     * @param PlayerInteractEvent $event
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if (!is_null($shop = $this->plugin->getProvider()->getShop($block->x, $block->y, $block->z))) {
            if ($player->hasPermission("ptkcustomenchantsshop.usesign")) {
                if ($this->plugin->getEconomyManager()->getMoney($player) >= $shop->getPrice()) {
                    if (!$this->plugin->getConfig()->getNested("double-tap")) {
                        $this->buyItem($player, $shop);
                    } else {
                        if (!isset($this->tap[$player->getLowerCaseName()]) || (isset($this->tap[$player->getLowerCaseName()]) && $this->tap[$player->getLowerCaseName()] <= time())) {
                            $this->tap[$player->getLowerCaseName()] = time() + 10;
                            $player->sendMessage(TextFormat::YELLOW . "Chạm Lần Nữa Để Mua Phù Phép " . $shop->getEnchantment() . " Với " . $shop->getPrice() . $this->plugin->getEconomyManager()->getMonetaryUnit() . ".");
                        } else {
                            $this->buyItem($player, $shop);
                        }
                    }
                } else {
                    $player->sendMessage(TextFormat::RED . "Không Đủ Tiền. Bạn Cần Có " . ($shop->getPrice() - $this->plugin->getEconomyManager()->getMoney($player)) . $this->plugin->getEconomyManager()->getMonetaryUnit() . " Nữa.");
                }
            }
        }
    }

    /**
     * @param SignChangeEvent $event
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     * @return bool
     */
    public function onSignChange(SignChangeEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if ($block instanceof SignPost) {
            $text = $event->getLines();
            switch ($text[0]) {
                case "[CE]":
                case "ce":
				case "CE":
				case "customenchants":
				case "CustomEnchants":
                    if (!$player->hasPermission("ptkcustomenchantsshop.makesign")) {
                        $event->setLines([TextFormat::RED . "Không Có Quyền","Để Làm Việc Này.", "", ""]);
                        return false;
                    }
                    if (is_null($enchantment = \PTKCustomEnchants\CustomEnchants\CustomEnchants::getEnchantmentByName($text[1]))) {
                        if (is_numeric($text[1]) && is_null($enchantment = \PTKCustomEnchants\CustomEnchants\CustomEnchants::getEnchantment($text[1])) !== true) {
                            $event->setLine(1, $enchantment->getName());
                        } else {
                            $event->setLine(1, TextFormat::RED . "Phù Phép Không Tồn Tại.");
                            return false;
                        }
                    }
                    if (!is_numeric($text[2])) {
                        $event->setLine(2, TextFormat::RED . "Thiếu Cấp Của Phù Phép.");
                        return false;
                    }
                    if (!is_numeric($text[3])) {
                        $event->setLine(3, TextFormat::RED . "Thiếu Giá Của Phù Phép.");
                        return false;
                    }
                    $event->setLine(0, "§e⦿⭐︎⦿§1[ᗰᑌᗩ EᑎᑕᕼᗩᑎT]§e⦿⭐︎⦿");
                    $event->setLine(1, "§aEᑎᑕᕼᗩᑎT: §d" . ucfirst($text[1]));
                    $event->setLine(2, "§aᑕấᑭ:§d " . $text[2]);
                    $event->setLine(3, "§aGIÁ:§d " . $text[3]);
                    $this->plugin->getProvider()->addShop(new Shop($block->x, $block->y, $block->z, $enchantment->getName(), $text[2], $text[3]));
                    break;
            }
        }
        return true;
    }

    /**
     * @param Player $player
     * @param Shop $shop
     */
    public function buyItem(Player $player, Shop $shop)
    {
        if ($this->plugin->ce->canBeEnchanted($player->getInventory()->getItemInHand(), CustomEnchants::getEnchantmentByName($shop->getEnchantment()), $shop->getLevel())) {
            $this->plugin->getEconomyManager()->takeMoney($player, $shop->getPrice());
        }
        $player->getInventory()->setItemInHand($this->plugin->ce->addEnchantment($player->getInventory()->getItemInHand(), $shop->getEnchantment(), $shop->getLevel(), true, $player)); //Still do it anyway to send the issue to player
        if (isset($this->tap[$player->getLowerCaseName()])) {
            unset($this->tap[$player->getLowerCaseName()]);
        }
    }
}
