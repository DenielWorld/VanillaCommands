<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Tag extends PluginCommand implements PluginIdentifiableCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setUsage("/tag <player: target> <add|remove> <tag: string> | /tag list <player: target>");
        $this->setDescription("Manages tags stored in entities");
        $this->setPermission("vanillacommands.command.tag");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(count($args) < 2)
            throw new InvalidCommandSyntaxException();

        $player_data = new Config($this->getPlugin()->getDataFolder() . "player_data.yml", Config::YAML);
        if($args[1] == "list") {
            $player = $this->getPlugin()->getServer()->getPlayer($args[1]);
            if(!$player instanceof Player) {
                $sender->sendMessage(TextFormat::RED."No targets matched selector");
                return;
            }
            $sender->sendMessage($args[1] . "'s Tags:");
            $msg = implode(", ", $player_data->getNested($player->getLowerCaseName() . ".tags"));
            $sender->sendMessage($msg);
        }else{
            $player = $this->getPlugin()->getServer()->getPlayer($args[0]);
            if(!$player instanceof Player) {
                $sender->sendMessage(TextFormat::RED."No targets matched selector");
                return;
            }
            if(!is_string($args[2]))
                throw new InvalidCommandSyntaxException();

            if($args[1] == "remove"){
                $tags = $player_data->getNested($player->getLowerCaseName() . ".tags");
                if(!in_array($args[2], $tags)) {
                    $sender->sendMessage("Tag not found.");
                    return;
                }
                unset($tags[array_search($args[2], $tags)]);
                $player_data->setNested($player->getLowerCaseName() . ".tags", $tags);
                $player_data->save();
                $sender->sendMessage("Successfully removed " . $args[2] . " from " . $player->getName());
            }
            elseif($args[1] == "add") {
                $tags = $player_data->getNested($player->getLowerCaseName() . ".tags");
                if (!in_array($args[2], $tags)) {
                    array_push($tags, $args[2]);
                    $player_data->setNested($player->getLowerCaseName() . ".tags", $tags);
                    $player_data->save();
                    $sender->sendMessage("Successfully added " . $args[2] . " to " . $player->getName());
                }
            }
        }
    }
}
