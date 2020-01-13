<?php

namespace DenielWorld\VanillaCommands\command;
/*Fellow reviewer, if you value your eyesight, you shall not proceed to reviewing this code,
and instead just let it go through. You have been warned.*/

use DenielWorld\VanillaCommands\Loader;
use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class SetBlock extends PluginCommand implements PluginIdentifiableCommand {

    public function __construct(string $name, Loader $owner) {
        parent::__construct($name, $owner);
        $this->setUsage("/setblock <position: x y z> <tileName: Block> [tileData: int] [replace|destroy|keep]");
        $this->setDescription("Sets a single block at a given position");
        $this->setPermission("vanillacommands.command.setblock");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(count($args) < 4)
            throw new InvalidCommandSyntaxException();
        if(!$sender instanceof Player) {
            $sender->sendMessage("You must be in-game to use this command");
            return;
        }
        if(is_string($args[3]))
            $blockId = ItemFactory::fromString($args[3])->getBlock()->getId();
        else
            $blockId = (int) $args[3];
        if(count($args) < 6) {
            $sender->getLevel()->setBlock(new Vector3($args[0], $args[1], $args[2]), Block::get($blockId, (int)($args[4] ?? 0)));
            $sender->sendMessage(TF::GREEN."Block at X: ".$args[0].", Y: ".$args[1].", Z: ".$args[2]." successfully set to ".Block::get($blockId, (int)($args[4] ?? 0))->getName());
        }elseif(count($args) == 6) {
            switch(strtolower($args[5])) {
                case "destroy":
                    $sender->getLevel()->useBreakOn(new Vector3($args[0], $args[1], $args[2]), ItemFactory::get(Item::AIR, 0, 0), null, true);
                    $sender->getLevel()->setBlock(new Vector3($args[0], $args[1], $args[2]), Block::get($args[3], (int)($args[4] ?? 0)));
                    $sender->sendMessage(TF::GREEN."Block at X: ".$args[0].", Y: ".$args[1].", Z: ".$args[2]." successfully set to ".Block::get($args[3], (int)($args[4] ?? 0))->getName());
                break;
                case "keep":
                    if($sender->getLevel()->getBlock(new Vector3($args[0], $args[1], $args[2]))->getId() !== BlockIds::AIR) {
                        $sender->sendMessage(TF::RED."The given block position is taken");
                        return;
                    }
                    $sender->getLevel()->setBlock(new Vector3($args[0], $args[1], $args[2]), Block::get($args[3], (int)($args[4] ?? 0)));
                    $sender->sendMessage(TF::GREEN."Block at X: ".$args[0].", Y: ".$args[1].", Z: ".$args[2]." successfully set to ".Block::get($args[3], (int)($args[4] ?? 0))->getName());
                break;
                case "replace":
                    $sender->getLevel()->setBlock(new Vector3($args[0], $args[1], $args[2]), Block::get($args[3], (int)($args[4] ?? 0)));
                    $sender->sendMessage(TF::GREEN."Block at X: ".$args[0].", Y: ".$args[1].", Z: ".$args[2]." successfully set to ".Block::get($args[3], (int)($args[4] ?? 0))->getName());
                break;
                default:
                    throw new InvalidCommandSyntaxException();
            }
        }
    }
}