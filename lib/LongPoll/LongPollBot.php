<?php

/**
 * __     ___    _     _ _     
 * \ \   / / | _| |   (_) |__  
 *  \ \ / /| |/ / |   | | '_ \ 
 *   \ V / |   <| |___| | |_) |
 *    \_/  |_|\_\_____|_|_.__/ 
 *
 * VkLib - library for simplified work with VK-APIf
 *
 * More about VK-API {@link https://vk.com/dev/first_guide}
 * Project Homepage {@link https://github.com/Rollylni/VkLib}
 *
 * @copyright 2019-2020 Rollylni
 * @author Faruch N. <rollyllni@gmail.com>
 * @version 0.7 beta
 * @license MIT
 */
namespace VkLib\LongPoll;

use VkLib\VkClient;
use VkLib\Exception\HttpRequestException;
use VkLib\Exception\VkMethodException;
use VkLib\Message\Keyboard;

/**
 * 
 * @link https://vk.com/dev/bots_longpoll
 */
class LongPollBot extends LongPoll {
    
    /**
     * 
     * @var int
     */
    protected $gid;
    
    /**
     * 
     * @var bool[]
     */
    protected $payloadHandling = [false, false];
    
    /**
     * 
     * @param int $gid
     * @param $client
     */
    public function __construct($gid, $client = VkClient::DEFAULT_CLIENT) {
        parent::__construct($client);
        $this->gid = $gid;
    }
    
    /**
     * 
     * @param bool $handle
     */
    public function setHandling(bool $handle = true) {
        $this->payloadHandling[0] = $handle;
        if (!$this->payloadHandling[1] && $handle) {
            Keyboard::setLongPoll($this);
            $this->payloadHandling[1] = true;
        }
        return $this;
    }
    
    /**
     * 
     * @return bool
     */
    public function isHandling() {
        return $this->payloadHandling[0];
    }
    
    /**
     * 
     * @throws HttpRequestException
     * @throws VkMethodException
     * @param string|float $v
     */
    public function setApiVersion($v) {
        $this->setSettings([
            "api_version" => $v
        ]);
    }
    
    /**
     * 
     * @throws HttpException
     * @throws VkMethodException
     * @return string|null
     */
    public function getApiVersion() {
        return $this->getSettings()["api_version"] ?? null;
    }
    
    /**
     * 
     * @throws HttpRequestException
     * @throws VkMethodException
     * @param bool $v
     */
    public function setEnabled(bool $v) {
        $this->setSettings([
            "enabled" => intval($v)
        ]);
    }
    /**
     * 
     * @throws HttpException
     * @throws VkMethodException
     * @return bool
     */
    public function isEnabled() {
        return $this->getSettings()["is_enabled"] ?? false;
    }
    
    /**
     * 
     * @throws HttpRequestException
     * @throws VkMethodException
     * @param (int|string)[] $settings
     */
    public function setSettings($settings) {
        $settings["group_id"] = $this->gid;
        $this->getClient()->getApi()->groups->setLongPollSettings($settings);
    }
    /**
     * 
     * @throws HttpRequestException
     * @throws VkMethodException
     * @return (bool|array|string)[]
     */
    public function getSettings() {
        return $this->getClient()->getApi()->groups->getLongPollSettings([
            "group_id" => $this->gid
        ])->json();
    }
    
    /**
     *
     * @throws HttpRequestException
     * @throws VkMethodException
     * @return array
     */
    public function getServer(): array {
        return $this->getClient()->getApi()->groups->getLongPollServer([
            "group_id" => $this->gid
        ])->json();
    }
    
    /**
     * 
     * @throws HttpRequestException
     * @return array
     */
    public function getEvents(): array {
        return $this->getClient()->getHttpClient()->postRequest($this->server, ["form_params" => [
            "act" => "a_check",
            "wait" => $this->wait,
            "key" => $this->key,
            "ts" => $this->ts,
        ]]);
    }
}