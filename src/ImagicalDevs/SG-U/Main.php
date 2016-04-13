<?php

namespace ImagicalDevs\SG-U;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\level\{Level,Position};
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\tile\Sign;
use pocketmine\item\Item;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\entity\Effect;
use pocketmine\event\entity\EntityLevelChangeEvent; 
use pocketmine\tile\Chest;
use pocketmine\inventory\ChestInventory;
use pocketmine\event\plugin\PluginEvent;

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
    
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new SurvivalGamesGame($this), 20);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new SignChangeEvent($this), 10);
		
		foreach($this->config->get("arenas") as $a){
		  $this->getServer()->loadLevel($a);
		}
  }
      public function playerBlockTouch(PlayerInteractEvent $event){
        if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
            $sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
            if(!($sign instanceof Sign)){
                return;
            }
            $sign = $sign->getText();
            $p = $event->getPlayer();
            $l = $p->getLevel();
            $b = $l->getBlock();
            if($sign[0]=='[SGU]'){
                if($sign[1]== "join"){
                
                    $p->sendMessage($this->getConfig()->get("join_msg"));
      //code will go here to join a match.
                 }
               }
    # Continuation of Spawn Saving
    if($this->spawn >= 1 && $this->spawn <= 24){
      $sg = $this->data;
      $sg->set($l . "Spawn" . $this->mode, array($b->getX(),$b->getY()+1,$b->getZ()));
      $p->sendMessage(C::GREEN."Spawn $this->mode Added!");
      $this->data++;
    }
         }
      }
public function onCommand(CommandSender $s, Command $cmd, $label, array $args){
    if(strtolower($cmd->getName() == "sgu")){
      if($s instanceof Player){
        if(!isset($args[0])){
          $s->sendMessage(C::RED."/sgu make <world name>");
        }else{
          if($args[0] == "make"){
            if(!isset($args[1])){
              $s->sendMessage(C::RED."/sgu make <world name>");
            }else{
              $world = $args[1];
              if($world instanceof Level){
                
                if(!$this->config->exists("arenas")){
                  $this->config->set("arenas", array($world));
                }else{
                  array_push($this->config->get("arenas",$args[1]));
                }
                
                $this->getServer()->loadLevel($world);
								$this->getServer()->getLevelByName($world)->loadChunk($this->getServer()->getLevelByName($world)->getSafeSpawn()->getFloorX(), $this->getServer()->getLevelByName($world)->getSafeSpawn()->getFloorZ());
								$s->teleport($this->getServer()->getLevelByName($world)->getSafeSpawn());
								$this->spawn = 1;
								$s->sendMessage(C::BLUE."[SG-U] Get Ready To Create Dat SG-U Arena!")
              }else{
                $s->sendMessage(C::RED."Dude...Thats Not A Level!");
              }
            }
          }
        }
      }else{
        $s->sendMessage(C::BLUE."Um...Ur Not A PLAYER");
      }
    }
    return true;
  }
class SignChangeEvent 
