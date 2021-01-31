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
use function is_array;
use function count;

class Button {
    
    public const TYPE_TEXT = "text";
    public const TYPE_OPEN_LINK = "open_link";
    public const TYPE_LOCATION = "location";
    public const TYPE_VK_PAY = "vkpay";
    public const TYPE_VK_APPS = "open_app";
    public const TYPE_CALLBACK = "callback";
    
    public const COLOR_BLUE = "primary";
    public const COLOR_WHITE = "secondary";
    public const COLOR_RED = "negative";
    public const COLOR_GREEN = "positive";
    
    public const ACTION_TYPE = "type";
    public const ACTION_LABEL = "label";
    public const ACTION_PAYLOAD = "payload";
    
    /**
     *
     * @var string
     */
    public $color = self::COLOR_WHITE;
    
    /**
     * 
     * @var mixed[]
     */
    protected $action = [];
    
    /**
     * 
     * @var scalar
     */
    public $id = null;
    
    /**
     * 
     * @param string $type
     * @param string $color
     */
    public function __construct($type = self::TYPE_TEXT, $color = self::COLOR_WHITE) {
        $this->setType($type);
        $this->setColor($color);
    }
    
    /**
     * 
     * @see Keyboard::addHandler()
     * @param \Closure|callable $handler
     * @return int
     */
    public function onClick($handler) {
        return Keyboard::addHandler($this, $handler);
    }
    
    /**
     * 
     * @param bool $json
     * @return array|string
     */
    public function getBody($json = true) {
        $body = [
            "color" => $this->getColor(),
            "action" => $this->getAction()
        ];
        
        if (is_array($this->getPayload())) {
            $body["action"][self::ACTION_PAYLOAD] = (string) json_encode($this->getPayload());
        }
        
        if ($json) {
            $body = (string) json_encode($body);
        }
        return $body;
    }
    
    public function clear() {
        $this->action = [];
    }
    
    /**
     * 
     * @param string $label
     */
    public function setLabel($label) {
        return $this->setAction(self::ACTION_LABEL, $label);
    }
    
    /**
     * 
     * @return string|null
     */
    public function getLabel() {
        return $this->getAction(self::ACTION_LABEL);
    }
    
    /**
     * 
     * @param array|string $payload
     */
    public function setPayload($payload) {
        return $this->setAction(self::ACTION_PAYLOAD, $payload);
    }
    
    /**
     * 
     * @return array|string|null
     */
    public function getPayload() {
        return $this->getAction(self::ACTION_PAYLOAD);
    }
    
    /**
     * 
     * @param string $type
     */
    public function setType($type) {
        return $this->setAction(self::ACTION_TYPE, $type);
    }
    
    /**
     * 
     * @return string|null
     */
    public function getType() {
        return $this->getAction(self::ACTION_TYPE);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setAction($key, $value) {
        $this->action[$key] = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $key
     * @return mixed
     */
    public function getAction($key = null) {
        if ($key === null) {
            return $this->action;
        }
        return $this->action[$key] ?? null;
    }
    
    /**
     * 
     * @param scalar $id
     * @returns self
     */
    public function setId($id = null) {
        $this->id = $id;
        return $this;
    }
    
    /**
     * 
     * @return scalar
     */
    public function getId() {
        if ($this->id === null) {
            return $this->id = count(Keyboard::getHandlers());
        }
        return $this->id;
    }
    
    /**
     * 
     * @param string $color
     * @return self
     */
    public function setColor($color) {
        $this->color = $color;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getColor() {
        return $this->color;
    }
}