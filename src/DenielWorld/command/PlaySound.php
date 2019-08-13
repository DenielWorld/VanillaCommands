<?php

namespace DenielWorld\command;

use DenielWorld\Loader;
use DenielWorld\utils\SoundManager;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\utils\TextFormat as TF;
use pocketmine\Player;

class PlaySound extends PluginCommand implements PluginIdentifiableCommand{

    protected $sound_manager;

    public function __construct(string $name, Loader $owner)
    {
        parent::__construct($name, $owner);
        $this->setUsage("/playsound <sound> <player> <x> <y> <z> <volume> <pitch>");//Can't assign "Minimum volume through packet?
        $this->setDescription("Play a sound in game at a specific location");
        $this->setPermission("vanillacommands.command.playsound");
        $sound_manager = new SoundManager();
        $this->sound_manager = $sound_manager;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if(isset($args[0]) and is_string($args[0]) and isset($args[1]) and is_string($args[1]) and !isset($args[2])){
                $player = $this->getPlugin()->getServer()->getPlayer($args[1]);
                if($player instanceof Player and $this->sound_manager->isValidName($args[0])){
                    $packet = new PlaySoundPacket();
                    $packet->soundName = $args[0];
                    $packet->x = $player->x;
                    $packet->y = $player->y;
                    $packet->z = $player->z;
                    $packet->volume = 3;
                    $packet->pitch = 2;
                    $player->sendDataPacket($packet);
                }
                elseif($player instanceof Player and !$this->sound_manager->isValidName($args[0]) and $this->sound_manager->isValidConstString($args[0])){
                    $packet = new PlaySoundPacket();
                    $packet->soundName = $this->sound_manager->constAsName($args[0]);
                    $packet->x = $player->x;
                    $packet->y = $player->y;
                    $packet->z = $player->z;
                    $packet->volume = 3;
                    $packet->pitch = 2;
                    $player->sendDataPacket($packet);
                }
                else {
                    $sender->sendMessage(TF::RED . "Invalid player or sound");
                }
            }
            elseif(isset($args[0]) and is_string($args[0]) and isset($args[1]) and is_string($args[1]) and isset($args[2]) and isset($args[3]) and isset($args[4]) and is_int($args[2]) and is_int($args[3]) and is_int($args[4]) and !isset($args[5])){
                if($this->sound_manager->isValidName($args[0])){
                    $packet = new PlaySoundPacket();
                    $packet->soundName = $args[0];
                    $packet->x = $args[2];
                    $packet->y = $args[3];
                    $packet->z = $args[4];
                    $packet->volume = 3;
                    $packet->pitch = 2;
                    $sender->sendDataPacket($packet);
                }
                elseif(!$this->sound_manager->isValidName($args[0]) and $this->sound_manager->isValidConstString($args[0])){
                    $packet = new PlaySoundPacket();
                    $packet->soundName = $this->sound_manager->constAsName($args[0]);
                    $packet->x = $args[2];
                    $packet->y = $args[3];
                    $packet->z = $args[4];
                    $packet->volume = 3;
                    $packet->pitch = 2;
                    $sender->sendDataPacket($packet);
                }
                else {
                    $sender->sendMessage(TF::RED . "Invalid sound");
                }
            }
            elseif(isset($args[0]) and is_string($args[0]) and isset($args[1]) and is_string($args[1]) and isset($args[2]) and isset($args[3]) and isset($args[4]) and is_int($args[2]) and is_int($args[3]) and is_int($args[4]) and isset($args[5]) and is_int($args[5]) and !isset($args[6])){
                if($this->sound_manager->isValidName($args[0])){
                    $packet = new PlaySoundPacket();
                    $packet->soundName = $args[0];
                    $packet->x = $args[2];
                    $packet->y = $args[3];
                    $packet->z = $args[4];
                    $packet->volume = $args[5];
                    $packet->pitch = 2;
                    $sender->sendDataPacket($packet);
                }
                elseif(!$this->sound_manager->isValidName($args[0]) and $this->sound_manager->isValidConstString($args[0])){
                    $packet = new PlaySoundPacket();
                    $packet->soundName = $this->sound_manager->constAsName($args[0]);
                    $packet->x = $args[2];
                    $packet->y = $args[3];
                    $packet->z = $args[4];
                    $packet->volume = $args[5];
                    $packet->pitch = 2;
                    $sender->sendDataPacket($packet);
                }
                else {
                    $sender->sendMessage(TF::RED . "Invalid sound");
                }
            }
            elseif(isset($args[0]) and is_string($args[0]) and isset($args[1]) and is_string($args[1]) and isset($args[2]) and isset($args[3]) and isset($args[4]) and is_int($args[2]) and is_int($args[3]) and is_int($args[4]) and isset($args[5]) and is_int($args[5]) and isset($args[6]) and is_int($args[6])){
                if($this->sound_manager->isValidName($args[0])){
                    $packet = new PlaySoundPacket();
                    $packet->soundName = $args[0];
                    $packet->x = $args[2];
                    $packet->y = $args[3];
                    $packet->z = $args[4];
                    $packet->volume = $args[5];
                    $packet->pitch = $args[6];
                    $sender->sendDataPacket($packet);
                }
                elseif(!$this->sound_manager->isValidName($args[0]) and $this->sound_manager->isValidConstString($args[0])){
                    $packet = new PlaySoundPacket();
                    $packet->soundName = $this->sound_manager->constAsName($args[0]);
                    $packet->x = $args[2];
                    $packet->y = $args[3];
                    $packet->z = $args[4];
                    $packet->volume = $args[5];
                    $packet->pitch = $args[6];
                    $sender->sendDataPacket($packet);
                }
                else {
                    $sender->sendMessage(TF::RED . "Invalid sound");
                }
            }
            else {
                $sender->sendMessage(TF::RED . "Please provide valid arguments");
            }
        }
        else{
            $sender->sendMessage("Please run this command in-game"); //might add console support in future
        }//todo support id sounds?
    }
}