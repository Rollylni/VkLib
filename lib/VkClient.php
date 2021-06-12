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

use VkLib\Exception\UnexpectedTypeException;
use VkLib\Exception\VkClientException;

use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

use function is_string;
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
     * @var ClientInterface
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
    public function __construct(string $client = VkClient::DEFAULT_CLIENT, float $version = VkApi::CURRENT_VERSION, string $lang = VkLang::ENGLISH) {
        $this->setVersion($version)->setLang($lang)->addClient($client, $this);
        $this->name = $client;
    }
    
    /**
     * Removing this client
     * 
     * @since 0.7.1
     */
    public function remove(): void {
        $this->removeClient($this);
    }
    
    /**
     * 
     * @since 0.7.1
     * 
     * @param string $name
     */
    public function rename(string $name): void {
        $this->remove();
        $this->addClient($name, $this)
    }
    
    /**
     *
     * @param string $name
     * @param VkClient $client
     */
    public static function addClient(string $name, VkClient $client): void {
        self::$clients[$name] = $client;
    }
    
    /**
     * 
     * @param string|VkClient $client
     */
    public static function removeClient($client): void {
        if ($client instanceof VkClient) {
            $client = $client->getName();
        }
        unset(self::$clients[$client]);
    }

    /**
     *
     * @param string $client
     * @return VkClient|null
     */
    public static function getClient(string $client = self::DEFAULT_CLIENT): ?VkClient {
        return self::$clients[$client] ?? null;
    }

    /**
     *
     * @param string|VkClient $client
     * @return VkClient
     * @throws VkClientException if the client is not found
     * @throws UnexpectedTypeException
     * @uses \VkLib\Method\VkMethod::call()
     * @uses \VkLib\LongPoll\LongPoll::__construct()
     * @uses \VkLib\Callback\CallbackManager::__construct()
     * @uses \VkLib\Widget\Widget::update()
     * @uses \VkLib\VkStreaming::__construct()
     * @uses \VkLib\Upload\Upload::__construct()
     */
    public static function checkClient($client = self::DEFAULT_CLIENT): VkClient {
        if($client instanceof VkClient){
            return $client;
        } elseif (!is_string($client)) {
            throw new UnexpectedTypeException($client, ["string", __CLASS__]);
        } elseif (self::getClient($client) instanceof VkClient) {
            return self::getClient($client);
        } elseif (strlen($client) === 85) { // access token length
            return (new self("tmp/client"))->setToken($client);
        } else {
            throw new VkClientException("client '$client' does not exist, Please create a new client for further work with the Library!");
        }
    }
    
    /**
     * 
     * @param ClientInterface|null $client
     */
    public function setHttpClient(?ClientInterface $client = null): void {
        $this->httpClient = $client;
    }
    
    /**
     * 
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface {
        if (!($this->httpClient instanceof ClientInterface)) {
            $this->httpClient = new Client([
                "handler" => HandlerStack::create(new StreamHandler())
            ]);
        }
        return $this->httpClient;
    }
    
    /**
     * 
     * @return VkApi
     */
    public function getApi(): VkApi {
        if ($this->api === null) {
            $this->api = new VkApi($this);
        }
        return $this->api;
    }
    
    /**
     *
     * @param string $token
     * @return self
     */
    public function setToken(string $token): self {
        $this->token = $token;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getToken(): string {
        return $this->token;
    }

    /**
     *
     * @param float $version
     * @return self
     */
    public function setVersion(float $version): self {
        $this->version = $version;
        return $this;
    }

    /**
     *
     * @return float
     */
    public function getVersion(): float {
        return $this->version;
    }

    /**
     *
     * @param string $lang
     * @return self
     */
    public function setLang($lang): self {
        $this->lang = $lang;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLang(): string {
        return $this->lang;
    }
    
    /**
     * 
     * @var string
     */
    public function getName(): string {
        return $this->name;
    }
}


// Metalcore♡