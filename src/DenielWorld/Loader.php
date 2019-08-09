<?php

namespace DenielWorld;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use DenielWorld\commands\AlwaysDay;
use Ability;
use DenielWorld\commands\Clear;

class Loader extends PluginBase implements Listener{

    public function onEnable()
    {
        $commands = [
            new Ability("ability", $this),
            new AlwaysDay("alwaysday", $this),
            new Clear("clear", $this)
        ];
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->registerAll("VanillaCommands", $commands);
    }

    //todo listener for ability states
}