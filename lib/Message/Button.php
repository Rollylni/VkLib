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

use VkLib\Exception\UnexpectedTypeException;

use function json_encode;
use function is_scalar;
use function is_array;
use function count;

class Button {
    
    /**
     * 
     * @since 0.7.1
     * 
     * @var int
     */
    private static $counter = 0;
    
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
    public function __construct(string $type = self::TYPE_TEXT, string $color = self::COLOR_WHITE) {
        $this->setType($type);
        $this->setColor($color);
    }
    
    /**
     * 
     * # Example 
     * ```
     * $keyboard->addButton(Button::create(
     *     Button::TYPE_TEXT,
     *     Button::COLOR_GREEN, 
     *     "Hello", [], 
     *     ["id" => "example", "handler" => function($obj) {}]
     * ));
     * 
     * ```
     * 
     * @since 0.7.1
     * 
     * @param string $type
     * @param string $color
     * @param string $label
     * @param string|array $payload
     * @param (scalar|Closure|callable)[] $onClick
     * @return Button
     */
    public static function create(string $type = self::TYPE_TEXT, string $color = self::COLOR_WHITE,
                                  ?string $label = null, ?string  $payload = null, array $onClick = []): Button {
        $btn = new self($type, $color);
        if ($label !== null) {
            $btn->setLabel($label);
        } if ($payload !== null) {
            $btn->setPayload($payload);
        }
        
        $btn->setId($onClick["id"] ?? null);
        if (isset($onClick["handler"])) {
            $btn->onClick($onClick["handler"]);
        }
        return $btn;
    }
    
    /**
     * 
     * @see Keyboard::addHandler()
     * @param \Closure|callable $handler
     * @return scalar
     */
    public function onClick($handler) {
        return Keyboard::addHandler($this, $handler);
    }
    
    /**
     * 
     * @param bool $json
     * @return array|string
     */
    public function getBody(bool $json = true) {
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
    
    public function clear(): void {
        $this->action = [];
    }
    
    /**
     * 
     * @param string $label
     */
    public function setLabel(string $label): self {
        return $this->setAction(self::ACTION_LABEL, $label);
    }
    
    /**
     * 
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->getAction(self::ACTION_LABEL);
    }
    
    /**
     * 
     * @param array|string $payload
     */
    public function setPayload($payload): self {
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
    public function setType(string $type): self {
        return $this->setAction(self::ACTION_TYPE, $type);
    }
    
    /**
     * 
     * @return string|null
     */
    public function getType(): ?string {
        return $this->getAction(self::ACTION_TYPE);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setAction(string $key, $value): self {
        $this->action[$key] = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $key
     * @return mixed
     */
    public function getAction(?string $key = null) {
        if ($key === null) {
            return $this->action;
        }
        return $this->action[$key] ?? null;
    }
    
    /**
     * 
     * @param scalar $id
     * @throws UnexpectedTypeException
     * @return self
     */
    public function setId($id = null): self {
        if (!is_scalar($id) && $id !== null) {
            throw new UnexpectedTypeException($id, ["scalar", "null"]);
        }
        $this->id = $id;
        return $this;
    }
    
    /**
     * 
     * @return scalar
     */
    public function getId() {
        if ($this->id === null) {
            return $this->id = ++static::$counter;
        }
        return $this->id;
    }
    
    /**
     * 
     * @param string $color
     * @return self
     */
    public function setColor(string $color): self {
        $this->color = $color;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getColor(): string {
        return $this->color;
    }
}