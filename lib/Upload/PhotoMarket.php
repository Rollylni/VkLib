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

use function intval;

class PhotoMarket extends Upload {
    
    /**
     * 
     * @var int
     */
    public $groupId = null;
    
    /**
     * 
     * @var bool
     */
    public $mainPhoto = true;
    
    /**
     * 
     * @var int
     */
    public $cropX = null;
    
    /**
     * 
     * @var int
     */
    public $cropY = null;
    
    /**
     * 
     * @var int
     */
    public $cropWidth = null;
    
    /**
     * 
     * @param array $params
     * @return array 
     */
    public function getServer(array $params = []) {
        if ($this->groupId) {
            $params["group_id"] = $this->groupId;
        } if ($this->cropX !== null) {
            $params["crop_x"] = $this->cropX;
        } if ($this->cropX !== null) {
            $params["crop_y"] = $this->cropY;
        } if ($this->cropWidth !== null) {
            $params["crop_width"] = $this->cropWidth;
        } if (!isset($params["main_photo"])) {
            $params["main_photo"] = intval($this->mainPhoto);
        }
        return $this->getClient()->getApi()->photos->getMarketUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array 
     */
    public function save(array $params = []) {
        return $this->getClient()->getApi()->photos->saveMarketPhoto($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function upload(array $params = []): array {
        if ($this->groupId) {
            $params["group_id"] = $this->groupId;
        }
        $url = $this->getServer()["upload_url"];
        $res = $this->post($url, "file");
        return $this->save($params + $res);
    }
    
    /**
     * 
     * @param string $id
     */
    public function setGroupId($id) {
        $this->groupId = $id;
        return $this;
    }
    
    /**
     * 
     * @param bool $mainPhoto
     */
    public function setMainPhoto($mainPhoto) {
        $this->mainPhoto = $mainPhoto;
        return $this;
    }
    
    /**
     * 
     * @param int $x
     * @param int $y
     * @param int $width
     */
    public function setCrop($x, $y, $width) {
        $this->cropX = $x;
        $this->cropY = $y;
        $this->cropWidth = $width;
        return $this;
    }
    
    /**
     * 
     * @param string $src
     */
    public function setPhoto($src) {
        return $this->addFile($src);
    }
}