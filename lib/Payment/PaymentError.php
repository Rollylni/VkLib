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
namespace VkLib\Payment;

/**
 * 
 * @link https://vk.com/dev/payments_callbacks?f=4.%20Response%20format%20in%20case%20of%20error
 */
class PaymentError implements PaymentResponse {
    
    public const COMMON_ERROR = 1;
    public const TEMPORERY_DATABASE_ERROR = 2;
    public const SIGNATURES_ERROR = 10;
    public const REQUEST_ERROR = 11;
    public const PRODUCT_NOT_EXIST = 20;
    public const PRODUCT_OUT_OF_STOCK = 21;
    public const USER_NOT_EXIST = 22;
            
    /**
     * 
     * @var int
     */
    public $errorCode;
    
    /**
     * 
     * @var string
     */
    public $errorMsg;
    
    /**
     * 
     * @var bool
     */
    public $critical;
    
    /**
     * 
     * @param int $code
     * @param string $msg
     * @param bool $critical
     */
    public function __construct($code, $msg = null, $critical = false) {
        $this->errorCode = $code;
        $this->errorMsg = $msg;
        $this->critical = $critical;
    }
    
    /**
     * 
     * @param int $code
     * @return self
     */
    public function setCode($code) {
        $this->errorCode = $code;
        return $this;
    }
    
    /**
     * 
     * @param string $msg
     * @return self
     */
    public function setMessage($msg) {
        $this->errorMsg = $msg;
        return $this;
    }
    
    /**
     *
     * @param bool $critical
     * @return self
     */
    public function setCritical($critical = true) {
        $this->critical = $critical;
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getBody(): array {
        return [
            "error" => [
                "error_code" => $this->errorCode,
                "error_msg" => $this->errorMsg,
                "critical" => $this->critical
            ]
        ];
    }
}