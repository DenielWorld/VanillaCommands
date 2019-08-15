<?php

namespace DenielWorld;

use DenielWorld\command\Connect;
use DenielWorld\command\ImmutableWorld;
use DenielWorld\command\MobEvent;
use DenielWorld\command\PlaySound;
use DenielWorld\command\SetBlock;
use DenielWorld\command\SetMaxPlayers;
use pocketmine\entity\EntityIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\level\Level;
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

    //Storing mob events that shouldn't occur here
    private $mobevents = [];

    //Legal mob events
    private $legalmobevents = ["events_enabled", "minecraft:pillager_patrols_event", "minecraft:wandering_trader_event"];

    //Worlds that cannot have blocks broken or placed in them
    private $immutable_worlds = [];

    //todo add this stuff to max player manager - unset($array[array_search($value, $array)])
    //todo move some stuff from here to a separate EventListener, this should be mainly for loading and initiating what the plugin has to do
    public function onEnable()
    {
        $commands = [
            new Ability("ability", $this),
            new AlwaysDay("alwaysday", $this),
            new Clear("clear", $this),
            new Connect("connect", $this),
            new SetBlock("setblock", $this),
            new SetMaxPlayers("setmaxplayers", $this),
            new PlaySound("playsound", $this),
            new MobEvent("mobevent", $this),
            new ImmutableWorld("immutableworld", $this)
        ];
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->registerAll("vanillacommands", $commands);
    }

    public function getInstance(){
        return $this;
    }

    public function getMobEvents(){
        return $this->mobevents;
    }

    public function getLegalMobEvents(){
        return $this->legalmobevents;
    }

    public function addMobEvent(string $mobevent) : void{
        foreach($this->legalmobevents as $legalmobevent) {
            if ($mobevent == $legalmobevent){
                array_push($this->mobevents, $mobevent);
            }
        }
    }

    public function removeMobEvent(string $mobevent) : void{
        foreach($this->legalmobevents as $legalmobevent) {
            if ($mobevent == $legalmobevent){
                $index = array_search($mobevent, $this->mobevents);
                if($index !== false){
                    unset($this->mobevents[$index]);
                }
            }
        }
    }

    public function getImmutableWorlds(){
        return $this->immutable_worlds;
    }

    public function addImmutableWorld(string $level){
        if($this->getServer()->getLevelByName($level) instanceof Level){//might be a pointless check for peeps with IDE but still
            if(!in_array($level, $this->immutable_worlds)){
                array_push($this->immutable_worlds, $level);
            }
        }
    }

    public function removeImmutableWorld(string $level){
        if($this->getServer()->getLevelByName($level) instanceof Level){
            if(in_array($level, $this->immutable_worlds)){
                $index = array_search($level, $this->immutable_worlds);
                unset($this->immutable_worlds[$index]);
            }
        }
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

    public function chatWhenMuted(PlayerChatEvent $event){
        if($event->getPlayer()->hasPermission("vanillacommands.state") or $event->getPlayer()->hasPermission("vanillacommands.state.mute")){
            $event->setCancelled();
        }
    }

    public function placeWhenNotWorldBuilder(BlockPlaceEvent $event){
        if($event->getPlayer()->hasPermission("vanillacommands.state") or $event->getPlayer()->hasPermission("vanillacommands.state.worldbuilder")) {
            $event->setCancelled();
        }
    }

    public function breakWhenNotWorldBuilder(BlockBreakEvent $event){
        if($event->getPlayer()->hasPermission("vanillacommands.state") or $event->getPlayer()->hasPermission("vanillacommands.state.worldbuilder")) {
            $event->setCancelled();
        }
    }
    public function flyingWhenCantFly(PlayerMoveEvent $event){
        if($event->getPlayer()->hasPermission("vanillacommands.state") or $event->getPlayer()->hasPermission("vanillacommands.state.mayfly")) {
            if($event->getPlayer()->isFlying()){
                $event->getPlayer()->setFlying(false);
            }
        }
    }

    public function mobEvent(EntityEvent $event){
        if(in_array("events_enabled", $this->mobevents)) $event->setCancelled();
    }

    public function pillagerEvent(EntitySpawnEvent $event){
        if($event->getEntity()->getId() === EntityIds::VINDICATOR or $event->getEntity()->getId() === EntityIds::EVOCATION_ILLAGER){
            if(in_array("minecraft:pillager_patrols_event", $this->mobevents)) $event->setCancelled();
        }
    }

    public function traderEvent(EntitySpawnEvent $event){
        if($event->getEntity()->getNameTag() === "Wandering Trader"){//Wandering trader id ain't even implemented in PMMP, gotta use a name dependency until/for future PMMP updates.
            if(in_array("minecraft:wandering_trader_event", $this->mobevents)) $event->setCancelled();
        }
    }

    public function immutableBlockPlace(BlockPlaceEvent $event){
        if(in_array($event->getPlayer()->getLevel()->getName(), $this->immutable_worlds)){
            $event->setCancelled();
        }
    }

    public function immutableBlockBreak(BlockBreakEvent $event){
        if(in_array($event->getPlayer()->getLevel()->getName(), $this->immutable_worlds)){
            $event->setCancelled();
        }
    }
}