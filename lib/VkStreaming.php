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
namespace VkLib;

use VkLib\Exception\VkStreamingException;

use Workerman\Worker;
use Triggers;

use function strtotime;
use function method_exists;
use function json_decode;

class VkStreaming {
    
    public const STAT_RECEIVED = "received";
    public const STAT_PREPARED = "prepared";
    
    public const GET_RULES = "getRequest";
    public const ADD_RULES = "postRequest";
    public const DELETE_RULES = "deleteRequest";
            
    /**
     * 
     * @var Triggers
     */
    public $handler = null;
    
    /**
     * 
     * @var VkClient
     */
    private $client;
    
    /**
     * 
     * @var string[]
     */
    protected $server = [];
    
    /**
     * 
     * @param $client - Service Access Token
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT) {
        $this->client = VkClient::checkClient($client);
    }
    
    /**
     * 
     * @param array $ctx - SSL Context
     */
    public function start(array $ctx = []) {
        if (!$this->getEndpoint() || !$this->getKey()) {
            $this->authorization();
        }
        $worker = new Worker("websocket://".$this->getEndpoint()."/stream?key=".$this->getKey(), $ctx);
        $worker->transport = "ssl";
        $worker->onConnect = function($conn) {
            $this->getHandler()->handle("onConnect", [$conn]);
        };
        
        $worker->onError = function($conn) {
            $this->getHandler()->handle("onError", [$conn]);  
        };
        
        $worker->onClose = function($conn) {
            $this->getHandler()->handle("onClose", [$conn]);
        };
        
        $worker->onMessage = function($conn, $data) {
            $json = json_decode($data, true) ?? [];
            $this->getHandler()->handle("onMessage", [$conn, $json]);
            
            if (isset($json["service_message"])) {
                $this->getHandler()->handle("onServiceMessage", [$conn, $json["service_message"]]);
            } elseif (isset($json["event"])) {
                $this->getHandler()->handle("onEvent", [$conn, $json["event"]]);
            }
        };
        $worker->runAll();
    }
    
    /**
     * 
     * @return string[][]
     */
    public function getRules() {
        return $this->request(self::GET_RULES)["rules"] ?? [];
    }
    
    /**
     * 
     * @param string $tag
     * @param string $value
     */
    public function addRule($tag, $value) {
        $this->request(self::ADD_RULES, [
            "rule" => [
                "value" => $value,
                "tag" => $tag
            ]
        ]);
        return $this;
    }
    
    /**
     * 
     * @param string $tag
     * @return self
     */
    public function deleteRule($tag) {
        $this->request(self::DELETE_RULES, [
            "tag" => $tag
        ]);
        return $this;
    }
    
    /**
     * 
     * @param string $method
     * @param array $params
     * @param bool $throws
     * @throws VkStreamingException
     * @return array
     */
    public function request($method, array $params = [], bool $throws = true) {
        $http = $this->getClient()->getHttpClient();
        if (!method_exists($http, $method)) {
            return [];
        }
        
        $url = "https://".$this->getEndpoint()."/rules?key=".$this->getKey();
        $res = $http->{$method}($url, [
            "json" => $params
        ]);
        
        if (isset($res["error"]) && $throws) {
            throw new VkStreamingException($res["error"]["message"], $res["error"]["error_code"]);
        }  
        return $res;
    }    
    
    /**
     * 
     * @return self
     */
    public function authorization() {
        $this->server = $this->getClient()->getApi()->streaming->getServerUrl()->json();
        return $this;
    }
    
    /**
     * 
     * @return string[]
     */
    public function getSettings() {
        return $this->getClient()->getApi()->streaming->getSettings()->json();
    }
    
    /**
     * 
     * @param string[] $params
     * @return self
     */
    public function setSettings(array $params) {
        $this->getClient()->getApi()->streaming->setSettings($params);
        return $this;
    }
    
    /**
     * 
     * @param string $word
     * @return string
     */
    public function getStem($word) {
        return $this->getClient()->getApi()->streaming->getStem([
            "word" => $word
        ])->json()["stem"] ?? "";
    }
    
    /**
     * 
     * @param string $type
     * @param string $interval
     * @param string $start
     * @param string $end
     * @return mixed[]
     */
    public function getStats($type = self::STAT_PREPARED, $interval = "5m", $start = "-1 day", $end = "now") {
        return $this->getClient()->getApi()->streaming->getStats([
            "type" => $type,
            "interval" => $interval,
            "start_time" => strtotime($start),
            "end_time" => strtotime($end)
        ])->json();
    }
    
    /**
     * 
     * @return string|null
     */
    public function getEndpoint() {
        return $this->server["endpoint"] ?? null;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getKey() {
        return $this->server["key"] ?? null;
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