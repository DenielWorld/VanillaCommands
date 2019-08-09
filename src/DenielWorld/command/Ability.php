<?php

use pocketmine\command\PluginCommand;
use DenielWorld\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;

class Ability extends PluginCommand {

    private $reg_abilities = ["mute", "worldbuilder", "mayfly"];

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("vanillacommands.command.ability");
        $this->setUsage("ability <player> <ability> <true/false>");
        $this->setDescription("Sets a player's ability.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $cfg = new Config($this->getPlugin()->getDataFolder() . "config.yml", Config::YAML);
        foreach($this->reg_abilities as $ability){
            if(isset($args[0]) and $args[0] instanceof Player){
                if(isset($args[1]) and $args[0] == $ability) {
                    if(isset($args[2]) and is_bool($args[2])){
                        if($args[2] == true){
                            $args[0]->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true); //wonder what true/false is for
                        }
                        elseif ($args[2] == false){
                            if($args[0]->hasPermission("vanillacommands.state." . $args[1])){
                                $attachment = $args[0]->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true);
                                $args[0]->removeAttachment($attachment);
                            }
                            else {
                                return false;
                            }
                        }
                        else {
                            if($cfg->get("ability-default-bool") == true){
                                $args[0]->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true);
                            }
                            elseif($cfg->get("ability-default-bool") == false){
                                if($args[0]->hasPermission("vanillacommands.state." . $args[1])){
                                    $attachment = $args[0]->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true);
                                    $args[0]->removeAttachment($attachment);
                                }
                                else {
                                    return false;
                                }
                            }
                            else {
                                //True is the backup default value
                                $args[0]->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true);
                            }
                        }
                    }
                    else {
                        if($cfg->get("ability-default-bool") == true){
                            $args[0]->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true);
                        }
                        elseif($cfg->get("ability-default-bool") == false){
                            if($args[0]->hasPermission("vanillacommands.state." . $args[1])){
                                $attachment = $args[0]->addAttachment($this->getPlugin(), "vanillacommands.state." . $args[1], true);
                                $args[0]->removeAttachment($attachment);
                            }
                            else {
                                $sender->sendMessage(TF::RED . "Please define if the ability will be set to true or false for the given player");
                            }
                        }
                        else {
                            $sender->sendMessage(TF::RED . "Default value not found, please check your config or have an Admin check config");
                        }
                    }
                }
                else {
                    $sender->sendMessage(TF::RED . "Please assign a valid ability");
                }
            }
            else {
                $sender->sendMessage(TF::RED . "Please assign a valid player");
            }
        }
        return true;
    }
}