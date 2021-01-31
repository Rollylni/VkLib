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

class ListRow extends Content {
    
    /**
     * 
     * @param string $title
     */
    public function __construct($title) {
        $this->setTitle($title);
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
    public function setTitleUrl($url) {
        $this->content["title_url"] = $url;
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
    public function setIconId($id) {
        $this->content["icon_id"] = $id;
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
    
    /**
     * 
     * @param string $address
     */
    public function setAddress($address) {
        $this->content["address"] = $address;
        return $this;
    }
    
    /**
     * 
     * @param string $time
     */
    public function setTime($time) {
        $this->content["time"] = $time;
        return $this;
    }
    
    /**
     * 
     * @param string $text
     */
    public function setText($text) {
        $this->content["text"] = $text;
        return $this;
    }
}