<?php

namespace DenielWorld\VanillaCommands\command;//Same as /transfer command from PMMP, but different name lmao.

use DenielWorld\VanillaCommands\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\network\mcpe\protocol\AutomationClientConnectPacket;
use pocketmine\Player;

class Connect extends PluginCommand implements PluginIdentifiableCommand{

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setUsage("/connect <serverUri: text>");
        $this->setDescription("Attempts to connect to the websocket server on the provided URL");
        $this->setPermission("vanillacommands.command.connect");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
	        $sender->sendMessage("Please run this command in-game");
	        return;
        }
        if(!isset($args[0]))
        	throw new InvalidCommandSyntaxException();
        $pk = new AutomationClientConnectPacket();
        $pk->serverUri = $args[0];
        $sender->sendDataPacket($pk);
    }
}