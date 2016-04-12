<?php

namespace ImagicalDevs\SG-U;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\{Command,CommandSender};
use pocketmine\math\Vector3;
use pocketmine\level\{Level,Position};
use pocketmine\tile\Tile;
use pocketmine\tile\Chest;
use pocketmine\tile\Sign;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;

class Main extends PluginBase implements Listener {
  
  public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    $this->getLogger()->info(C::BLUE."SG-U Enabled!");
    $this->saveResource("messages.yml");
    $this->saveResource("config.yml");
    $this->saveResource("sgdata.yml");
    
    @mkdir($this->getDataFolder());
    $this->msg = new Config($this->getDataFolder(). "messages.yml", Config::YAML);
    $this->config = new Config($this->getDataFolder(). "config.yml", Config::YAML);
    $this->data = new Config($this->getDataFolder(). "sgdata.yml", Config::YAML);
  }
  
  public function onCommand(CommandSender $s, Command $cmd, $label, array $args){
    switch(strtolower($cmd->getName()){
      case "":
      break;
    }
  }
}
