<?php

namespace DenielWorld;

use DenielWorld\command\Connect;
use DenielWorld\command\SetBlock;
use DenielWorld\command\SetMaxPlayers;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use DenielWorld\command\AlwaysDay;
use DenielWorld\command\Ability;
use DenielWorld\command\Clear;

class Loader extends PluginBase implements Listener{
    //Storing online player names in here, mostly used as count($playercount)
    private $playercount = [];

    //Storing MaxPlayerCount here passed from SetMaxPlayers command
    private $maxcount;

    public function onEnable()
    {
        $commands = [
            new Ability("ability", $this),
            new AlwaysDay("alwaysday", $this),
            new Clear("clear", $this),
            new Connect("connect", $this),
            new SetBlock("setblock", $this),
            new SetMaxPlayers("setmaxplayers", $this)
        ];
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->registerAll("vanillacommands", $commands);
    }

    public function getInstance(){
        return self::getInstance();
    }

    public function getMaxCount(){
        return $this->maxcount;
    }

    public function setMaxCount(int $count){
        $this->maxcount = $count;
    }

    public function getPlayerCount(){
        return count($this->playercount);
    }

    public function onJoin(PlayerJoinEvent $event){
        if(isset($this->maxcount) and $this->maxcount == count($this->playercount)) {
            $event->getPlayer()->kick("The server is full", false);
        }
        elseif(isset($this->maxcount) and $this->maxcount !== count($this->playercount)){
            array_push($this->playercount, $event->getPlayer()->getName());
        }
    }

    public function onLeave(PlayerQuitEvent $event){
        array_shift($this->playercount);
    }
    //todo listener for ability states
}