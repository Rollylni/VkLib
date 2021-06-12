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

class Error {

    /**
     *
     * @var array
     */
    public $error = [];

    /**
     *
     * @var VkMethod
     */
    protected $method;

    /**
     *
     * @param array $response
     * @param VkMethod $method
     */
    public function __construct(array $response, VkMethod $method) {
        $this->error = $response;
        $this->method = $method;
    }

    /**
     *
     * @return Captcha|null
     */
    public function getCaptcha(): ?Captcha {
        if(isset($this->error["captcha_sid"]) && isset($this->error["captcha_img"]))
            return new Captcha($this->getMethod(), $this->error["captcha_sid"], $this->error["captcha_img"]);
        else
            return null;
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
     * @return int|null
     */
    public function getCode(): ?int {
        return $this->error["error_code"] ?? null;
    }

    /**
     *
     * @return string|null
     */
    public function getMessage(): ?string {
        return $this->error["error_msg"] ?? null;
    }

    /**
     *
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->error["error_desc"] ?? ($this->error["error_description"] ?? null);
    }

    /**
     *
     * @return string|null
     */
    public function getConfirmationText(): ?string {
        return $this->error["confirmation_text"] ?? null;
    }

    /**
     *
     * @return string|null
     */
    public function getRedirectURI(): ?string {
        return $this->error["redirect_uri"] ?? null;
    }

    /**
     *
     * @return array|null
     */
    public function getRequestParams(): ?array {
        return $this->error["request_params"] ?? null;
    }
} 