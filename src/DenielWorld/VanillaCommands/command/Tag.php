<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class Tag extends PluginCommand implements PluginIdentifiableCommand{

    private $plugin;

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->plugin = $owner;
        $this->setUsage("/tag <player> <add|remove> <tag> | /tag list <player>");
        $this->setDescription("Manage an entity's tag");
        $this->setPermission("vanillacommands.command.tag");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $player_data = new Config($this->plugin->getDataFolder() . "player_data.yml", Config::YAML);
        if($sender instanceof Player){
            if(count($args) < 2) {
                $sender->sendMessage($this->getUsage());
            }
            elseif($args[1] == "list"){
                $player = $this->plugin->getServer()->getPlayer($args[1]);
                if($player instanceof Player){
                    $sender->sendMessage($args[1] . "'s Tags:");
                    $msg = implode(", ", $player_data->getNested($player->getLowerCaseName() . ".tags"));
                    $sender->sendMessage($msg);
                    /*foreach ($player_data->getNested($player->getLowerCaseName() . ".tags") as $tag){
                        $sender->sendMessage($tag);
                    }*/
                }
                else {
                    $sender->sendMessage("Please provide a valid player");
                }
            }
            else{
                if($this->plugin->getServer()->getPlayer($args[0]) instanceof Player){
                    $player = $this->plugin->getServer()->getPlayer($args[0]);
                    if($args[1] == "remove"){
                        if(is_string($args[2])){
                            $tags = $player_data->getNested($player->getLowerCaseName() . ".tags");
                            if(array_search($args[2], $tags) !== false) {
                                unset($tags[array_search($args[2], $tags)]);
                                $player_data->setNested($player->getLowerCaseName() . ".tags", $tags);
                                $player_data->save();
                                $sender->sendMessage("Successfully removed " . $args[2] . " from " . $player->getName());
                            }
                            else {
                                $sender->sendMessage("Tag not found");
                            }
                        }
                        else {
                            $sender->sendMessage($this->getUsage());
                        }
                    }
                    elseif($args[1] == "add") {
                        if (is_string($args[2])) {
                            $tags = $player_data->getNested($player->getLowerCaseName() . ".tags");
                            if (!is_int(array_search($args[2], $tags))) {
                                array_push($tags, $args[2]);
                                $player_data->setNested($player->getLowerCaseName() . ".tags", $tags);
                                $player_data->save();
                                $sender->sendMessage("Successfully added " . $args[2] . " to " . $player->getName());
                            } else {
                                $sender->sendMessage("");
                            }
                        }
                    }
                }
            }
        }
        else {
            $sender->sendMessage("Please run this command in-game");
        }
    }
}
