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

class PhotoChat extends Upload {
    
    /**
     * 
     * @var int
     */
    public $chatId = null;
    
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
    public function getServer(array $params = []): array {
        if ($this->chatId) {
            $params["chat_id"] = $this->chatId;
        } if ($this->cropX !== null) {
            $params["crop_x"] = $this->cropX;
        } if ($this->cropX !== null) {
            $params["crop_y"] = $this->cropY;
        } if ($this->cropWidth !== null) {
            $params["crop_width"] = $this->cropWidth;
        }
        return $this->getClient()->getApi()->photos->getChatUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []): array {
        return $this->getClient()->getApi()->messages->setChatPhoto($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function upload(array $params = []): array {
        $url = $this->getServer()["upload_url"];
        $res = $this->post($url, "file");
        return $this->save(["file" => $res["response"]]);
    }
    
    /**
     * 
     * @param int $id
     */
    public function setChatId(int $id): self {
        $this->chatId = $id;
        return $this;
    }
    
    /**
     * 
     * @param int $x
     * @param int $y
     * @param int $width
     */
    public function setCrop(int $x, int $y, int $width): self {
        $this->cropX = $x;
        $this->cropY = $y;
        $this->cropWidth = $width;
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