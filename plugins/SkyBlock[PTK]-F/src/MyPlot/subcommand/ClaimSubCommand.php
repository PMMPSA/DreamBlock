<?php
namespace MyPlot\subcommand;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;

class ClaimSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.claim");
    }

    public function execute(CommandSender $sender, array $args) {
        if (count($args) > 1) {
            return false;
        }
        $name = "";
        if (isset($args[0])) {
            $name = $args[0];
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $plot = $this->getPlugin()->getPlotByPosition($player->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
            return true;
        }
        if ($plot->owner != "") {
            if ($plot->owner === $sender->getName()) {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.yourplot"));
            } else {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.alreadyclaimed", [$plot->owner]));
            }
            return true;
        }

        $maxPlots = $this->getPlugin()->getMaxPlotsOfPlayer($player);
        $plotsOfPlayer = count($this->getPlugin()->getProvider()->getPlotsByOwner($player->getName()));
        if ($plotsOfPlayer >= $maxPlots) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.maxplots", [$maxPlots]));
            return true;
        }

        $plotLevel = $this->getPlugin()->getLevelSettings($plot->levelName);
        $economy = $this->getPlugin()->getEconomyProvider();
        if ($economy !== null and !$economy->reduceMoney($player, $plotLevel->claimPrice)) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.nomoney"));
            return true;
        }

        $plot->owner = $sender->getName();
        $plot->name = $name;
        if ($this->getPlugin()->getProvider()->savePlot($plot)) {
            $sender->sendMessage($this->translateString("claim.success"));
			
			$f = $this->getPlugin()->getPlotPosition($plot);
			$plotSize = $this->getPlugin()->getLevelSettings($plot->levelName)->plotSize;
			$f->x += floor($plotSize / 2) - 3;
			$f->z -= -89;
			$f->y += 5;
			
			$sender->getLevel()->setBlock(new \pocketmine\math\Vector3($f->x, $f->y, $f->z), \pocketmine\block\Block::get(\pocketmine\block\Block::CHEST), true, true);
			
			$nbt = new CompoundTag("", [
			new ListTag("Items", []),
			new StringTag("id", Tile::CHEST),
			new IntTag("x", $f->x),
			new IntTag("y", $f->y),
			new IntTag("z", $f->z)
		]);
		$nbt->Items->setTagType(NBT::TAG_Compound);
		
		//$sender->getLevel()->getChunk($sender->getX() >> 4, $sender->getZ() >> 4) <- nếu chuyển qua genisys thì sửa cái này vào $sender->getLevel()
		$tile = Tile::createTile("Chest",$sender->getLevel(), $nbt);
			//Tạo Chest
			$ie1 = Item::get(278, 0, 1);
			$ie1->setCustomName("§r§eCúp SkyBlock");
			$ie2 = Item::get(279, 0, 1);
			$ie2->setCustomName("§r§eRìu SkyBlock");
			$ie3 = Item::get(277, 0, 1);
			$ie3->setCustomName("§r§eXẻng SkyBlock");
			$ie1->addEnchantment(Enchantment::getEnchantment(17)->setLevel(17));
			$ie2->addEnchantment(Enchantment::getEnchantment(17)->setLevel(17));
			$ie3->addEnchantment(Enchantment::getEnchantment(17)->setLevel(17));
			$ie1->addEnchantment(Enchantment::getEnchantment(17)->setLevel(15));
			$ie2->addEnchantment(Enchantment::getEnchantment(17)->setLevel(15));
			$ie3->addEnchantment(Enchantment::getEnchantment(17)->setLevel(15));
			$i = $tile->getInventory();
			$ip = $player->getInventory();
			$i->addItem(Item::get(2, 0, 16));
			$i->addItem(Item::get(8, 0, 2));
			$i->addItem(Item::get(Item::FENCE, 0, 2));
			$ip->addItem(Item::get(276, 0, 1));
			$ip->addItem($ie1);
			$ip->addItem($ie2);
			$ip->addItem($ie3);
			$i->addItem(Item::get(6, 0, 2));
			$i->addItem(Item::get(81, 0, 3));
			$i->addItem(Item::get(295, 0, 5));
			$i->addItem(Item::get(338, 0, 3));
			$i->addItem(Item::get(352, 0, 10));
			$i->addItem(Item::get(361, 0, 3));
			$i->addItem(Item::get(362, 0, 3));
			$i->addItem(Item::get(391, 0, 3));
			$i->addItem(Item::get(392, 0, 3));
			$i->addItem(Item::get(54, 0, 1));
			$i->addItem(Item::get(364, 0, 5));
            $sender->sendMessage("§b[§bDream§aBlock§b-§aSkyBlock§b] §aĐã Thêm§b Nước§a Và§6 Hàng Rào§a Vào Rương Đồ Bên Cạnh Bạn!");
            $sender->sendMessage("§b[§bDream§aBlock§b-§aSkyBlock§b] §bHãy Sử Dụng Hàng Rào Thay Cho Dung Nham Để Làm Máy Farm Đá!");
			} else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("error"));
        }
        return true;
    }
}