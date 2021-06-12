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

class PhotoMarketAlbum extends Upload {
    
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
        if ($this->groupId) {
            $params["group_id"] = $this->groupId;
        }
        return $this->getClient()->getApi()->photos->getMarketAlbumUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []): array {
        return $this->getClient()->getApi()->photos->saveMarketAlbumPhoto($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function upload(array $params = []): array {
        $url = $this->getServer()["upload_url"];
        $res = $this->post($url, "file");
        if (isset($res["gid"])) {
            $params["group_id"] = $res["gid"];
            unset($res["gid"]);
        }
        return $this->save($res + $params);
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
    public function setPhoto(string $src): self {
        return $this->addFile($src);
    }
}