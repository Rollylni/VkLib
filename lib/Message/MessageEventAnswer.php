<?php

/**
 * __     ___    _     _ _     
 * \ \   / / | _| |   (_) |__  
 *  \ \ / /| |/ / |   | | '_ \ 
 *   \ V / |   <| |___| | |_) |
 *    \_/  |_|\_\_____|_|_.__/ 
 *
 * VkLib - library for simplified work with VK-API
 *
 * More about VK-API {@link https://vk.com/dev/first_guide}
 * Project Homepage {@link https://github.com/Rollylni/VkLib}
 *
 * @copyright 2019-2020 Rollylni
 * @author Faruch N. <rollyllni@gmail.com>
 * @version 0.7 beta
 * @license MIT
 */
namespace VkLib\Message;

use VkLib\LongPoll\LongPollBot;

use function json_encode;

/**
 * 
 * @link https://vk.com/dev/messages.sendMessageEventAnswer
 * @link https://vk.com/dev/bots_docs_5?f=4.4.%2BCallback-%D0%BA%D0%BD%D0%BE%D0%BF%D0%BA%D0%B8
 */
class MessageEventAnswer {
    
    public const TYPE_SHOW_SNACKBAR = "show_snackbar";
    public const TYPE_OPEN_LINK = "open_link";
    public const TYPE_OPEN_APP = "open_app";
    
    /**
     * 
     * @var LongPollBot
     */
    private $lp;
    
    /**
     * 
     * @var array
     */
    private $object;
    
    /**
     * 
     * @param LongPollBot $lp
     * @param array $obj
     */
    public function __construct(LongPollBot $lp, array $obj) {
        $this->lp = $lp;
        $this->object = $obj;
    }
    
    /**
     * 
     * @param string $link url
     */
    public function openLink($link) {
        $this->sendAnswer([
            "type" => self::TYPE_OPEN_LINK,
            "link" => $link
        ]);
    }
    
    /**
     * 
     * @param string $text Max 90 symbols
     */
    public function showSnackbar($text) {
        $this->sendAnswer([
            "type" => self::TYPE_SHOW_SNACKBAR,
            "text" => $text
        ]);
    }
    
    /**
     * 
     * @param int $appId
     * @param string $hash
     * @param int $ownerId
     */
    public function openApp($appId, $hash, $ownerId = null) {
        $this->sendAnswer([
            "type" => self::TYPE_OPEN_APP,
            "app_id" => $appId,
            "owner_id" => $ownerId,
            "hash" => $hash
        ]);
    }
    
    /**
     * 
     * @return array $data
     */
    public function sendAnswer(array $data) {
        $this->getLp()->getClient()->getApi()->messages->sendMessageEventAnswer([
            "event_id" => $this->getEventId(),
            "user_id" => $this->getUserId(),
            "peer_id" => $this->getPeerId(),
            "event_data" => (string) json_encode($data)
        ]);
    }
    
    /**
     * 
     * @return array|null
     */
    public function getPayload() {
        return $this->object["paylaod"] ?? null;
    }


    /**
     * 
     * @return string|null
     */
    public function getEventId() {
        return $this->object["event_id"] ?? null;
    }
    
    /**
     * 
     * @return int|null
     */
    public function getPeerId() {
        return $this->object["peer_id"] ?? null;
    }
    
    /**
     * 
     * @return int|null
     */
    public function getUserId() {
        return $this->object["user_id"] ?? null;
    }
    
    /**
     * 
     * @return int|null
     */
    public function getMessageId() {
        return $this->object["conversation_message_id"] ?? null;
    }
    
    /**
     * 
     * @return LongPollBot
     */
    public function getLp(): LongPollBot {
        return $this->lp;
    }
    
    /**
     * 
     * @return array
     */
    public function getObject(): array {
        return $this->object;
    }
}