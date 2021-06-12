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

class DocumentMessages extends Upload {
    
    public const TYPE_DOCUMENT = "doc";
    public const TYPE_GRAFFITI = "graffiti";
    public const TYPE_AUDIO = "audio_message";
    
    /**
     * 
     * @var int
     */
    public $peerId = null;
    
    /**
     * 
     * @var string
     */
    public $type = self::TYPE_DOCUMENT;
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function getServer(array $params = []): array {
        if ($this->peerId) {
            $params["peer_id"] = $this->peerId;
        } if (!isset($params["type"])) {
            $params["type"] = $this->type;
        }
        return $this->getClient()->getApi()->docs->getMessagesUploadServer($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function save(array $params = []): array {
        return $this->getClient()->getApi()->docs->save($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function upload(array $params = []): array {
        $url = $this->getServer()["upload_url"];
        $res = $this->post($url, "file");
        return $this->save($res + $params);
    }
    
    /**
     * 
     * @param int $id
     */
    public function setPeerId(int $id): self {
        $this->peerId = $id;
        return $this;
    }
    
    /**
     * 
     * @param string $src
     */
    public function setDocument(string $src): self {
        $this->type = self::TYPE_DOCUMENT;
        return $this->addFile($src);
    }
    
    /**
     * 
     * @param string $src
     */
    public function setGraffiti(string $src): self {
        $this->type = self::TYPE_GRAFFITI;
        return $this->addFile($src);
    }
    
    /**
     * 
     * @param string $src
     */
    public function setAudio(string $src): self {
        $this->type = self::TYPE_AUDIO;
        return $this->addFile($src);
    }
}