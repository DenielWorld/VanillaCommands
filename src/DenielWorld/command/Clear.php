<?php

namespace DenielWorld\command;

use DenielWorld\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as TF;

class Clear extends PluginCommand implements PluginIdentifiableCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.clear");
        $this->setUsage("/clear <Player> <Item> <Meta> <Count>"); //id required?
        $this->setDescription("Clear a player's inventory, or remove a specific item from the player's inventory");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0])) {
            $player = $this->getPlugin()->getServer()->getPlayer($args[0]);
            if(!$player){
                return false;
            }
            elseif (isset($args[1]) and is_int($args[1])) {
                foreach ($player->getInventory()->getContents() as $slot => $item) {
                    if (!isset($args[2]) and $item->getId() == $args[1]) {
                        $player->getInventory()->remove(Item::get($item->getId(), 0, $item->getCount()));
                    } elseif ($item->getId() == $args[1] and isset($args[2]) and is_int($args[2]) and $item->getDamage() == $args[2] and !isset($args[3])) {
                        $player->getInventory()->remove(Item::get($item->getId(), $item->getDamage(), $item->getCount()));
                    } elseif ($item->getId() == $args[1] and isset($args[2]) and is_int($args[2]) and $item->getDamage() == $args[2] and is_int($args[3]) and isset($args[3])) {
                        $player->getInventory()->remove(Item::get($item->getId(), $item->getDamage(), $args[3]));
                    } else {
                        $sender->sendMessage(TF::RED . "Please provide a valid item id (Optional: Meta, Count");
                    }
                }
            } else {
                $player->getInventory()->clearAll();
            }
        } else {
            $sender->sendMessage(TF::RED . "Please specify a real player");
        }
        return true;
    }
}