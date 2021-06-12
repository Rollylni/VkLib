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
use VkLib\Method\VkMethod;

use Workerman\Worker;
use Triggers;

use function strtotime;
use function method_exists;

/**
 * 
 * @link https://vk.com/dev/streaming_api_docs
 */
class VkStreaming {
    
    public const STAT_RECEIVED = "received";
    public const STAT_PREPARED = "prepared";
    
    public const GET_RULES = "get";
    public const ADD_RULES = "post";
    public const DELETE_RULES = "delete";
     
    // Triggers
    public const ON_CONNECT = "onConnect";
    public const ON_ERROR = "onError";
    public const ON_CLOSE = "onClose";
    public const ON_MESSAGE = "onMessage";
    public const ON_EVENT = "onEvent";
    public const ON_SERVICE_MESSAGE = "onServiceMessage";
    
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
     * @param string|VkClient $client - Service Access Token
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT) {
        $this->client = VkClient::checkClient($client);
    }
    
    /**
     * 
     * @param array $ctx - SSL Context
     */
    public function start(array $ctx = []): void {
        if (!$this->getEndpoint() || !$this->getKey()) {
            $this->authorization();
        }
        $worker = new Worker("websocket://".$this->getEndpoint()."/stream?key=".$this->getKey(), $ctx);
        $worker->transport = "ssl";
        $worker->onConnect = function($conn) {
            $this->getHandler()->handle(self::ON_CONNECT, [$conn]);
        };
        
        $worker->onError = function($conn) {
            $this->getHandler()->handle(self::ON_ERROR, [$conn]);  
        };
        
        $worker->onClose = function($conn) {
            $this->getHandler()->handle(self::ON_CLOSE, [$conn]);
        };
        
        $worker->onMessage = function($conn, $data) {
            $json = VkMethod::JSON($data);
            $this->getHandler()->handle(self::ON_MESSAGE, [$conn, $data]);
            
            if (isset($json["service_message"])) {
                $this->getHandler()->handle(self::ON_SERVICE_MESSAGE, [$conn, $json["service_message"]]);
            } elseif (isset($json["event"])) {
                $this->getHandler()->handle(self::ON_EVENT, [$conn, $json["event"]]);
            }
        };
        $worker->runAll();
    }
    
    /**
     * 
     * @return string[][]
     */
    public function getRules(): array {
        return $this->request(self::GET_RULES)["rules"] ?? [];
    }
    
    /**
     * 
     * @param string $tag
     * @param string $value
     */
    public function addRule(string $tag, string $value): self {
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
    public function deleteRule(string $tag): self {
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
    public function request(string $method, array $params = [], bool $throws = true): array {
        $http = $this->getClient()->getHttpClient();
        if (!method_exists($http, $method)) {
            return [];
        }
        
        $url = "https://".$this->getEndpoint()."/rules?key=".$this->getKey();
        $res = VkMethod::JSON($http->{$method}($url, [
            "http_errors" => $throws,
            "json" => $params
        ]));
        
        if (isset($res["error"]) && $throws) {
            throw new VkStreamingException($res["error"]["message"], $res["error"]["error_code"]);
        }  
        return $res;
    }    
    
    /**
     * 
     * @return self
     */
    public function authorization(): self {
        $this->server = $this->getClient()->getApi()->streaming->getServerUrl()->json();
        return $this;
    }
    
    /**
     * 
     * @return string[]
     */
    public function getSettings(): array {
        return $this->getClient()->getApi()->streaming->getSettings()->json();
    }
    
    /**
     * 
     * @param string[] $params
     * @return self
     */
    public function setSettings(array $params): self {
        $this->getClient()->getApi()->streaming->setSettings($params);
        return $this;
    }
    
    /**
     * 
     * @param string $word
     * @return string
     */
    public function getStem(string $word): string {
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
    public function getStats(string $type = self::STAT_PREPARED, string $interval = "5m", 
                             string $start = "-1 day", string $end = "now"): array {
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
    public function getEndpoint(): ?string {
        return $this->server["endpoint"] ?? null;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getKey(): ?string {
        return $this->server["key"] ?? null;
    }
    
    /**
     * 
     * @return VkClient
     */
    public function getClient(): VkClient {
        return $this->client;
    }
    
    /**
     * 
     * @return Triggers
     */
    public function getHandler(): Triggers {
        if (!($this->handler instanceof Triggers)) {
            $this->handler = new Triggers();
        }
        return $this->handler;
    }
}