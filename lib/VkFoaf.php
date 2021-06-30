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
 * @copyright 2019-2021 Rollylni
 * @author Faruch N. <rollyllni@gmail.com>
 * @version 0.7 beta
 * @license MIT
 */
namespace VkLib;

use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

use function rtrim;
use function mb_strlen;
use function mb_substr;
use function preg_match;

/**
 * 
 * @since 0.7.1
 */
class VkFoaf {
    
    public const ENDPOINT = "https://vk.com/foaf.php";
    
    public const TYPE_GROUP = "Group";
    public const TYPE_PERSON = "Person";
    
    public const PUBLIC_ACCESS_ALLOWED = "allowed";
    public const PUBLIC_ACCESS_DISALLOWED = "disallowed";
    
    public const GROUP_TYPE_GROUP = "group";
    public const GROUP_TYPE_EVENT = "event";
    public const GROUP_TYPE_PUBLIC = "public";
    
    public const PROFILE_STATE_ACTIVE = "active";
    public const PROFILE_STATE_VERIFIED = "verified"; 
    public const PROFILE_STATE_DELETED = "deleted";
    public const PROFILE_STATE_BANNED = "banned";
    
    /**
     * 
     * @var mixed[][]
     */
    protected $foaf = [];
    
    /**
     * 
     * @var string 
     */
    protected $type = "";
    
    /**
     * 
     * @var ClientInterface
     */
    protected $httpClient = null;
    
    /**
     * 
     * @var string|int
     */
    public $subjectId = null;
    
    /**
     * 
     * @param string|int $sid
     */
    public function __construct($sid = null) {
        $this->subjectId = $sid;
    }
    
    /**
     * 
     * @param bool $throws
     * @return void
     */
    public function execute(bool $throws = true): void {
        $res = $this->getHttpClient()->get(self::ENDPOINT, [
            "query" => [
                "id" => $this->getSubjectId()
            ],
            "http_errors" => $throws
        ]);
        
        $tags = [];
        $current = null;
        $foaf = 2;
        
        self::match($res, "<\?.*\?>");
        while (self::has($res)) {
            if (self::match($res, "<\/foaf:(Person|Group)>")) {
                $foaf = 0;
            } elseif (self::match($res, "<foaf:(Person|Group)>", $matches)) {
                $this->type = $matches[1];
                $foaf--;
                continue;
            } elseif ($foaf === 2) {
                self::consume($res);
                continue;
            }
            
            if ($foaf) {
                $char = self::consume($res);
                
                if ($char === '<') {
                    $closing = false;
                    if (self::peek($res) === '/') {
                        self::consume($res);
                        $closing = true;
                    } 
                    
                    if (!self::match($res, ".*:(.*)(\s|>)", $matches)) {
                        continue;
                    }
                    
                    $key = $matches[1];
                    $params = [];
                    
                    while (self::has($res)) {
                        if (self::match($res, ".*:(.*)=", $matches)) {
                            $paramKey = $matches[1];
                            $paramValue = null;
                            
                            while (self::has($res)) {
                                $char = self::consume($res);
                                if ($char === '"') {
                                    if ($paramValue === null) {
                                        $paramValue = "";
                                    } else {
                                        break;
                                    }
                                } elseif ($paramValue !== null) {
                                    $paramValue .= $char;
                                }
                            }
                            
                            $params[$paramKey] = $paramValue;
                        } else {
                            if (self::peek($res, 2) === "/>" && !$closing) {
                                self::consume($res, 2);
                                $closing = true;
                                
                                if ($current !== null) {
                                    $tags[$current["key"]][] = $current;
                                } 
                                
                                $current = [
                                    "key" => $key,
                                    "params" => $params
                                ];
                                break;
                            } elseif (self::consume($res) === '>') {
                                break;
                            }
                        }
                    }
                    
                    if ($closing) {
                        $tags[$key][] = $current;
                        $current = null;
                    } else {
                        if ($current !== null) {
                            $tags[$current["key"]][] = $current;
                        }
                        
                        $current = [
                            "key" => $key,
                            "value" => "",
                            "params" => $params
                        ];
                    }
                } elseif ($current !== null) {
                    $current["value"] .= $char;
                }
            } else {
                break;
            }
        }
        
        $this->foaf = $tags;
    }
    
    /**
     * 
     * @param string $str
     * @param int $len
     * @return string
     */
    protected function peek(string $str, int $len = 1): string {
        if ($len > ($max = mb_strlen($str))) {
            $len = $max;
        }
        return mb_substr($str, 0, $len);
    }
    
    /**
     * 
     * @param string $str
     * @param int $len
     * @return string
     */
    protected function consume(string &$str, int $len = 1): string {
        $peeked = $this->peek($str, $len);
        $str = mb_substr($str, mb_strlen($peeked));
        return $peeked;
    }
    
    /**
     * 
     * @param string $str
     * @param string $pattern
     * @param array $matches 
     * @return bool
     */
    protected function match(string &$str, string $pattern, ?array &$matches = null): bool {
        if (!preg_match("/^$pattern/", $str, $matches)) {
            return false;
        }
        
        $str = mb_substr($str, mb_strlen(rtrim($matches[0] ?? "")));
        return true;
    }
    
    /**
     * 
     * @param string $str
     * @return bool
     */
    protected function has(string $str): bool {
        return mb_strlen($str) > 0;
    }
    
    /**
     * 
     * @return bool 
     */
    public function isPerson(): bool {
        return $this->type === self::TYPE_PERSON;
    }
    
    /**
     * 
     * @return bool
     */
    public function isGroup(): bool {
        return $this->type === self::TYE_GROUP;
    }
    
    /**
     * 
     * @return mixed[]
     */
    public function getFoaf(): array {
        return $this->foaf;
    }
    
    /**
     * 
     * @param string|int $sid
     * @return self
     */
    public function setSubjectId($sid): self {
        $this->subjectId = $sid;
        return $this;
    }
    
    /**
     * 
     * @return string|int
     */
    public function getSubjectId() {
        return $this->subjectId;
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
}