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
namespace VkLib\HttpClient;

use VkLib\Exception\HttpRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\HandlerStack;

class BaseHttpClient extends Client implements HttpClient {
    
    /**
     * 
     * @param array $cfg
     */
    public function __construct(array $cfg = []) {
        if (!isset($cfg["handler"])) {
            $handler = new StreamHandler();
            $stack = HandlerStack::create($handler);
            $cfg["handler"] = $stack;
        }
        parent::__construct($cfg);
    }
    
    /**
     * 
     * @param string $url
     * @param array $options
     * @throws HttpRequestException
     * @return array
     */
    public function deleteRequest($url, array $options = []): array {
        $response = $this->request("DELETE", $url, $options);
        if ($response->getStatusCode() !== 200) {
            throw new HttpRequestException("HTTP Error Occured: " . $response->getReasonPhrase(). " (" .$response->getStatusCode(). ") \"$url\"");
        }
        return json_decode($response->getBody(), true) ?? [];
    }
    
    /**
     * 
     * @param string $url
     * @throws HttpRequestException
     * @return array
     */
    public function getRequest($url): array {
        $response = $this->request("GET", $url);
        if ($response->getStatusCode() !== 200) {
            throw new HttpRequestException("HTTP Error Occured: " . $response->getReasonPhrase(). " (" .$response->getStatusCode(). ") \"$url\"");
        }
        return json_decode($response->getBody(), true) ?? [];
    }
    
    /**
     * 
     * @param type $url
     * @param array $options
     * @throws HttpRequestException
     * @return array
     */
    public function postRequest($url, array $options = []): array {
        $response = $this->request("POST", $url, $options);
        if ($response->getStatusCode() !== 200) {
            throw new HttpRequestException("HTTP Error Occured: " . $response->getReasonPhrase(). " (" .$response->getStatusCode(). ") \"$url\"");
        }
        return json_decode($response->getBody(), true) ?? [];
    }
}