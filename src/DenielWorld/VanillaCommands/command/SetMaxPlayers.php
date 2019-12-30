<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class SetMaxPlayers extends PluginCommand implements PluginIdentifiableCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setUsage("/setmaxplayers <maxPlayers: int>");
        $this->setDescription("Sets the maximum number of players for this game session.");
        $this->setPermission("vanillacommands.command.setmaxplayers");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
    	if(!isset($args[0]) or !is_int($args[0]))
    		throw new InvalidCommandSyntaxException();
	    $this->getPlugin()->getServer()->setConfigInt("max-players", $args[0]);
	    $ref = new \ReflectionClass($this->getPlugin()->getServer());
	    $prop = $ref->getProperty("maxPlayers");
	    $prop->setAccessible(true);
	    $prop->setValue((int)$args[0]);
	    $prop->setAccessible(false);
	    $this->getPlugin()->getServer()->getQueryInformation()->setMaxPlayerCount($args[0]);
	    $sender->sendMessage("Set max players to ".$args[0].".");
    }
}