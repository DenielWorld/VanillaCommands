<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as TF;

class MobEvent extends PluginCommand implements PluginIdentifiableCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setDescription("Controls what mob events are allowed to run");
        $this->setUsage("/mobevent <event: mobEvent> [value: Boolean]");
        $this->setPermission("vanillacommands.command.mobevent");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
	    if(!isset($args[0]))
	    	throw new InvalidCommandSyntaxException();
    	if(!in_array(strtolower($args[0]), $this->getPlugin()->getLegalMobEvents())) {
		    $sender->sendMessage(TextFormat::RED."Ability must be one of: ".implode(", ", $this->getPlugin()->getLegalMobEvents()));
    		return;
	    }
    	$value = true;
    	if(isset($args[1]) and is_bool($args[1]))
    		$value = $args[1];
    	if($value) {
		    $this->getPlugin()->addMobEvent(strtolower($args[0]));
		    $sender->sendMessage(TF::GREEN . $args[0] . "is now set to true");
	    }else{
		    $this->getPlugin()->removeMobEvent(strtolower($args[0]));
		    $sender->sendMessage(TF::GREEN . $args[0] . "is now set to false");
	    }
    }
}