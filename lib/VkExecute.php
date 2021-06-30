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

use VkLib\Method\Response;

use GuzzleHttp\Promise\PromiseInterface;

use function sprintf;
use function is_file;
use function is_array;
use function is_string;
use function is_null;
use function is_bool;
use function is_callable;
use function json_encode;
use function preg_replace;
use function file_get_contents;
use function sizeof;

/**
 * 
 * @since 0.7.1
 * @link https://vk.com/dev/execute
 */
class VkExecute {
    
    /**
     * Maximum number of calls to API methods
     * (in one request)
     * 
     * @var int
     */
    public const MAX_POOL_SIZE = 25;
    
    /**
     *
     * @var VkClient
     */
    private $client;
    
    /**
     * 
     * @var mixed[]
     */
    public $vars = [];
    
    /**
     * 
     * @var string
     */
    public $code = "";
    
    /**
     * 
     * @var int
     */
    public $func_v = 0; // what the fck is this?
    
    /**
     * 
     * @var mixed[][]|false
     */
    protected $pool = false;
    
    /**
     * 
     * @var string
     */
    protected $section = null;
    
    /**
     * 
     * @param string|VkClient $client
     * @param string $code
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT, string $code = "") {
        $this->client = VkClient::checkClient($client);
        $this->setCode($code);
    }
    
    /**
     *
     * @param string $section
     * @return self
     */
    public function __get(string $section): self {
        $this->section = $section;
        return $this;
    }
    
    /**
     * 
     * @param bool $throws 
     * @param float $timeout
     * @param callable $async
     * @return Response|PromiseInterface
     */
    public function execute(bool $throws = true, float $timeout = 10.00, $async = false) {
        if ($this->isPool() && sizeof($this->pool) > 0) {
            $code = "return [";
            foreach ($this->pool as $method) {
                $code .= sprintf("%s(%s),", $method["method"], json_encode($method["params"]));
            }
            $code .= "];";
            
            $res = $this->getClient()->getApi()->reset()->execute([
                "code" => $code
            ], $throws, $timeout);
            foreach ($res->json() as $k => $response) {
                $func = $this->pool[$k]["callback"];
                if (is_callable($func)) {
                    $func($response);
                }
            }
            
            $this->setPool();
            return $res;
        } else {
            $code = $this->code;
            foreach ($this->vars as $k => $v) {
                if (is_array($v)) {
                    $v = json_encode($v);
                } elseif (is_string($v)) {
                    $v = sprintf('"%s"', $v);
                } elseif (is_null($v)) {
                    $v = "null";
                } elseif (is_bool($v)) {
                    $v = $v ? 1:0;
                }
                $code = preg_replace("/getopt\(('|\")?$k('|\")?\)", $v, $code);
            }
            
            return $this->getClient()->getApi()->reset()->execute([
                "code" => $code,
                "func_v" => $this->func_v
            ], $throws, $timeout, $async);
        }
    }
    
    /**
     *
     * @param string $method
     * @param array $args 0 => parameters (mixed[]) = [], 1 => callback (callable)
     * @return int|null
     */
    public function __call(string $method, array $args): ?int {
        if ($this->section === null || sizeof($this->pool) > self::MAX_POOL_SIZE) {
            return null;
        } if (!$this->isPool()) {
            $this->setPool();
        }
        
        $method = sprintf("API.%s.%s", $this->section, $method);
        $params = $args[0] ?? [];
        $callback = $args[1] ?? null;
        
        $this->pool[] = [
            "method" => $method,
            "params" => $params,
            "callback" => $callback
        ];
        
        return sizeof($this->pool) - 1;
    }
    
    /**
     * 
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    public function __set(string $name, $value): void {
        $this->vars[$name] = $value;
    }
    
    /**
     * 
     * @return bool
     */
    public function isPool(): bool {
        return is_array($this->pool);
    }
    
    /**
     * 
     * @param mixed[] $vars
     * @return self
     */
    public function initVars(array $vars = []): self {
        $this->vars = $vars;
        return $this;
    }
    
    /**
     * 
     * @param bool $pool
     * @return self
     */
    public function setPool(bool $pool = true): self {
        if ($pool) {
            $this->pool = [];
        } else {
            $this->pool = false;
        }
        
        return $this;
    }
    
    /**
     * 
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self {
        if (is_file($code)) {
            $code = file_get_contents($code);
        }
        $this->code = $code;
        return $this;
    }
    
    /**
     * 
     * @return VkClient
     */
    public function getClient(): VkClient {
        return $this->client;
    }
}