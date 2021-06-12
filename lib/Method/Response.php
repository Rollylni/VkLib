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

use function substr;
use function json_encode;

class Response {
    
    /**
     * 
     * @var VkMethod
     */
    protected $method; 
    
    /**
     * 
     * @var array
     */
    public $data;
    
    /**
     * 
     * @param VkMethod $method
     * @param array $data - Json Data
     */
    public function __construct(VkMethod $method, array $data = []) {
        $this->method = $method;
        $this->data = $data;
    }
    
    /**
     * 
     * @param string $method
     * @param array $args
     * @see VkMethod::formatParameter()
     * @return mixed
     */
    public function __call(string $method, array $args) {
        if (substr($method, 0, 3) === "get") {
            $offset = $args[0] ?? null;
            $param = VkMethod::formatParameter(substr($method, 3));
            if ($offset !== null) {
                if (isset($this->json()["items"][$offset][$param])) {
                    return $this->json()["items"][$offset][$param];
                } elseif (isset($this->json()[$offset][$param])) {
                    return $this->json()[$offset][$param];
                }
            }
            return $this->json()[$param] ?? null;
        }
    }
    
    /**
     * 
     * @return Error|null
     */
    public function getError(): ?Error {
        $err = $this->json(false);
        if (isset($err["error"])) {
            return new Error($err["error"], $this->getMethod());
        }
        return null;
    }
    
    /**
     * 
     * @return VkMethod
     */
    public function getMethod(): VkMethod {
        return $this->method;
    }
    
    /**
     * 
     * @since 0.7.1
     * 
     * @return string
     */
    public function getResponse(): string {
        return json_encode($this->json());
    }
    
    /**
     * 
     * @deprecated
     * 
     * @return string|false
     */
    public function getData() {
        return json_encode($this->json());
    }
    
    /**
     * 
     * @param bool $resp
     * @return mixed
     */
    public function json(bool $resp = true) {
        if (isset($this->data["response"]) && $resp) {
            return $this->data["response"];
        }
        return $this->data;
    }
}
