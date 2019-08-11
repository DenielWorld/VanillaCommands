<?php

namespace DenielWorld\command;

use DenielWorld\Loader;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class SetBlock extends PluginCommand implements PluginIdentifiableCommand{

    private $oldblockhandles = ["destroy", "keep", "replace"];

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setUsage("/setblock <x> <y> <z> <Block> <Meta> <oldBlockHandling>");
        $this->setDescription("Sets a single block at a given position");
        $this->setPermission("vanillacommands.command.setblock");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $b_handle = $this->oldblockhandles;
       if($sender instanceof Player){
           if(isset($args[0]) and isset($args[1]) and isset($args[2])){
               if(isset($args[3]) and is_int($args[3]) and !isset($args[4]) and Block::get($args[3])->isValid() and !isset($args[5])){
                   $sender->getLevel()->setBlock(new Vector3($args[0], $args[1], $args[2]), Block::get($args[3]));
                   $sender->sendMessage(TF::GREEN . "Block at X: " . $args[0] . ", Y: " . $args[1] . ", Z: " . $args[2] . " successfully set to " . Block::get($args[3])->getName());
               }
               elseif(isset($args[3]) and is_string($args[3]) and !isset($args[4]) and Block::get((int)ItemFactory::fromString($args[3]))->isValid() and !isset($args[5])){
                   $itemblock = ItemFactory::fromString($args[3]);
                   $sender->getLevel()->setBlock(new Vector3($args[0], $args[1], $args[2]), Block::get((int)$itemblock));
                   $sender->sendMessage(TF::GREEN . "Block at X: " . $args[0] . ", Y: " . $args[1] . ", Z: " . $args[2] . " successfully set to " . Block::get((int)$itemblock)->getName());
               }
               elseif(isset($args[3]) and is_int($args[3]) and isset($args[4]) and is_int($args[4]) and Block::get($args[3])->isValid() and !isset($args[5])){
                   $sender->getLevel()->setBlock(new Vector3($args[0], $args[1], $args[2]), Block::get($args[3], $args[4]));
                   $sender->sendMessage(TF::GREEN . "Block at X: " . $args[0] . ", Y: " . $args[1] . ", Z: " . $args[2] . " successfully set to " . Block::get($args[3], $args[4])->getName());
               }
               elseif(isset($args[3]) and is_string($args[3]) and isset($args[4]) and is_int($args[4]) and Block::get((int)ItemFactory::fromString($args[3]))->isValid() and !isset($args[5])){
                   $itemblock = ItemFactory::fromString($args[3]);
                   $sender->getLevel()->setBlock(new Vector3($args[0], $args[1], $args[2]), Block::get((int)$itemblock), $args[4]);
                   $sender->sendMessage(TF::GREEN . "Block at X: " . $args[0] . ", Y: " . $args[1] . ", Z: " . $args[2] . " successfully set to " . Block::get((int)$itemblock)->getName());
               }
               //todo more elseif checks in case oldBlockHandling is provided in args
               else{
                   $sender->sendMessage(TF::RED . "Please define a valid block");
                   $sender->sendMessage(TF::AQUA . "Optional: Provide a meta and oldBlockHandling");
               }
           }
           else {
               $sender->sendMessage(TF::RED . "Please provide the XYZ");
           }
       }
       else {
           $sender->sendMessage("Please run this command in-game");
       }
    }
}