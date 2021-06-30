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

use VkLib\Exception\VkMethodException;
use VkLib\Method\Response;
use VkLib\Method\VkMethod;

use GuzzleHttp\Promise\PromiseInterface;

class VkApi {
    
    /**
     *
     * @link https://vk.com/dev/versions
     * @var float
     */
    public const CURRENT_VERSION = 5.131;

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
     * Reserved Parameters
     * 
     * @var string[]
     */
    public $params = [];
    
    /**
     *
     * @param string|VkClient $client
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT) {
        $this->addParam(VkMethod::PARAM_TOKEN, $client);
    }
    
    /**
     * add a reserved parameter
     * 
     * @param string $param
     * @param mixed $value
     */
    public function addParam(string $param, $value): void {
        $this->params[$param] = $value;
    }
    
    /**
     * delete a reserved parameter
     * 
     * @param string $param
     */
    public function delParam(string $param): void {
        unset($this->params[$param]);
    }
    
    /**
     * 
     * @return self
     */
    public function reset(): self {
        $this->section = null;
        return $this;
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
     * @param string $method
     * @param array $args 0 => parameters (array) = [], 1 => throws (bool) = true,
     *                    2 => timeout (float) = 0, 3 => async (callable) = false
     * @throws VkMethodException
     * @return Response|PromiseInterface
     */
    public function __call(string $method, array $args) {
        if ($this->section !== null) {
            $method = $this->section .".". $method;
        }
        $vkMethod = new VkMethod($method, $args[0] ?? []);
        foreach ($this->params as $k => $v)
            $vkMethod->parameters[$k] = $v;
        return $vkMethod->call($args[1] ?? true, $args[2] ?? 0, $args[3] ?? false);
    }
}