<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\event\server\QueryRegenerateEvent;

class SetMaxPlayers extends PluginCommand implements PluginIdentifiableCommand{

    private $plugin;

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setUsage("/setmaxplayers <int>");
        $this->setDescription("Set the max amount of players for the current game session");
        $this->setPermission("vanillacommands.command.setmaxplayers");
        $this->plugin = $owner;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0]) and is_int($args[0])) {
            $this->plugin->setMaxCount($args[0]);//In-built hack to set the max amount of players (The count should be equal to or below the count given in server.properties to function)
            $this->getPlugin()->getServer()->getQueryInformation()->setMaxPlayerCount($args[0]);//set fake max player count, since I can't do it with some hacky method lol
            $this->getPlugin()->getServer()->getPluginManager()->callEvent(new QueryRegenerateEvent); //Should use a different way to call the event later, since this method is deprecated
        }
    }
}