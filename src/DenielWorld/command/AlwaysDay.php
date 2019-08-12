<?php

namespace DenielWorld\command;

use DenielWorld\Loader;
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
        $this->setUsage("/alwaysday <true/false>");
        $this->setDescription("Sets the time to day and pauses it");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $level = $sender->getLevel();
            if (isset($args[0])) {
                switch ($args[0]) {
                    case false:
                        $level->startTime();
                        $sender->sendMessage("Successfully set AlwaysDay to false");
                        break;
                    default:
                        $level->setTime(5000);
                        $level->stopTime();
                        $sender->sendMessage("Successfully set AlwaysDay to true");
                }
            }else{
                $level->setTime(5000);
                $level->stopTime();
                $sender->sendMessage("Successfully set AlwaysDay to true");
            }
        }else{
            $sender->sendMessage("Please run this command in-game");
        }
    }
}
