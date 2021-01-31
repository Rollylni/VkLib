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

class PhotoWall extends Upload {
    
    /**
     * 
     * @var int
     */
    public $groupId = null;
    
    /**
     * 
     * @param array $params 
     * @retuirn array
     */
    public function getServer(array $params = []) {
        if ($this->groupId) {
            $params["group_id"] = $this->groupId;
        }
        return $this->getClient()->getApi()->photos->getWallUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []) {
        return $this->getClient()->getApi()->photos->saveWallPhoto($params)->json();
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
        $res = $this->post($url, "photo");
        return $this->save($params + $res);
    }
    
    /**
     * 
     * @param int $id
     */
    public function setGroupId($id) {
        $this->groupId = $id;
        return $id;
    }
    
    /**
     * 
     * @param string $src
     */
    public function setPhoto($src) {
        return $this->addFile($src);
    }
}