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

class GroupImage extends Upload {
    
    /**
     * 
     * @var string
     */
    public $imageType = null;
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function getServer(array $params = []) {
        if ($this->imageType) {
            $params["image_type"] = $this->imageType;
        }
        return $this->getClient()->getApi()->appWidgets->getGroupImageUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []) {
        return $this->getClient()->getApi()->appWidgets->saveGroupImage($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function upload(array $params = []): array {
        $url = $this->getServer()["upload_url"];
        $res = $this->post($url, "image");
        return $this->save($res);
    }
    
    /**
     * 
     * @param string $type
     */
    public function setImageType($type) {
        $this->imageType = $type;
        return $this;
    }
    
    /**
     * 
     * @param string $src
     */
    public function setImage($src) {
        return $this->addFile($src);
    }
}