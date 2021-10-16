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
use VkLib\Exception\VkMethodException;
use VkLib\Message\Keyboard;
use VkLib\Method\VkMethod;

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
     * @param string|VkClient $client
     */
    public function __construct(int $gid, $client = VkClient::DEFAULT_CLIENT) {
        parent::__construct($client);
        $this->gid = $gid;
    }
    
    /**
     * 
     * @since 0.7.1
     * @return int
     */
    public function getGroupId(): int {
        return $this->gid;
    }
    
    /**
     * 
     * @param bool $handle
     */
    public function setHandling(bool $handle = true): self {
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
    public function isHandling(): bool {
        return $this->payloadHandling[0];
    }
    
    /**
     * 
     * @throws VkMethodException
     * @param string $v
     */
    public function setApiVersion(string $v): void {
        $this->setSettings([
            "api_version" => $v
        ]);
    }
    
    /**
     * 
     * @throws VkMethodException
     * @return string|null
     */
    public function getApiVersion(): ?string {
        return $this->getSettings()["api_version"] ?? null;
    }
    
    /**
     * 
     * @throws VkMethodException
     * @param bool $v
     */
    public function setEnabled(bool $v): void {
        $this->setSettings([
            "enabled" => $v
        ]);
    }
    /**
     * 
     * @throws VkMethodException
     * @return bool
     */
    public function isEnabled(): bool {
        return $this->getSettings()["is_enabled"] ?? false;
    }
    
    /**
     * 
     * @throws VkMethodException
     * @param (int|string)[] $settings
     */
    public function setSettings(array $settings): void {
        $settings["group_id"] = $this->gid;
        $this->getClient()->getApi()->groups->setLongPollSettings($settings);
    }
    /**
     * 
     * @throws VkMethodException
     * @return (bool|array|string)[]
     */
    public function getSettings(): array {
        return $this->getClient()->getApi()->groups->getLongPollSettings([
            "group_id" => $this->gid
        ])->json();
    }
    
    /**
     *
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
     * @return array
     */
    public function getEvents(): array {
        return VkMethod::JSON($this->getClient()->getHttpClient()->post($this->server, ["form_params" => [
            "act" => "a_check",
            "wait" => $this->wait,
            "key" => $this->key,
            "ts" => $this->ts,
        ]]));
    }
}
