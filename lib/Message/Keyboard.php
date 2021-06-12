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

use VkLib\LongPoll\LongPollEvent;
use VkLib\LongPoll\LongPollBot;

use function json_encode;
use function json_decode;
use function array_slice;
use function is_callable;
use function str_shuffle;
use function is_array;
use function substr;
use function count;
use function key;

/**
 * 
 * @link https://vk.com/dev/bots_docs_3?f=4.%20Bot%20keyboards
 */
class Keyboard {
    
    /**
     * 
     * @var callable[]
     */
    protected static $payloadHandlers = [];
    
    /**
     *
     * @var string
     */
    protected static $akey = null;
    
    public const STANDART_MAX_BUTTONS = 40;
    public const INLINE_MAX_BUTTONS = 10;
    
    public const MAX_LINES_COUNT = 10;
    
    public const LINE_DEFAULT_SIZE = 4;
    public const INLINE_SIZE = 3;
    
    /**
     * 
     * @var bool
     */
    protected $inline;
    
    /**
     * 
     * @var bool
     */
    public $onetime;
    
    /**
     * 
     * @var int
     */
    public $lineSize = self::LINE_DEFAULT_SIZE;
    
    /**
     * 
     * @var Button[][]
     */
    protected $lines = [[]];
    
    /**
     * 
     * @param bool $inLine
     * @param bool $oneTime
     */
    public function __construct(bool $inLine = false, bool $oneTime = false) {
        $this->reset($inLine);
        $this->onetime = $oneTime;
    }
    
    /**
     * 
     * @internal
     * @param LongPollBot $lp
     */
    public static function setLongPoll(LongPollBot $lp): void {
        self::generateKey();
        $lp->getHandler()->add(LongPollEvent::MESSAGE_NEW, function($obj) use($lp) {
            if (!$lp->isHandling()) {
                return;
            } if (isset($obj["message"])) {
                $obj = $obj["message"];
            } if (!isset($obj["payload"])) {
                return;
            }
            $payload = json_decode($obj["payload"], true) ?? [];
            if (
                !isset($payload["onClick"]) ||
                !isset($payload["onClick"]["id"]) ||
                !isset(static::$payloadHandlers[$payload["onClick"]["id"]]) ||
                !isset($payload["onClick"]["key"]) ||
                $payload["onClick"]["key"] !== static::$akey
            ) {
               return;
            }
            
            $handler = static::$payloadHandlers[$payload["onClick"]["id"]];
            $obj["payload"] = $payload["payload"] ?? [];
            if (is_callable($handler)) {
                $handler($obj);
            }
        });
        
        $lp->getHandler()->add(LongPollEvent::MESSAGE_EVENT, function($obj) use($lp) {
            if (!$lp->isHandling()) {
                return;
            } if (!isset($obj["payload"])) {
                return;
            }
            
            if (is_array($obj["payload"])) {
                $payload = $obj["payload"];
            } else {
                $payload = json_decode($obj["payload"], true) ?? [];
            }
            
            if (
                !isset($payload["onClick"]) ||
                !isset($payload["onClick"]["id"]) ||
                !isset(static::$payloadHandlers[$payload["onClick"]["id"]]) ||
                !isset($payload["onClick"]["key"]) ||
                $payload["onClick"]["key"] !== static::$akey
            ) {
               return;
            }
            
            $handler = static::$payloadHandlers[$payload["onClick"]["id"]];
            $obj["payload"] = $payload["payload"] ?? [];
            if (is_callable($handler)) {
                $handler(new MessageEventAnswer($lp, $obj));
            }
        });
    }
    
    /**
     * 
     * @param Button $btn
     * @param callable $handler
     * @return scalar
     */
    public static function addHandler(Button $btn, $handler) {
        static::$payloadHandlers[($id = $btn->getId())] = $handler;
        $payload = $btn->getPayload();
        self::generateKey();
        
        $btn->setPayload([
            "onClick" => [
                "id" => $id,
                "key" => static::$akey
            ],
            "payload" => $payload
        ]);
        return $id;
    }
    
    /**
     * 
     * @param int $id
     */
    public static function removeHandler(int $id): void {
        unset(static::$payloadHandlers[$id]);
    }
    
    /**
     * 
     * @return callable[]
     */
    public static function getHandlers(): array {
        return static::$payloadHandlers;
    }
    
    public static function generateKey(): void {
        if (static::$akey === null) {
            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            static::$akey = substr(str_shuffle($chars), 0, 8);
        }
    }


    /**
     * 
     * @param Button[] $buttons
     */
    public function addButtons(Button ...$buttons): void {
        foreach ($buttons as $button) {
            $this->addButton($button);
        }
    }


    /**
     * 
     * @param Button $button
     * @return self
     */
    public function addButton(Button $button): self {
        $line = $this->getLine();
        $fullSize = false;
        switch ($button->getType()) {
            case Button::TYPE_LOCATION;
            case Button::TYPE_VK_APPS;
            case Button::TYPE_VK_PAY:
                $fullSize = true;
        }
        
        if (count($this->getLine($line)) > $this->getSize() || ($fullSize && count($this->getLine($line)) > 0)) {
            if ($this->lineBreak()) {
                $this->addButton($button);
            }
        } 
        else $this->lines[$line][] = $button;
        return $this;
    }
    
    /**
     * 
     * @return bool
     */
    public function lineBreak(): bool {
        if (count($this->getLines()) > self::MAX_LINES_COUNT) {
            return false;
        } elseif ($this->isInline() && $this->getButtonsCount() > self::INLINE_MAX_BUTTONS) {
            return false;
        } elseif (!$this->isInline() && $this->getButtonsCount() > self::STANDART_MAX_BUTTONS) {
            return false;
        }
        $this->lines[] = [];
        return true;
    }
    
    /**
     * 
     * @param int $line
     * @return Button[]|int|null
     */
    public function getLine(?int $line = null) {
        if ($line === null) {
            return key(array_slice($this->lines, -1, 1, true));
        }
        return $this->lines[$line] ?? null;
    }
    
    /**
     * 
     * @param bool $empty
     * @param bool $json
     * @return array|string
     */
    public function getBody(bool $empty = false, bool $json = true) {
        $body = [
            "one_time" => $this->onetime,
            "inline" => $this->inline
        ];
        
        $buttons = [];
        if (!$empty) {
            foreach ($this->getLines() as $line) {
                $_line = [];
                foreach ($line as $button) {
                    $_line[] = $button->getBody(false);
                }
                $buttons[] = $_line;
            }
        }
        $body["buttons"] = $buttons;
        
        if ($json) {
            $body = (string) json_encode($body);
        }
        return $body;
    }
    
    /**
     * 
     * @param int $size
     * @return self
     */
    public function setSize(int $size = self::LINE_DEFAULT_SIZE) {
        $this->lineSize = $size;
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getSize(): int {
        return $this->lineSize;
    }
    
    /**
     * 
     * @return Button[][]
     */
    public function getLines(): array {
        return $this->lines;
    }
    
    /**
     * 
     * @return int
     */
    public function getButtonsCount(): int {
        $count = 0;
        foreach ($this->getLines() as $line) {
            $count += count($line);
        }
        return $count;
    }
    
    /**
     * 
     * @param bool $inline
     */
    public function reset(bool $inline = false): void {
        $this->inline = $inline;
        $this->lines = [[]];
        if ($this->inline) {
            $this->lineSize = self::INLINE_SIZE;
        }
    }
    
    /**
     * 
     * @return bool
     */
    public function isInline(): bool {
        return $this->inline;
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->getBody();
    }
}