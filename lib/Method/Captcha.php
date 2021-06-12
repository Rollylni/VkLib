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
namespace VkLib\Method;

use function is_callable;
use function file_get_contents;
use function file_put_contents;

class Captcha {
    
    /**
     *
     * @var string
     */
    public $sid = null;

    /**
     *
     * @var string
     */
    public $img = null;

    /**
     *
     * @var VkMethod
     */
    protected $method;
    
    /**
     * 
     * @var Closure[]
     */
    public static $handlers = [];
    
    /**
     * Captcha constructor.
     *
     * @param VkMethod $method
     * @param string|null $sid
     * @param string|null $img
     */
    public function __construct(VkMethod $method, ?string $sid, ?string $img) {
        $this->method = $method;
        $this->sid = $sid;
        $this->img = $img;
    }

    /**
     *
     * @param string $patch
     */
    public function saveImage($patch): void {
        file_put_contents($patch, file_get_contents($this->getImage()));
    }

    /**
     *
     * @param string $captcha
     * @see VkMethod::__call()
     * @return VkMethod
     */
    public function input($captcha): VkMethod {
        return $this->getMethod()->setCaptchaSid($this->getSid())->setCaptchaKey($captcha);
    }

    /**
     *
     * @return string
     */
    public function getImage(): ?string {
        return $this->img;
    }

    /**
     *
     * @return string
     */
    public function getSid(): ?string {
        return $this->sid;
    }
    
    /**
     * 
     * @return VkMethod
     */
    public function getMethod(): VkMethod {
        return $this->method;
    }
    
    /**
     * 
     * @internal
     * @uses VkMethod::call()
     * @param Captcha $cap
     * @return bool
     */
    public static function handle(Captcha $cap): bool {
        $handled = false;
        foreach (static::$handlers as $handler) {
            if (is_callable($handler)) {
                if ($handler($cap) === true) {
                    $handled = true;
                }
            }
        }
        return $handled;
    }
    
    /**
     * 
     * @api
     * @param Closure|callable $handler
     */
    public static function setHandler($handler): void {
        static::$handlers[] = $handler;
    }
}