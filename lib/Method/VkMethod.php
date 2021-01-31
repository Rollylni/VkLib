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
namespace VkLib\Method;

use VkLib\Exception\HttpRequestException;
use VkLib\Exception\VkClientException;
use VkLib\Exception\VkMethodException;
use VkLib\VkClient;
use VkLib\VkApi;

use function strtolower;
use function ctype_upper;
use function substr;
use function strlen;
use function is_array;
use function is_bool;
use function implode;
use function intval;
use const PHP_EOL;

/**
 *
 * @link https://vk.com/dev/api_requests
 */
class VkMethod {
    
    /**
     * Client whose token will be used to send VkApi methods
     *
     * @var string
     */
    public static $client = VkClient::DEFAULT_CLIENT;
    
    public const PARAM_LANG = "lang";
    public const PARAM_TOKEN = "access_token";
    public const PARAM_VERSION = "v";
    
    /**
     * 
     * @var string 
     */
    private $method = null;
    
    /**
     * 
     * @var mixed[]
     */
    public $parameters = [];
    
    /**
     * 
     * @param string $method
     * @param mixed[] $params
     */
    public function __construct($method = null, array $params = []) {
        $this->method = $method;
        $this->parameters = $params;
    }
    
    /**
     * 
     * @param string $method
     * @param array $args
     * @see VkMethod::formatParameter()
     * @return self
     */
    public function __call($method, $args) {
        if (substr($method, 0, 3) === "set") {
            $param = $this->formatParameter(substr($method, 3));
            $this->setParameter($param, $args[0] ?? null);
        }
        return $this;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value) {
        $this->setParameter($key, $value);
    }
    
    /**
     * 
     * @param bool $throws
     * @throws HttpRequestException - maybe
     * @throws VkClientException
     * @throws VkMethodException
     * @see VkMethod::convertParameters()
     * @see VkClient::checkClient()
     * @see Captcha::handle()
     * @return Response
     */
    public function call(bool $throws = true) {
        $this->parameters[self::PARAM_TOKEN] = $this->parameters[self::PARAM_TOKEN] ?? static::$client;
        $client = VkClient::checkClient($this->parameters[self::PARAM_TOKEN]);

        $this->parameters[self::PARAM_LANG] = $this->parameters[self::PARAM_LANG] ?? $client->getLang();
        $this->parameters[self::PARAM_VERSION] = $this->parameters[self::PARAM_VERSION] ?? $client->getVersion();
        $this->parameters[self::PARAM_TOKEN] = $client->getToken();
        $this->parameters = $this->convertParameters($this->parameters);
        
        $response = $client->getHttpClient()->postRequest(VkApi::ENDPOINT . $this->method, [
            "form_params" => $this->parameters
        ]);
        $response = new Response($this, $response);
        $error = $response->getError();
        
        if ($error && (($captcha = $error->getCaptcha()) instanceof Captcha)) {
            Captcha::handle($captcha);
        }
        
        if ($throws && $error) {
            $error_msg = "Method " .$this->getMethod(). " failed: " .$error->getMessage(). " (" .$error->getCode(). ")";
            if ($error->getDescription()) {
                $error_msg .= ", ".$error->getDescription();
            }
            $error_msg .= ':' . PHP_EOL;
            foreach ($error->getRequestParams() as $i => $v)
                $error_msg .= "  #$i: " .$v["key"]. " = " .$v["value"].PHP_EOL;
            throw new VkMethodException($error_msg, $error);
        }
        return $response;
    }


    /**
     * 
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        return $this->getParameter($key);
    }
    
    /**
     * 
     * @param string $param
     * @return string Formatted parameter
     */
    public static function formatParameter($param) {
        $length = strlen($param);
        $str = "";
        for ($offset = 0; $offset < $length; $offset++) {
            $char = $param[$offset];
            if (ctype_upper($char)) {
                if (
                    isset($param[$offset + 1]) && 
                    !ctype_upper($param[$offset + 1]) &&
                    $offset != 0
                ) {
                    $str .= '_';
                }
                $char = strtolower($char);
            }
            $str .= $char;
        }
        return $str;
    }
    
    /**
     * 
     * @param mixed[] $params
     * @uses VkMethod::call()
     * @return (string|int)[]
     */
    public static function convertParameters(array $params) {
        foreach ($params as $k => $v) {
            if (is_array($v)) {
                $params[$k] = implode(",", $v);
            } elseif (is_bool($v)) {
                $params[$k] = intval($v);
            }
        }
        return $params;
    }

    /**
     * 
     * @param string $method
     * @return self
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }
    
    /**
     * 
     * @param array $params
     * @return self
     */
    public function setParameters(array $params = []) {
        $this->parameters = $params;
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getParameters(): array {
        return $this->parameters;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setParameter($key, $value) {
        $this->parameters[$key] = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $key
     * @return mixed
     */
    public function getParameter($key) {
        return $this->parameters[$key] ?? null;
    }
}