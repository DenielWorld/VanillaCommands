<?php

namespace DenielWorld\command;//Same as /transfer command from PMMP, but different name lmao.

use DenielWorld\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class Connect extends PluginCommand implements PluginIdentifiableCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setUsage("/connect <Ip> <Port></Port>");
        $this->setDescription("Connect to other servers");
        $this->setPermission("vanillacommands.command.connect");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if(isset($args[0]) and isset($args[1]) and is_int($args[1])){
                $sender->transfer($args[0], $args[1]); //too simple, todo: enhance
            }
            else {
                $sender->sendMessage(TF::RED . "Please provide a valid IP and Port");
            }
        }
        else {
            $sender->sendMessage("Please run this command in-game"); #Todo - check for if sender is player in other cmds
        }
    }
}