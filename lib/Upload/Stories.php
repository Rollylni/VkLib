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

class Stories extends Upload {
    
    public const TYPE_PHOTO = "file";
    public const TYPE_VIDEO = "video_file";
    
    /**
     * 
     * @var string
     */
    public $type = self::TYPE_PHOTO;
    
    /**
     * 
     * @var mixed[]
     */
    public $parameters = [];
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function getServer(array $params = []) {
        $params += $this->parameters;
        if ($this->type === self::TYPE_PHOTO) {
            return $this->getClient()->getApi()->stories->getPhotoUploadServer($params)->json();
        } elseif ($this->type === self::TYPE_VIDEO) {
            return $this->getClient()->getApi()->stories->getVideoUploadServer($params)->json();
        } return [];
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []) {
        return $this->getClient()->getApi()->stories->save($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function upload(array $params = []): array {
        $url = $this->getServer()["upload_url"];
        $res = $this->post($url, $this->type);
        $params["upload_results"] = $res["upload_result"];
        return $this->save($params);
    }
    
    /**
     * 
     * @param string $src
     */
    public function setPhoto($src) {
        $this->type = self::TYPE_PHOTO;
        return $this->addFile($src);
    }
    
    /**
     * 
     * @param string $src
     */
    public function setVideo($src) {
        $this->type = self::TYPE_VIDEO;
        return $this->addFile($src);
    }
}