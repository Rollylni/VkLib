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
namespace VkLib\Widget\Content;

class CoverRow extends Content {
    
    /**
     * 
     * @param string $coverId
     */
    public function __construct($coverId) {
        $this->setCoverId($coverId);
    }
    
    /**
     * 
     * @param string $title
     */
    public function setTitle($title) {
        $this->content["title"] = $title;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setUrl($url) {
        $this->content["url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param string $text
     */
    public function setButton($text) {
        $this->content["button"] = $text;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setButtonUrl($url) {
        $this->content["button_url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param string $id
     */
    public function setCoverId($id) {
        $this->content["cover_id"] = $id;
        return $this;
    }
    
    /**
     * 
     * @param string $descr
     */
    public function setDescr($descr) {
        $this->content["descr"] = $descr;
        return $this;
    }
}