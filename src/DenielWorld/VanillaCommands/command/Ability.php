<?php

namespace DenielWorld\VanillaCommands\command;

use pocketmine\command\PluginCommand;
use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Ability extends PluginCommand implements PluginIdentifiableCommand {

    private $reg_abilities = ["mute", "worldbuilder", "mayfly"];

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.ability");
        $this->setUsage("/ability <player: player> <ability: string> <value: bool>");
        $this->setDescription("Sets a player's ability.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(count($args) < 3)
            throw new InvalidCommandSyntaxException();
        if(!in_array(strtolower($args[1]), $this->reg_abilities)) {
            $sender->sendMessage(TextFormat::RED."Ability must be one of: ".implode(", ", $this->reg_abilities));
            return;
        }
        if(!is_bool($args[2]))
            throw new InvalidCommandSyntaxException();
        $player = $this->getPlugin()->getServer()->getPlayer($args[0]);
        if(!$player instanceof Player) {
            $sender->sendMessage(TextFormat::RED."Player offline!");
            return;
        }
        if ($args[2]) {
            $player->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true);
        } else {
            if ($player->hasPermission("vanillacommands.state." . $args[1])) {
                $attachment = $player->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true);
                $player->removeAttachment($attachment);
            }
        }
    }
}