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

interface HttpClient {
    
    /**
     * 
     * @param string $url
     * @param array $options
     * @throws HttpRequestException
     * @return array
     */
    public function postRequest($url, array $options = []): array;
    
    /**
     * 
     * @param string $url
     * @param array $options
     * @throws HttpRequestException
     * @return array
     */
    public function deleteRequest($url, array $options = []): array;
    
    /**
     * 
     * @param string $url
     * @throws HttpRequestException
     * @return array
     */
    public function getRequest($url): array;
}