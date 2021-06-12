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

use function json_encode;
use function array_slice;

/**
 * 
 * @link https://vk.com/dev/bot_docs_templates?f=5.1.%20%D0%9A%D0%B0%D1%80%D1%83%D1%81%D0%B5%D0%BB%D0%B8
 */
class Carousel implements Template {
    
    /**
     * 
     * @var array[]
     */
    public $elements = [];
    
    public const ACTION_OPEN_PHOTO = "open_photo";
    public const ACTION_OPEN_LINK = "open_link";
    
    public const MAX_CAROUSEL_ELEMENTS = 10;
    public const MAX_ELEMENT_BUTTONS = 3;
    
    /**
     * 
     * @param Button[] $buttons
     * @param string $photoId
     * @param string $title
     * @param string $description
     * @param string $action
     * @param string $link
     */
    public function addElement(array $buttons, ?string $photoId = null, ?string $title = null, 
                    ?string $description = null, string $action = self::ACTION_OPEN_PHOTO, ?string $link = null): void {
        $body = [];
        $body["buttons"] = array_slice($buttons, 0, self::MAX_ELEMENT_BUTTONS);
        foreach ($body["buttons"] as $k => $button) {
            $body["buttons"][$k] = $button->getBody(false);
        }
        
        if ($photoId !== null) {
            $body["photo_id"] = $photoId;
        } if ($title !== null) {
            $body["title"] = $title;
            $body["description"] = $description;
        }
        $act = ["type" => $action];
        if ($action === self::ACTION_OPEN_LINK) {
            $act["link"] = $link;
        }
        $body["action"] = $act;
        $this->elements[] = $body;
    }
    
    /**
     * 
     * @param bool $json
     * @return array|string
     */
    public function getBody(bool $json = true) {
        $body = [
            "type" => Template::CAROUSEL,
            "elements" => array_slice($this->elements, 0, self::MAX_CAROUSEL_ELEMENTS)
        ];
        
        if ($json) {
            $body = (string) json_encode($body);
        }
        return $body;
    }
    
    /**
     * 
     * @return array
     */
    public function getElements(): array {
        return $this->elements;
    }
}