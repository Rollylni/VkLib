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
 * Author Homepage {@link https://vk.com/rollylni}
 *
 * @copyright 2019-2020 Rollylni
 * @author Faruch N. <rollyllni@gmail.com>
 * @version 0.7 beta
 * @license MIT
 */
namespace VkLib;

use VkLib\Exception\VkClientException;
use VkLib\HttpClient\BaseHttpClient;
use VkLib\HttpClient\HttpClient;

use function strlen;

final class VkClient {
    
    /**
     * 
     * @var VkClient[]
     */
    private static $clients = [];
    
    public const DEFAULT_CLIENT = "main";
    
    /**
     * 
     * @var HttpClient
     */
    private $httpClient = null;
    
    /**
     * 
     * @var string
     */
    public $token = null;
    
    /**
     * 
     * @var float
     */
    public $version;
    
    /**
     * 
     * @var string
     */
    public $lang;
    
    /**
     * 
     * @var string
     */
    private $name;
    
    /**
     * 
     * @var VkApi
     */
    private $api = null;


    /**
     * 
     * @var string $client
     * @var float  $version
     * @var string $lang
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT, $version = VkApi::CURRENT_VERSION, $lang = VkLang::ENGLISH) {
        $this->setVersion($version)->setLang($lang)->addClient($client, $this);
        $this->name = $client;
    }
    
    /**
     *
     * @param string $name
     * @param VkClient $client
     */
    public static function addClient($name, VkClient $client) {
        self::$clients[$name] = $client;
    }
    
    /**
     * 
     * @param string|VkClient $client
     */
    public static function removeClient($client) {
        if ($client instanceof VkClient) {
            $client = $client->getName();
        }
        unset(self::$clients[$client]);
    }

    /**
     *
     * @param string $client
     * @return VkClient|string
     */
    public static function getClient($client = self::DEFAULT_CLIENT) {
        return self::$clients[$client] ?? $client;
    }

    /**
     *
     * @param string $client
     * @return VkClient
     * @throws VkClientException if the client is not found
     * @uses \VkLib\Method\VkMethod::call()
     * @uses \VkLib\LongPoll\LongPoll::__construct()
     * @uses \VkLib\Callback\CallbackManager::__construct()
     * @uses \VkLib\Widget\Widget::update()
     * @uses \VkLib\VkStreaming::__construct()
     * @uses \VkLib\Upload\Upload::__construct()
     */
    public static function checkClient($client = self::DEFAULT_CLIENT) {
        if($client instanceof VkClient){
            return $client;
        } elseif (self::getClient($client) instanceof VkClient) {
            return self::getClient($client);
        } elseif (strlen($client) === 85) {
            return (new self('tc'))->setToken($client);
        } else {
            throw new VkClientException("client '$client' does not exist, Please create a new client for further work with the Library!");
        }
    }
    
    /**
     * 
     * @param HttpClient|null $client
     */
    public function setHttpClient(?HttpClient $client = null) {
        $this->httpClient = $client;
    }
    
    /**
     * 
     * @return HttpClient
     */
    public function getHttpClient() {
        if (!($this->httpClient instanceof HttpClient)) {
            $this->httpClient = new BaseHttpClient();
        }
        return $this->httpClient;
    }
    
    /**
     * 
     * @return VkApi
     */
    public function getApi() {
        if ($this->api === null) {
            $this->api = new VkApi($this->getName());
        }
        return $this->api;
    }
    
    /**
     *
     * @param string $token
     * @return self
     */
    public function setToken($token) {
        $this->token = $token;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     *
     * @param float $version
     * @return self
     */
    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    /**
     *
     * @return float
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     *
     * @param string $lang
     * @return self
     */
    public function setLang($lang) {
        $this->lang = $lang;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLang() {
        return $this->lang;
    }
    
    /**
     * 
     * @var string
     */
    public function getName() {
        return $this->name;
    }
}
