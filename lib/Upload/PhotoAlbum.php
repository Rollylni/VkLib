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

class PhotoAlbum extends Upload {
    
    /**
     * 
     * @var int
     */
    public $albumId = null;
    
    /**
     * 
     * @var int
     */
    public $groupId = null;
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function getServer(array $params = []): array {
        if ($this->albumId) {
            $params["album_id"] = $this->albumId;
        } if ($this->groupId) {
            $params["group_id"] = $this->groupId;
        }
        return $this->getClient()->getApi()->photos->getUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []): array {
        return $this->getClient()->getApi()->photos->save($params)->json();
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
        $res = $this->post($url);
        if (isset($res["aid"])) {
            $params["album_id"] = $res["aid"];
            unset($res["aid"]);
        }
        return $this->save($params + $res);
    }
    
    /**
     * 
     * @param int $id
     */
    public function setAlbumId(int $id): self {
        $this->albumId = $id;
        return $this;
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
     * @param string $src
     */
    public function addPhoto(string $src): self {
        return $this->addFile($src);
    }
}