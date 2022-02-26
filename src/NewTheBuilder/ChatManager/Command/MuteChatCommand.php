<?php

namespace NewTheBuilder\ChatManager\Command;

use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use JsonException;
use NewTheBuilder\ChatManager\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class MuteChatCommand extends Command {

    public function __construct(){
        parent::__construct("chat", "Enable/Disable chat", "/mutechat");
        $this->setPermission("chatmanager.command.chat");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool|void
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        $config = Main::getConfigFile();

        if (!$sender->hasPermission("chatmanager.command.chat")){
            $sender->sendMessage($config->get("Prefix") . $config->get("NoPermission"));
            return true;
        }

        if ($config->get("Chat-Status") === "enable"){
            $config->set("Chat-Status", "disable");
            $config->save();
            Server::getInstance()->broadcastMessage($config->get("Prefix") . str_replace("{PLAYER}", $sender->getName(), $config->get("Broadcast_Disable_Chat")));
            $message = new Message();
            $url = new Webhook($config->get("api-webhook"));
            $embed = new Embed();
            $embed->setTitle($config->get("title_1"));
            $embed->setDescription(str_replace("{PLAYER}", $sender->getName(), $config->get("description_1")));
            $embed->setFooter($config->get("footer_1"));
        }else{
            $config->set("Chat-Status", "enable");
            $config->save();
            Server::getInstance()->broadcastMessage($config->get("Prefix") . str_replace("{PLAYER}", $sender->getName(), $config->get("Broadcast_Enable_Chat")));
            $message = new Message();
            $url = new Webhook($config->get("api-webhook"));
            $embed = new Embed();
            $embed->setTitle($config->get("title_2"));
            $embed->setDescription(str_replace("{PLAYER}", $sender->getName(), $config->get("description_2")));
            $embed->setFooter($config->get("footer_2"));
        }
        $message->addEmbed($embed);
        $url->send($message);
    }
}