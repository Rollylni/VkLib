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
 * @link https://vk.com/dev/objects/appWidget?f=3.%20Table
 */
class TableWidget extends Widget {
    
    /**
     * 
     * @var string
     */
    protected $type = Widget::TYPE_TABLE;
    
    public const ALIGN_RIGHT = "right";
    public const ALIGN_CENTER = "center";
    public const ALIGN_LEFT = "left";
    
    /**
     * 
     * @param string $text
     * @param string $align
     */
    public function addHeader($text, $align = self::ALIGN_CENTER) {
        $this->body["head"][] = [
            "text" => $text,
            "align" => $align
        ];
        return $this;
    }
    
    /**
     * 
     * @param string $text
     * @param string $url
     * @param string|array $icon_id
     */
    public function addBody($text, $url = null, $icon_id = null) {
        $body = ["text" => $text];   
        if ($url) {
            $body["url"] = $url;
        } if ($icon_id) {
            $body["icon_id"] = $icon_id;
        }
        $this->body["body"][] = $body;
        return $this;
    }
}