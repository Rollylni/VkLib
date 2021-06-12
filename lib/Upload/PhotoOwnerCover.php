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

class PhotoOwnerCover extends Upload {
    
    /**
     * 
     * @var int
     */
    public $groupId = null;
    
    /**
     * 
     * @var int[]
     */
    public $cropParams = [
        "crop_x" => 0,
        "crop_y" => 0,
        "crop_x2" => 795,
        "crop_y2" => 200
    ];
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function getServer(array $params = []): array {
        if ($this->groupId) {
            $params["group_id"] = $this->groupId;
        }
        $params += $this->cropParams;
        return $this->getClient()->getApi()->photos->getOwnerCoverPhotoUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []): array {
        return $this->getClient()->getApi()->photos->saveOwnerCoverPhoto($params)->json();
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
     * @param int $id
     */
    public function setGroupId(int $id): self {
        $this->groupId = $id;
        return $this;
    }
    
    /**
     * 
     * @param int[] $params
     */
    public function setCropParams(array $params): self {
        $this->cropParams = $params;
        return $this;
    }
    
    /**
     * 
     * @param string $src
     */
    public function setPhoto(string $src): self {
        return $this->addFile($src);
    }
}