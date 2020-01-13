<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

class AlwaysDay extends PluginCommand implements PluginIdentifiableCommand
{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.alwaysday");
        $this->setUsage("/alwaysday [lock: Boolean]");
        $this->setDescription("Locks and unlocks the day-night cycle");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $level = $sender->getLevel();
            if (!isset($args[0])) {
	            $level->setTime(5000);
	            $level->stopTime();
	            $sender->sendMessage("Day-Night cycle locked");
	            return;
            }
            if(is_bool($args[0])) {
            	if($args[0]){
		            $level->startTime();
		            $sender->sendMessage("Day-Night cycle locked");
	            }else{
		            $level->setTime(5000);
		            $level->stopTime();
		            $sender->sendMessage("Day-Night cycle unlocked");
	            }
            }
        }else{
            $sender->sendMessage("Please run this command in-game");
        }
    }
}
