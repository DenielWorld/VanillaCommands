<?php

namespace DenielWorld\command;

use DenielWorld\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class ImmutableWorld extends PluginCommand implements PluginIdentifiableCommand
{

    private $plugin;

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.immutableworld");
        $this->setUsage("/immutableworld <bool>");
        $this->setDescription("Fully protects the current world for the given game session");
        $this->plugin = $owner;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player) {
            if (isset($args[0]) and is_bool($args[0]) and $args[0] == true) {
                $this->plugin->addImmutableWorld($sender->getLevel()->getName());
                $sender->sendMessage(TF::GREEN . $sender->getLevel()->getName() . "is now protected");
            }
            elseif (isset($args[0]) and is_bool($args[0]) and $args[0] == false) {
                $this->plugin->removeImmutableWorld($sender->getLevel()->getName());
                $sender->sendMessage(TF::GREEN . $sender->getLevel()->getName() . "is no longer protected");
            }
            else {
                $sender->sendMessage(TF::RED . "/immutableworld <bool>");
            }
        }
        else {
            $sender->sendMessage("Please run this command in-game");
        }
    }
}