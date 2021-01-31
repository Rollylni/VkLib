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
namespace VkLib\Message;

use function array_slice;
use function is_array;
use function explode;
use function implode;

class Attachment {
    
    /**
     * 10 - maximum attachment limit (for a message)
     * 
     * @var int
     */
    public static $limit = 10;
    
    /**
     * 
     * @var Attachment[]
     */
    protected $attachments = [];
    
    /**
     * 
     * @var string
     */
    public $type = null;
   
    /**
     * 
     * @var int
     */
    public $ownerId = null;
    
    /**
     * 
     * @var int
     */
    public $mediaId = null;
    
    /**
     * 
     * @var string
     */
    public $accessKey = null;


    /**
     * 
     * @param string|array $attach
     */
    public function __construct($attach) {
        if (is_array($attach)) {
            $this->type = $attach[0];
            $this->ownerId = $attach[1];
            $this->mediaId = $attach[2];
            $this->accessKey = $attach[3] ?? null;
            $this->attachments[] = $this;
        } else {
            $this->attachments = $f = $this->format($attach, static::$limit);
            if (isset($f[0])) {
                $this->type = $f[0]->getType();
                $this->ownerId = $f[0]->getOwnerId();
                $this->mediaId = $f[0]->getId();
                $this->accessKey = $f[0]->getKey();
            }
        }
    }
   
    /**
     * 
     * @param string $attach
     * @param int $limit
     * @return Attachment[]
     */
    public static function format(string $attach, int $limit = 10): array {
       $e = explode(',', $attach, $limit);
       $a = [];
       foreach ($e as $v) {
           $a[] = new static(explode('_', $v, 4));
       }
       return $a;
    }
    
    /**
     * 
     * @param Attachment[] $attachs
     * @param int $limit
     * @return string
     */
    public static function deformat(array $attachs, int $limit = 10): string {
        return implode(',', array_slice($attachs, 0, $limit));
    }
   
    /**
     * 
     * @return string
     */
    public function getBody() {
        return $this->deformat($this->getAttachments(), static::$limit);
    }
   
    /**
     * 
     * @return Attachment[]
     */
    public function getAttachments() {
        return $this->attachments;
    }
   
    /**
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }
   
    /**
     * 
     * @return string
     */
    public function getKey() {
        return $this->accessKey;
    }
   
    /**
     * 
     * @return int
     */
    public function getOwnerId() {
        return $this->ownerId;
    }
   
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->mediaId;
    }
   
    /**
     * 
     * @return string
     */
    public function __toString() {
        $arr = [
            $this->getType(),
            $this->getOwnerId(),
            $this->getId()
        ];
        if ($this->getKey() !== null) {
            $arr[] = $this->getKey();
        }
        return implode('_', $arr);
    }
}