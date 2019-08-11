<?php

namespace DenielWorld\command;

use DenielWorld\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

class AlwaysDay extends PluginCommand implements PluginIdentifiableCommand {

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.alwaysday");
        $this->setUsage("/alwaysday <true/false>");
        $this->setDescription("Sets the time to day and pauses it");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player) {
            $level = $sender->getLevel();
            if (isset($args[0]) and $args[0] == true) {
                $level->setTime(5000);
                $level->stopTime();
            } elseif (isset($args[0]) and $args[0] == false) {
                $level->startTime();
            } else {
                $level->setTime(5000);
                $level->stopTime();
            }
        }
    }
}