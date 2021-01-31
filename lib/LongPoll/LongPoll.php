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

use Triggers;

abstract class LongPoll {
    
    /**
     * 
     * @var VkClient
     */
    protected $client;
    
    public const FAILED_TS_DEPRECATED = 1;
    public const FAILED_KEY_EXPIRED = 2;
    public const FAILED_SESSION_LOST = 3;
    public const FAILED_INVALID_VERSION = 4;
    
    public const WAIT_MAX = 90;
    public const WAIT_DEF = 25;
    
    /**
     * 
     * @var Triggers
     */
    public $handler = null;
    
    /**
     *
     * @var int
     */
    public $wait = self::WAIT_DEF;
    
    /**
     *
     * @var string $server
     * @var string $key
     * @var int $ts
     */
    protected $server = null, $key = null, $ts = null;
    
    /**
     *
     * @var bool
     */
    protected $listen = false;
    
    /**
     * 
     * @param $client
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT) {
        $this->client = VkClient::checkClient($client);
    }
    
    /**
     *
     * @return array
     */
    public abstract function getServer(): array;

    /**
     *
     * @return array
     */
    public abstract function getEvents(): array;

    /**
     *
     * @param bool $ts
     * @return self
     */
    public function connect(bool $ts = true) {
        $res = $this->getServer();
        $this->server = $res["server"];
        $this->key = $res["key"];
        if ($ts) {
            $this->ts = $res["ts"];
        }
        return $this;
    }

    public function start() {
        $this->listen = true;
        $this->getHandler()->handle("onStart");
        while ($this->listen) {
            $get = $this->getEvents();
            $this->getHandler()->handle("onListen", [$get]);
            if (isset($get["failed"])) {
                $this->getHandler()->handle("onFailed", [$get]);
                switch ($get["failed"]) {
                    case self::FAILED_TS_DEPRECATED:
                        $this->ts = $get["ts"];
                        break;
                    case self::FAILED_KEY_EXPIRED:
                        $this->connect(false);
                        break;
                    case self::FAILED_SESSION_LOST:
                        $this->connect();
                        break;
                }
                continue;
            }
            $this->ts = $get["ts"];
            $events = $get["updates"];
            foreach ($events as $event) {
                $this->getHandler()->handle($event["type"], [$event["object"]]);
            }
        }
        $this->getHandler()->handle("onStop");
    }

    /**
     *
     * @return self
     */
    public function stop() {
        $this->listen = false;
        return $this;
    }

    /**
     *
     * @return bool
     */
    public function isListen() {
        return $this->listen;
    }

    /**
     *
     * @param int $wait
     * @return self
     */
    public final function setWait(int $wait) {
        $this->wait = $wait;
        return $this;
    }

    /**
     *
     * @return int
     */
    public final function getWait() {
        return $this->wait;
    }
    
    /**
     * 
     * @return VkClient
     */
    public function getClient() {
        return $this->client;
    }
    
    /**
     * 
     * @return Triggers
     */
    public function getHandler() {
        if (!($this->handler instanceof Triggers)) {
            $this->handler = new Triggers();
        }
        return $this->handler;
    }
}