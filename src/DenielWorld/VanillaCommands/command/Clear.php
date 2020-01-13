<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Clear extends PluginCommand implements PluginIdentifiableCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.clear");
        $this->setUsage("/clear [Player: target] [itemName: item] [data: int] [maxCount: int]");
        $this->setDescription("Clears items from player inventory.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
    	if(!isset($args[0]) and $sender instanceof Player) {
    		$items = count($sender->getInventory()->getContents());
		    $sender->getInventory()->clearAll();
		    $items += count($sender->getArmorInventory()->getContents());
		    $sender->getArmorInventory()->clearAll();
		    $sender->sendMessage("Cleared the inventory of ".$sender->getName().", removing $items items");
		    return;
	    }elseif(!$sender instanceof Player) {
    		$sender->sendMessage(TextFormat::RED."You must be in-game to use this command!");
    		return;
	    }
    	$player = $this->getPlugin()->getServer()->getPlayer($args[0]);
    	if(!$player instanceof Player) {
    		$sender->sendMessage(TextFormat::RED."No targets matched selector");
    		return;
	    }
	    if (!isset($args[1])) {
		    $items = count($player->getInventory()->getContents());
		    $player->getInventory()->clearAll();
		    $items += count($player->getArmorInventory()->getContents());
		    $player->getArmorInventory()->clearAll();
		    $sender->sendMessage("Cleared the inventory of ".$player->getName().", removing $items items");
		    return;
	    }
	    foreach ($player->getInventory()->getContents() as $slot => $item) {
	    	if (!isset($args[2]) and $item->getId() == $args[1]) {
	    		$player->getInventory()->remove(Item::get($item->getId(), 0, $item->getCount()));
	    	} elseif ($item->getId() == $args[1] and isset($args[2]) and is_int($args[2]) and $item->getDamage() == $args[2] and !isset($args[3])) {
	    		$player->getInventory()->remove(Item::get($item->getId(), $item->getDamage(), $item->getCount()));
	    	} elseif ($item->getId() == $args[1] and isset($args[2]) and is_int($args[2]) and $item->getDamage() == $args[2] and is_int($args[3]) and isset($args[3])) {
	    		$player->getInventory()->remove(Item::get($item->getId(), $item->getDamage(), $args[3]));
	    	} elseif ($item->getName() == $args[1] and isset($args[2]) and is_int($args[2]) and $item->getDamage() == $args[2] and !isset($args[3])) {
			    $player->getInventory()->remove(Item::get($item->getId(), $item->getDamage(), $item->getCount()));
		    } elseif ($item->getName() == $args[1] and isset($args[2]) and is_int($args[2]) and $item->getDamage() == $args[2] and is_int($args[3]) and isset($args[3])) {
			    $player->getInventory()->remove(Item::get($item->getId(), $item->getDamage(), $args[3]));
		    } else {
	    		$sender->sendMessage(TextFormat::RED . "Could not clear the inventory of ".$player->getName(). ", no items to remove");
	    	}
	    }
    }
}