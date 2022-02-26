<?php

namespace NewTheBuilder\ChatManager;

use NewTheBuilder\ChatManager\Command\ClearChatCommand;
use NewTheBuilder\ChatManager\Command\MuteChatCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private static Main $main;

    protected function onEnable(): void {
        //Listener
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        //Command
        $this->getServer()->getCommandMap()->register("ChatManager", new MuteChatCommand());
        $this->getServer()->getCommandMap()->register("ChatManager", new ClearChatCommand());
        //Config
        if (!file_exists($this->getDataFolder() . "Config.yml")){
            $this->saveResource("Config.yml");
        }
        //API
        self::$main = $this;
    }

    public static function getInstance() : Main {
        return self::$main;
    }

    public static function getConfigFile() : Config{
        return new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);
    }

}