<?php

namespace DenielWorld\command;

use DenielWorld\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class Clear extends PluginCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.clear");
        $this->setUsage("/clear <Player> <Item> <Meta> <Count>"); //id required?
        $this->setDescription("Clear a player's inventory, or remove a specific item from the player's inventory");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0]) and $args[0] instanceof Player){
            if(isset($args[1]) and is_int($args[1]) and $sender instanceof Player){
                foreach($args[0]->getInventory()->getContents() as $slot => $item) {
                    if(!isset($args[2]) and $item->getId() == $args[1]) {
                        $args[0]->getInventory()->remove(Item::get($item->getId(), 0, $item->getCount()));
                    }
                    elseif($item->getId() == $args[1] and isset($args[2]) and is_int($args[2]) and $item->getDamage() == $args[2] and !isset($args[3])) {
                        $args[0]->getInventory()->remove(Item::get($item->getId(), $item->getDamage(), $item->getCount()));
                    }
                    elseif($item->getId() == $args[1] and isset($args[2]) and is_int($args[2]) and $item->getDamage() == $args[2] and is_int($args[3]) and isset($args[3])){
                        $args[0]->getInventory()->remove(Item::get($item->getId(), $item->getDamage(), $args[3]));
                    }
                    else{
                        $sender->sendMessage(TF::RED . "Please provide a valid item id (Optional: Meta, Count");
                    }
                }
            }
            else{
                $args[0]->getInventory()->clearAll();
            }
        }
        else {
            $sender->sendMessage(TF::RED . "Please specify a real player");
        }
    }
}