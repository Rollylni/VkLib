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

use VkLib\Exception\VkClientException;
use VkLib\Exception\VkMethodException;
use VkLib\VkClient;
use VkLib\VkApi;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\PromiseInterface;

use function json_decode;
use function strtolower;
use function ctype_upper;
use function substr;
use function strlen;
use function is_callable;
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
    public const PARAM_TEST_MODE = "test_mode";
    
    /**
     * 
     * @var string|null
     */
    private $method = null;
    
    /**
     * 
     * @var mixed[]
     */
    public $parameters = [];
    
    /**
     * 
     * @param string|null $method
     * @param mixed[] $params
     */
    public function __construct(?string $method = null, array $params = []) {
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
    public function __call(string $method, array $args): self {
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
    public function __set(string $key, $value) {
        $this->setParameter($key, $value);
    }
    
    /**
     * 
     * @param bool $throws
     * @param float $timeout
     * @param callable $async
     * @throws RequestException
     * @throws VkClientException
     * @throws VkMethodException
     * @see VkMethod::convertParameters()
     * @see VkClient::checkClient()
     * @see Captcha::handle()
     * @return Response|PromiseInterface
     */
    public function call(bool $throws = true, float $timeout = 0, $async = false) {
        $this->parameters[self::PARAM_TOKEN] = $this->parameters[self::PARAM_TOKEN] ?? static::$client;
        $client = VkClient::checkClient($this->parameters[self::PARAM_TOKEN]);

        $this->parameters[self::PARAM_LANG] = $this->parameters[self::PARAM_LANG] ?? $client->getLang();
        $this->parameters[self::PARAM_VERSION] = $this->parameters[self::PARAM_VERSION] ?? $client->getVersion();
        $this->parameters[self::PARAM_TOKEN] = $client->getToken();
        $this->parameters = $this->convertParameters($this->parameters);
        
        $url = VkApi::ENDPOINT . $this->method;
        $options = [
            "form_params" => $this->parameters,
            "http_errors" => $throws,
            "timeout" => $timeout
        ];
        
        if (is_callable($async)) {
            return $client->getHttpClient()->postAsync($url, $options)->then(
                function(ResponseInterface $res) use($async) {
                    $async($this->handleResponse(self::JSON($res), false));
                }
            );
        }
        
         return $this->handleResponse(self::JSON($client->getHttpClient()->post($url, $options)), $throws);
    }
    
    /**
     * 
     * @since 0.7.1
     * 
     * @param array $response
     * @param bool $throws
     * @throws VkMethodException
     * @return Response
     */
    public function handleResponse(array $response, bool $throws = true): Response {
        $response = new Response($this, $response);
        $error = $response->getError();
        $handled = false;
        
        if ($error && (($captcha = $error->getCaptcha()) instanceof Captcha)) {
            $handled = Captcha::handle($captcha);
        }
        
        if ($throws && $error && !$handled) {
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
    public function __get(string $key) {
        return $this->getParameter($key);
    }
    
    /**
     * 
     * @since 0.7.1
     * 
     * @param string|ResponseInterface $res
     * @return mixed[]
     */
    public static function JSON($res): array {
        if ($res instanceof ResponseInterface) {
            $res = $res->getBody();
        }
        return json_decode($res, true) ?? [];
    }
    
    /**
     * 
     * @param string $param
     * @return string Formatted parameter
     */
    public static function formatParameter(string $param): string {
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
    public static function convertParameters(array $params): array {
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
    public function setMethod(string $method): self {
        $this->method = $method;
        return $this;
    }
    
    /**
     * 
     * @since 0.7.1
     * 
     * @param string $client
     * @return self
     */
    public function setClient(string $client): self {
        $this->setAccessToken($client);
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
    public function setParameters(array $params = []): self {
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
    public function setParameter(string $key, $value): self {
        $this->parameters[$key] = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $key
     * @return mixed
     */
    public function getParameter(string $key) {
        return $this->parameters[$key] ?? null;
    }
}