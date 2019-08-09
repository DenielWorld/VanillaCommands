<?php

namespace DenielWorld\commands;

use DenielWorld\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class AlwaysDay extends PluginCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.alwaysday");
        $this->setUsage("/alwaysday <true/false>");
        $this->setDescription("Sets the time to day and pauses it");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0]) and $args[0] == true){
            $this->getPlugin()->getServer()->getDefaultLevel()->setTime(5000);
            $this->getPlugin()->getServer()->getDefaultLevel()->stopTime();
        }
        elseif(isset($args[0]) and $args[0] == false){
            $this->getPlugin()->getServer()->getDefaultLevel()->startTime();
        }
        else{
            $this->getPlugin()->getServer()->getDefaultLevel()->setTime(5000);
            $this->getPlugin()->getServer()->getDefaultLevel()->stopTime();
        }
    }
}