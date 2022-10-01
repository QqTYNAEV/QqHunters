<?php

namespace QqHunters;

/*
*            
*            
*  ____  ____ 
*  / __ \ / __ \ 
* | | | | | | | |
* | | | | | | | |
* | |__| | | |__| |
*  \___\_\ \___\_\
*     
*     
*
*	QqHunters плагин позволяющий устроить охоту всего сервера на одного игрока
*    Для Майнкрафта ПЕ версии 1.1.5, Версия плагина: 0.9 (BETA 2)
*	
*	
*	
*	Плагин от ГКОДЕРА
*	
*	VK ➡ @qq_tynaev
*	GitHub ➡ QqTYNAEV
*	Хз что дальше прикрепить
*	
*	Удачного использования
*/




use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\math\Vector3;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\entity\Effect;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\scheduler\CallbackTask;
use pocketmine\level\Level;



class Main extends PluginBase implements Listener{
	
	
	
	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		@mkdir($this->getDataFolder());
		$this->saveResource("messages.yml");
		$this->config = new Config($this->getDataFolder()."messages.yml", Config::YAML);
		$this->saveResource("settings_pl.yml");
		$this->settings = new Config($this->getDataFolder()."settings_pl.yml", Config::YAML);
		@mkdir($this->getDataFolder()."database/");
		$this->tpyn = new Config($this->getDataFolder()."database/tpyn.yml", Config::YAML);
		$this->tpyn->set("Jertva", array("tpyn" => "§lHe_urpoBou_urpok§r", "award" => 0, "by" => "Qq", "mode" => "classic"));
		$this->tpyn->save();
		if(!$this->eco){
			$this->getLogger()->critical("
				§c   ║§e ¯\_ಠ_ಠ_/¯
				§c   ║§a Плагин §bEconomyAPI§a не найден
				§c   ║§a Установи его и только тогда я буду работать");
			$this->getServer()->getPluginManager()->disablePlugin($this); #Вырубить плагин
		}
		if(!$this->settings->get("info") == true){
			$this->getLogger()->info("§c   ║§f Регулярное объявление выключено, игрокам будет труднее охотится на разыскиваемого§a (Я предупредил)");
		}
	}
	
	
	public function onDisable(){
		$this->tpyn->set("Jertva", array("tpyn" => "§lHe_urpoBou_urpok§r", "award" => 0, "by" => "Qq", "mode" => "classic"));
		$this->tpyn->save();
	}
	
	
	public function onQuit(PlayerQuitEvent $event){
		$victim = $event->getPlayer()->getName();
		if($this->tpyn->get("Jertva")["mode"] == "extra" || $this->tpyn->get("Jertva")["mode"] == "hard" || $this->tpyn->get("Jertva")["mode"] == "demon"){
			if($event->getPlayer()->getName() == $this->tpyn->get("Jertva")["tpyn"]){
				$player = $event->getPlayer();
				foreach($this->getServer()->getOnlinePlayers() as $all){
					$title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("leave.title"));
		            $subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("leave.subtitle"));
		            $msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("leave.msg"));
					$all->sendTitle($title, $subtitle, 20, 60, 20);
				    $all->sendMessage($msg);
				}
				foreach($player->getInventory()->getContents() as $drop){
				    $player->getLevel()->dropItem($player, $drop);
				}
				$player->getInventory()->clearAll();
				$this->tpyn->set("Jertva", array("tpyn" => "§lHe_urpoBou_urpok§r", "award" => 0, "by" => "Qq", "mode" => "classic"));
		        $this->tpyn->save();
			}
		}
		if($this->tpyn->get("Jertva")["mode"] == "classic"){
			if($this->tpyn->get("Jertva")["tpyn"] == "§lHe_urpoBou_urpok§r"){ #Пробовал с ! но не сработало
			}else{
				$name = $event->getPlayer()->getName();
				foreach($this->getServer()->getOnlinePlayers() as $all){
					$title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("leave.title"));
	                $subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("leave.subtitle"));
		            $msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("leave.msg"));
			        $all->sendTitle($title, $subtitle, 20, 60, 20);
			        $all->sendMessage($msg);
			    }
			    $this->tpyn->set("Jertva", array("tpyn" => "§lHe_urpoBou_urpok§r", "award" => 0, "by" => "Qq", "mode" => "classic"));
		        $this->tpyn->save();
		    }
		}
	}
	
	
	public function onInteract(PlayerInteractEvent $event){
		if($this->tpyn->get("Jertva")["mode"] == "extra" || $this->tpyn->get("Jertva")["mode"] == "hard" || $this->tpyn->get("Jertva")["mode"] == "demon"){
			if($event->getPlayer()->getName() == $this->tpyn->get("Jertva")["tpyn"]){
				$block = $event->getBlock()->getId();
				if($block == 54 || $block == 218 || $block == 130 || $block == 146){
					$event->setCancelled(true);
					$event->getPlayer()->sendTitle("", "§c§lНЕЛЬЗЯ!");
				}
			}
		}
	}
	
	
	public function onConsume(PlayerItemConsumeEvent $event){
		if($this->tpyn->get("Jertva")["mode"] == "hard" || $this->tpyn->get("Jertva")["mode"] == "demon"){
			if($event->getPlayer()->getName() == $this->tpyn->get("Jertva")["tpyn"]){
				$id = $event->getItem()->getId();
				if($id == 322 || $id == 466 || $id == 432 || $id == 433){
					$event->setCancelled(true);
					$event->getPlayer()->sendTitle("", "§c§lНЕЛЬЗЯ!");
				}
			}
		}
	}
	
	
	public function hunter(){
		foreach($this->getServer()->getOnlinePlayers() as $all){
			$player = $this->getServer()->getPlayer($this->tpyn->get("Jertva")["tpyn"]);
			if($player){
				$x = $player->getFloorX();
				$y = $player->getFloorY();
				$z = $player->getFloorZ();
				$xyz = $x." ".$y." ".$z;
				$test_world = $player->getLevel()->getName();
				switch($test_world){
					case "world": $world = "§aВерхний мир";
					break;
					case "nether": $world = "§cНижний мир (Ад)";
					break;
					case "ender": $world = "§9Край (Энд)";
					break;
					default: $world = $test_word;
				}
				$all->sendMessage(str_replace(["{name}", "{xyz}", "{world}"], [$this->tpyn->get("Jertva")["tpyn"], $xyz, $world], $this->config->get("information")));
			}
		}
	}
	
	
	public function onDeath(PlayerDeathEvent $event){
		$dth = $event->getEntity();
		$victim = $dth->getName();
		$xyz = $dth->getFloorX() ." ". $dth->getFloorY() ." ". $dth->getFloorX();
		if($this->tpyn->get("Jertva")["tpyn"] == $dth->getName()){
		    $chek = $event->getEntity()->getLastDamageCause()->getCause();
		    if($chek == 1){
			    $killer = $event->getEntity()->getLastDamageCause()->getDamager();
			    if($killer instanceof Player){
				    $name = $killer->getName();
					$title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}", "{killer}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz, $killer], $this->config->get("stop.title"));
					$subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}", "{killer}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz, $killer], $this->config->get("stop.subtitle"));
					$msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}", "{killer}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz, $killer], $this->config->get("stop.msg"));
				    foreach($this->getServer()->getOnlinePlayers() as $all){
					    $all->sendTitle($title, $subtitle, 20, 60, 20);
						$all->sendMessage($msg);
					}
				}else{
					$title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("stop.e.title"));
					$subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("stop.e.subtitle"));
					$msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("stop.e.msg"));
					foreach($this->getServer()->getOnlinePlayers() as $all){
					    $all->sendTitle($title, $subtitle, 20, 60, 20);
					    $all->sendMessage($msg);
					}
				}
			}else{
				foreach($this->getServer()->getOnlinePlayers() as $all){
					$title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("stop.e.title"));
					$subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("stop.e.subtitle"));
					$msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$victim, $this->tpyn->get("Jertva")["mode"], $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("stop.e.msg"));
				    $all->sendTitle($title, $subtitle, 20, 60, 20);
					$all->sendMessage($msg);
				}
			}
			$this->tpyn->set("Jertva", array("tpyn" => "§lHe_urpoBou_urpok§r", "award" => 0, "by" => "Qq", "mode" => "classic"));
			$this->tpyn->save();
			$this->huntTask->remove();
		}
	}
	
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if($command->getName() == "hunter"){
			if($this->tpyn->get("Jertva")["tpyn"] == "§lHe_urpoBou_urpok§r"){ #Пробовал с ! но не сработало
			    if(count($args) <= 2){
				    $sender->sendMessage($this->config->get("cmd.error"));
			    }
			    if(count($args) >= 3){
				    $player = $this->getServer()->getPlayer($args[0]);
				    if(!$player){
					    $sender->sendMessage($this->config->get("cmd.NotPl"));
				    }else{
					    $name = $player->getName();
					    if($player->getGamemode() == 1){
						    $sender->sendMessage($this->config->get("cmd.plC"));
					    }
					    if($player->getGamemode() == 3){
						    $sender->sendMessage($this->config->get("cmd.plS"));
					    }
					    if($player->getGamemode() == 0 || $player->getGamemode() == 2){
						    if(!is_numeric($args[1])){
							    $sender->sendMessage($this->config->get("cmd.errorAward"));
						    }else{
							    switch($args[2]){
								    case "c":
								    case "classsic":
								    case "cl":
									    $this->tpyn->set("Jertva", array("tpyn" => $name, "award" => $args[1], "by" => $sender->getName(), "mode" => "classic"));
									    $this->tpyn->save();
									    $xyz = $player->getFloorX()." ".$player->getFloorY()." ".$player->getFloorZ();
									    $player->sendMessage(str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§aКлассический", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("victim.msg")));
									    foreach($this->getServer()->getOnlinePlayers() as $all){
											$title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§aКлассический", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.title"));
											$subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§aКлассический", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.subtitle"));
											$msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§aКлассический", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.msg"));
								            $all->sendTitle($title, $subtitle, 20, 60, 20);
								            $all->sendMessage($msg);
							            }
							           $sender->sendMessage($this->config->get("cmd.success"));
                                       $this->huntTask = $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array($this, "hunter")), 20 * $this->settings->get("timing"));
								    break;
								
								    case "e":
								    case "extra":
								    case "ext":
									    $this->tpyn->set("Jertva", array("tpyn" => $name, "award" => $args[1], "by" => $sender->getName(), "mode" => "extra"));
									    $this->tpyn->save();
									    $xyz = $player->getFloorX()." ".$player->getFloorY()." ".$player->getFloorZ();
									    $player->sendMessage(str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§6Экстра", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("victim.msg")));
									    $player->sendMessage("§c|||||§f §lЕсли выйдешь с сервера потеряешь весь свой инвентарь\n§c|||||§f §lТак же у тебя не будет возможности открывать сундуки, шалкера и другие блоки тому подобное");
									    foreach($this->getServer()->getOnlinePlayers() as $all){
								            $title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§6Экстра", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.title"));
											$subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§6Экстра", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.subtitle"));
											$msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§6Экстра", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.msg"));
								            $all->sendTitle($title, $subtitle, 20, 60, 20);
								            $all->sendMessage($msg);
							            }
							           $sender->sendMessage($this->config->get("cmd.success")); 
									   $this->huntTask = $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array($this, "hunter")), 20 * $this->settings->get("timing"));
								    break;
								
								    case "h":
								    case "hard":
								    case "hardcore":
								    case "hrd":
									    $this->tpyn->set("Jertva", array("tpyn" => $name, "award" => $args[1], "by" => $sender->getName(), "mode" => "hard"));
									    $this->tpyn->save();
									    $xyz = $player->getFloorX()." ".$player->getFloorY()." ".$player->getFloorZ();
									    $player->sendMessage(str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§cХардкор", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("victim.msg")));
									    $player->sendMessage("§c|||||§f §lЕсли выйдешь с сервера потеряешь весь свой инвентарь\n§c|||||§f §lУ тебя не будет возможности открывать сундуки, шалкера и другие блоки тому подобное\n§c|||||§f §lТак же ты не можешь есть Золотые яблоки, хорусы и тому подобное вещи");
									    foreach($this->getServer()->getOnlinePlayers() as $all){
								            $title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§cХардкор", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.title"));
											$subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§cХардкор", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.subtitle"));
											$msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§cХардкор", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.msg"));
								            $all->sendTitle($title, $subtitle, 20, 60, 20);
								            $all->sendMessage($msg);
							            }
							           $sender->sendMessage($this->config->get("cmd.success")); 
							           $this->huntTask = $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array($this, "hunter")), 20 * $this->settings->get("timing"));
								    break;
								
								    case "d":
								    case "demon":
								    case "dm":
									    $this->tpyn->set("Jertva", array("tpyn" => $name, "award" => $args[1], "by" => $sender->getName(), "mode" => "demon"));
									    $this->tpyn->save();
									    $xyz = $player->getFloorX()." ".$player->getFloorY()." ".$player->getFloorZ();
									    $player->sendMessage(str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§4Д§cЕ§4М§cО§4Н§cИ§4Ч§cЕ§4С§cК§4И§cЙ", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("victim.msg")));
									    $player->sendMessage("§c|||||§f §lЕсли выйдешь с сервера потеряешь весь свой инвентарь\n§c|||||§f §lУ тебя не будет возможности открывать сундуки, шалкера и другие блоки тому подобное\n§c|||||§f §lТы не можешь есть Золотые яблоки, хорусы и тому подобное вещи\n§c|||||§f §lУ тебя будет эффект замедления 2 + минус твой инвентарь + голод и подрез здоровья");
									    $player->addEffect(Effect::getEffect(2)->setAmplifier(1)->setDuration(20 * 99999));
									    $player->setHealth($player->getHealth() / 2);
									    $player->setFood(7);
									    $player->getInventory()->clearAll();
									    foreach($this->getServer()->getOnlinePlayers() as $all){
								            $title = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§4Д§cЕ§4М§cО§4Н§cИ§4Ч§cЕ§4С§cК§4И§cЙ", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.title"));
											$subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§4Д§cЕ§4М§cО§4Н§cИ§4Ч§cЕ§4С§cК§4И§cЙ", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.subtitle"));
											$msg = str_replace(["{name}", "{mode}", "{award}", "{by}", "{xyz}"], [$name, "§4Д§cЕ§4М§cО§4Н§cИ§4Ч§cЕ§4С§cК§4И§cЙ", $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"], $xyz], $this->config->get("start.msg"));
								            $all->sendTitle($title, $subtitle, 20, 60, 20);
								            $all->sendMessage($msg);
							            }
							           $sender->sendMessage($this->config->get("cmd.success"));
							           $this->huntTask = $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array($this, "hunter")), 20 * $this->settings->get("timing"));
								    break;
								
								    default: $sender->sendMessage("§c»§f Режима розыска не существует");
							    }
							}
						}
					}
				}
			}else{
				$sender->sendMessage($this->config->get("cmd.working"));
			}
		}
		if($command->getName() == "hunter-info"){
			if($this->tpyn->get("Jertva")["tpyn"] == "§lHe_urpoBou_urpok§r"){
				$sender->sendMessage("§c|§f Пока никто не разыскивается");
			}else{
				if($this->tpyn->get("Jertva")["mode"] == "classic"){
					$mode = "§aКлассический";
				}
				if($this->tpyn->get("Jertva")["mode"] == "extra"){
					$mode = "§6Экстра";
				}
				if($this->tpyn->get("Jertva")["mode"] == "hard"){
					$mode = "§cХардкор";
				}
				if($this->tpyn->get("Jertva")["mode"] == "demon"){
					$mode = "§4Д§cЕ§4М§cО§4Н§cИ§4Ч§cЕ§4С§cК§4И§cЙ";
				}
				$victim = $this->tpyn->get("Jertva")["tpyn"];
				$info = str_replace(["{name}", "{mode}", "{award}", "{by}"], [$victim, $mode, $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("cmd.info"));
				$sender->sendMessage($info);
			}
		}
		if($command->getName() == "stop-hunt"){
			$victim = $this->tpyn->get("Jertva")["tpyn"];
			if($this->tpyn->get("Jertva")["mode"] == "classic"){
				$mode = "§aКлассический";
			}
			if($this->tpyn->get("Jertva")["mode"] == "extra"){
				$mode = "§6Экстра";
			}
			if($this->tpyn->get("Jertva")["mode"] == "hard"){
				$mode = "§cХардкор";
			}
			if($this->tpyn->get("Jertva")["mode"] == "demon"){
				$mode = "§4Д§cЕ§4М§cО§4Н§cИ§4Ч§cЕ§4С§cК§4И§cЙ";
			}
			$sender->sendMessage($this->config->get("cmd.cancel"));
			foreach($this->getServer()->getOnlinePlayers() as $all){
				$title = str_replace(["{name}", "{mode}", "{award}", "{by}"], [$victim, $mode, $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("cancel.title"));
				$subtitle = str_replace(["{name}", "{mode}", "{award}", "{by}"], [$victim, $mode, $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("cancel.subtitle"));
				$msg = str_replace(["{name}", "{mode}", "{award}", "{by}"], [$victim, $mode, $this->tpyn->get("Jertva")["award"], $this->tpyn->get("Jertva")["by"]], $this->config->get("cancel.msg"));
				$all->sendTitle($title, $subtitle, 20, 60, 20);
				$all->sendMessage($msg);
			}
			$this->tpyn->set("Jertva", array("tpyn" => "§lHe_urpoBou_urpok§r", "award" => 0, "by" => "Qq", "mode" => "classic"));
			$this->tpyn->save();
			$this->huntTask->remove();
		}
	}
}