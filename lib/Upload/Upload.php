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
namespace VkLib\Upload;

use VkLib\VkClient;

use function fopen;
use function file_exists;
use function str_replace;

/**
 * 
 * @link https://vk.com/dev/upload_files
 */
abstract class Upload {
    
    /**
     * 
     * @var mixed[]
     */
    public $params = [];
    
    /**
     * 
     * @var string[]
     */
    public $files = [];
    
    /**
     *
     * @var VkClient
     */
    private $client;
    
    /**
     * 
     * @param $client
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT) {
        $this->client = VkClient::checkClient($client);
    }
    
    /**
     * 
     * @return array
     */
    public function getServer() {}
    
    /**
     * 
     * @return array
     */
    public function save() {}
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public abstract function upload(array $params = []): array;
    
    /**
     * 
     * @param string $url
     * @param string $format
     * @return array
     */
    public function post($url, $format = "file%") {
        $multipart = [];
        foreach ($this->getParams() as $k => $v) {
            $multipart[] = [
                "name" => $k,
                "contents" => $v
            ];
        }
        
        foreach ($this->getFiles() as $k => $v) {
            if (!file_exists($v)) {
                continue;
            }
            $multipart[] = [
                "name" => str_replace("%", ++$k, $format),
                "contents" => fopen($v, "rb")
            ];
        }
        return $this->getClient()->getHttpClient()->postRequest($url, [
            "multipart" => $multipart
        ]);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     */
    public function addParam($key, $value) {
        $this->params[$key] = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $fileSrc
     */
    public function addFile($fileSrc) {
        $this->files[] = $fileSrc;
        return $this;
    }
    
    /**
     * 
     * @return mixed[]
     */
    public function getParams() {
        return $this->params;
    }
    
    /**
     * 
     * @return string[]
     */
    public function getFiles() {
        return $this->files;
    }
    
    /**
     * 
     * @return VkCLient
     */
    public function getClient() {
        return $this->client;
    }
}