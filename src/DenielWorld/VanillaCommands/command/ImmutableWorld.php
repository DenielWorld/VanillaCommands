<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ImmutableWorld extends PluginCommand implements PluginIdentifiableCommand
{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.immutableworld");
        $this->setUsage("/immutableworld <value: Boolean>");
        $this->setDescription("Fully protects the current world for the given game session");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player) {
	        $sender->sendMessage(TextFormat::RED."Please run this command in-game");
	        return;
        }
        if(!isset($args[0]) or !is_bool($args[0]))
        	throw new InvalidCommandSyntaxException();
        if($args[0]) {
	        $this->getPlugin()->addImmutableWorld($sender->getLevel()->getName());
	        $sender->sendMessage(TextFormat::GREEN . $sender->getLevel()->getName() . "is now protected");
        }else{
	        $this->getPlugin()->removeImmutableWorld($sender->getLevel()->getName());
	        $sender->sendMessage(TextFormat::GREEN . $sender->getLevel()->getName() . "is no longer protected");
        }
    }
}