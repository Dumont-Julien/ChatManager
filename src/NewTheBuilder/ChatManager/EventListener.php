<?php

namespace NewTheBuilder\ChatManager;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class EventListener implements Listener {

    public function PlayerChatEvent(PlayerChatEvent $event){

        $sender = $event->getPlayer();
        $config = Main::getConfigFile();

        if ($config->get("Chat-Status") === "disable") {
            if (!$sender->hasPermission("chatmanager.bypass")){
                $event->cancel();
                $sender->sendMessage($config->get("Prefix") . $config->get("Disable_Chat"));
            }
        }
    }

}