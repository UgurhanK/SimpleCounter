<?php

namespace Sayac;

use pocketmine\plugin\PluginBase;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use pocketmine\event\Listener;

use pocketmine\command\{Command, CommandSender};

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\event\player\PlayerQuitEvent;

class Main extends PluginBase implements Listener{

    public $cfg;

    public $cfg2;

   // public $afkcfg;

    public $prefix = "§f[§6SUNUCU§f]"." §f";

    public function onEnable(): void{

        $this->getServer()->getPluginManager()->registerEvents($this,$this);

        @mkdir($this->getDataFolder());

        $this->cfg = new Config($this->getDataFolder()."config.yml",Config::YAML);

        $this->cfg2 = new Config($this->getDataFolder()."toplamlar.yml",Config::YAML);

     //   $this->afkcfg = new Config($this->getDataFolder()."afkcfg.yml",Config::YAML);

    }

    /*public function setAfk(Player $o, bool $bool){

        if($bool == true){

        $array = ["ilk" => time(), "cikis" => 0];

        $this->afkcfg->set($o->getName()."Zaman",$array);

        $this->afkcfg->set($o->getName()."Saniyesi", 0);

        $this->afkcfg->set($o->getName(), "AFK");

        $this->afkcfg->save();

        } else {

        $array = ["ilk" => $this->afkcfg->get($o->getName()."Zaman")["ilk"], "cikis" => time()];

        $this->afkcfg->set($o->getName()."Saniyesi", time() - $this->afkcfg->get($o->getName()."Zaman")["ilk"]);

        $this->afkcfg->set($o->getName()."Zaman",$array);

        $this->afkcfg->set($o->getName(), "notAFK");

        $this->afkcfg->save();

        }

    }*/

    public function onCommand(CommandSender $o, Command $cmd, string $label, array $args): bool{

        if($cmd == "surem"){

            $int = $this->cfg2->get($o->getName());

            $int += time() -$this->cfg->get($o->getName())["giris"];

            $o->sendMessage($this->prefix."§f- §6Süreniz: §f".$int."§6 saniye");

            $int = 0;

        }

        if($cmd->getName() == "sureliste"){

            $array = $this->cfg2->getAll();

            arsort($array);

            $names = array_keys($array);

            $int = $this->cfg2->get($o->getName());

            $int += time() -$this->cfg->get($o->getName())["giris"];

            $o->sendMessage($this->prefix ."§f- §6Süre Listesi:");

            foreach ($names as $name){

                if($name !== null){

                    $o->sendMessage("§d- §e".$name." §c- §6".$this->cfg2->get($name));

                }

            }

           // $zamanim = $int;

           /* if($zamanim >= 3600){

            $saatim= intval($zamanim/3600);

            $dakika= intval(($zamanim-($saatim * 3600)) /60);

            $saniye = intval($zamanim - (($saatim *3600) + ($dakika*60)));

            $o->sendMessage($saatim." Saat :".$dakika." Dakika:".$saniye." Saniye");

            } else {

                $dakika= intval($zamanim /60);

            $saniye = intval($zamanim - ($dakika*60));

            $o->sendMessage($dakika." Dakika:".$saniye." Saniye");

            }*/

            //$o->sendMessage($this->prefix ."§f- §6Süre Listesi İlk 10: \n§d1- §e".$names[0]." §c- §6".$this->cfg2->get($names[0])."\n§d2- §e".$names[1] ."§c - §6".$this->cfg2->get($names[1])."\n§d3 §e$names[2] §c- §6".$this->cfg2->get($names[2])."\n§d4- §e$names[3] §c- §6".$this->cfg2->get($names[3])."\n§d5-§e $names[4] §c-§6 ".$this->cfg2->get($names[4])."\n§d6-§e $names[5] §c-§6 ".$this->cfg2->get($names[5])."\n§d7-§e $names[6] §c-§6 ".$this->cfg2->get($names[6])."\n§d8-§e $names[7] §c-§6 ".$this->cfg2->get($names[7])."\n§d8-§e $names[7] §c-§6 ".$this->cfg2->get($names[8])."\n§d9-§e $names[8] §c-§6 ".$this->cfg2->get($names[8])."\n§d10-§e $names[9] §c-§6 ".$this->cfg2->get($names[9]));

        }

        if($cmd == "suresifirla"){

            if($this->getServer()->isOp($o->getName())){

                $array = [];

                 $this->cfg->setAll($array);

                 $this->cfg2->setAll($array);

                

                foreach($this->getServer()->getOnlinePlayers() as $player){

                    $array = ["giris" => time(), "cikis" => 0];

                    $this->cfg->set($player->getName(), $array);

                    $this->cfg2->set($player->getName(), 0);

                    $this->cfg->save();

                    $this->cfg2->save();

                    

                }

                $this->cfg->save();

                $this->cfg2->save();

                $o->sendMessage("§a»§f Süreler sıfırlandı!");

            } else {

                $o->sendMessage("§c»§f Yeterli yetkiniz yok!");

            }

        }

        

        return true;

    }

    public function giris(PlayerJoinEvent $e){

        $o = $e->getPlayer();

        $zaman = time();

        //$this->afkcfg->set($o->getName()."Saniyesi", 0);

        $array = ["giris" => $zaman, "cikis" => 0];

        $this->cfg->set($o->getName(),$array);

       // $this->afkcfg->save();

        $this->cfg->save();

    }

    public function cikis(PlayerQuitEvent $e){

        $o = $e->getPlayer();

        $zaman = time();

        

        $array = $this->cfg->get($o->getName());

        $array["cikis"] = $zaman;

        $this->cfg->set($o->getName(), $array);

        $int = $this->cfg2->get($o->getName());

        $int += $zaman - $this->cfg->get($o->getName())["giris"];

   

            $this->cfg2->set($o->getName(), $int);

        

        $this->cfg->save();

        $this->cfg2->save();

    }

    public function getSaat(string $o){

        $int = $this->cfg2->get($o);

         $zamanim = $int;

            if($zamanim >= 3600){

            $saatim= intval($zamanim/3600);

            $dakika= intval(($zamanim-($saatim * 3600)) /60);

            $saniye = intval($zamanim - (($saatim *3600) + ($dakika*60)));

            return $saatim.":".$dakika.":".$saniye;

            } else {

                $dakika= intval($zamanim /60);

            $saniye = intval($zamanim - ($dakika*60));

            return "0:".$dakika.":".$saniye;

            }

    }

}
