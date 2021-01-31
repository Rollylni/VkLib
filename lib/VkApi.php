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

use VkLib\Exception\HttpRequestException;
use VkLib\Exception\VkMethodException;
use VkLib\Method\Response;
use VkLib\Method\VkMethod;

class VkApi {
    
    /**
     *
     * @link https://vk.com/dev/versions
     * @var float
     */
    public const CURRENT_VERSION = 5.126;

    /**
     *
     * @link https://vk.com/dev/api_requests
     * @var string
     */
    public const ENDPOINT = "https://api.vk.com/method/";
    
    /**
     *
     * @var string
     */
    public $section = null;
    
    /**
     * 
     * @var string[]
     */
    public $params = [];
    
    /**
     * Reserved Parameters
     *
     * @param $client
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT) {
        $this->addParam("access_token", $client);
    }
    
    /**
     * add a reserved parameter
     * 
     * @param string $param
     * @param string $value
     */
    public function addParam($param, $value) {
        $this->params[$param] = $value;
    }
    
    /**
     * delete a reserved parameter
     * 
     * @param string $param
     */
    public function delParam($param) {
        unset($this->params[$param]);
    }
    
    /**
     * 
     * @return self
     */
    public function clear() {
        $this->section = null;
        return $this;
    }

    /**
     *
     * @param string $section
     * @return self
     */
    public function __get(string $section) {
        $this->section = $section;
        return $this;
    }
    
     /**
     *
     * @param string $method
     * @param array $args
     * @throws HttpRequestException
     * @throws VkMethodException
     * @return Response|self
     */
    public function __call(string $method, array $args) {
        if ($this->section !== null) {
            $method = $this->section .".". $method;
        }
        $vkMethod = new VkMethod($method, $args[0] ?? []);
        foreach ($this->params as $k => $v)
            $vkMethod->parameters[$k] = $v;
        return $vkMethod->call($args[1] ?? true);
    }
}