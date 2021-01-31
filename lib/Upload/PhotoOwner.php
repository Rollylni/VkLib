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

class PhotoOwner extends Upload {
    
    /**
     * 
     * @var string|int
     */
    public $ownerId = null;
    
    /**
     * 
     * @param array $params 
     * @return array
     */
    public function getServer(array $params = []) {
        if ($this->ownerId) {
            $params["owner_id"] = $this->ownerId;
        }
        return $this->getClient()->getApi()->photos->getOwnerPhotoUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []) {
        return $this->getClient()->getApi()->photos->saveOwnerPhoto($params)->json();
    }
    /**
     * 
     * @param array $params
     * @return array
     */
    public function upload(array $params = []): array {
        $url = $this->getServer()["upload_url"];
        $res = $this->post($url, "photo");
        return $this->save($res);
    }
    
    /**
     * 
     * @param string|int $id
     */
    public function setOwnerId($id) {
        $this->ownerId = $id;
        return $this;
    }
    
    /**
     * 
     * @param string $crop
     */
    public function setSquareCrop($crop) {
        return $this->addParam("_square_crop", $crop);
    }
    
    /**
     * 
     * @param string $src
     */
    public function setPhoto($src) {
        return $this->addFile($src);
    }
}