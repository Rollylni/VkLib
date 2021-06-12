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
namespace VkLib\Widget;

/**
 * 
 * @link https://vk.com/dev/objects/appWidget?f=1.%20Text
 */
class TextWidget extends Widget {
    
    /**
     * 
     * @var string
     */
    protected $type = Widget::TYPE_TEXT;
    
    /**
     * 
     * @param string $text
     */
    public function setText(string $text): self {
        $this->body["text"] = $text;
        return $this;
    }
    
    /**
     * 
     * @param string $descr
     */
    public function setDescr(string $descr): self {
        $this->body["descr"] = $descr;
        return $this;
    }
}