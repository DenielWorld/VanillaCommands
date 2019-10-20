<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as TF;

class MobEvent extends PluginCommand implements PluginIdentifiableCommand{

    private $legalmobevents;

    private $plugin;

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setDescription("Control what mob events are allowed to run");
        $this->setUsage("/mobevent <event> <bool>");
        $this->setPermission("vanillacommands.command.mobevent");
        $this->plugin = $owner;
        $this->legalmobevents = $owner->getLegalMobEvents();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        foreach($this->legalmobevents as $legalmobevent){
            if(isset($args[0])) continue;
            if($args[0] == $legalmobevent and !isset($args[1])) {
                $sender->sendMessage(TF::RED . "Please provide a bool argument");
            }
            elseif($args[0] == $legalmobevent and isset($args[1]) and $args[1] == true){
                $this->plugin->addMobEvent($args[0]);
                $sender->sendMessage(TF::GREEN . $args[0] . "is now set to true");
            }
            elseif($args[0] == $legalmobevent and isset($args[1]) and $args[1] == false){
                $this->plugin->removeMobEvent($args[0]);
                $sender->sendMessage(TF::GREEN . $args[0] . "is now set to false");
            }
        }
    }
}