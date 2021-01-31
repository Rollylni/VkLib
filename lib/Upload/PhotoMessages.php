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

class PhotoMessages extends Upload {
    
    /**
     * 
     * @var int
     */
    public $peerId = null;
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function getServer(array $params = []) {
        if ($this->peerId) {
            $params["peer_id"] = $this->peerId;
        }
        return $this->getClient()->getApi()->photos->getMessagesUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []) {
        return $this->getClient()->getApi()->photos->saveMessagesPhoto($params)->json();
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
    public function setPeerId($id) {
        $this->peerId = $id;
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