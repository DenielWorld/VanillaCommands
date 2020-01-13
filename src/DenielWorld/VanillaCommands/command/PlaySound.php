<?php

namespace DenielWorld\VanillaCommands\command;

use DenielWorld\VanillaCommands\Loader;
use DenielWorld\VanillaCommands\utils\SoundManager;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class PlaySound extends PluginCommand implements PluginIdentifiableCommand{

    protected $sound_manager;

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setUsage("/playsound <sound: string> [player: target] [position: x y z] [volume: float] [pitch: float] [minimumVolume: float]"); //Can't assign "Minimum volume through packet?
        $this->setDescription("Plays a sound");
        $this->setPermission("vanillacommands.command.playsound");
        $this->sound_manager = new SoundManager();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(count($args) < 1) {
            throw new InvalidCommandSyntaxException();
        }
        if(is_int($args[0]))
            $name = $this->sound_manager->getNameFromId($args[0]);
        else
            $name = $args[0];
        if(!isset($args[1])){
            if(!$sender instanceof Player) {
                $sender->sendMessage("Please run this command in-game");
                return;
            }
            $packet = new PlaySoundPacket();
            $packet->soundName = $name;
            $packet->x = $sender->x;
            $packet->y = $sender->y;
            $packet->z = $sender->z;
            $packet->volume = 3;
            $packet->pitch = 2;
        }elseif(!isset($args[4])) {
            $player = $this->getPlugin()->getServer()->getPlayer($args[1]);
            if(!$player instanceof Player) {
                $sender->sendMessage(TextFormat::RED."Player not found");
                return;
            }
            $packet = new PlaySoundPacket();
            $packet->soundName = $name;
            $packet->x = $player->x;
            $packet->y = $player->y;
            $packet->z = $player->z;
            $packet->volume = 3;
            $packet->pitch = 2;
            $player->sendDataPacket($packet);
        }elseif(count($args) < 7) {
            $packet = new PlaySoundPacket();
            $packet->soundName = $name;
            $packet->x = $args[2];
            $packet->y = $args[3];
            $packet->z = $args[4];
            $packet->volume = $args[5] ?? 3;
            $packet->pitch = $args[6] ?? 2;
            $sender->sendDataPacket($packet);
        }else{
            throw new InvalidCommandSyntaxException();
        }
    }
}