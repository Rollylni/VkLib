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
namespace VkLib\Payment\Notification;

use VkLib\Payment\PaymentResponse;
use VkLib\Method\VkMethod;

use substr;

/**
 * 
 * @method string getNotificationType()
 * @method int getAppId()
 * @method int getUserId()
 * @method int getReceiverId()
 * @method int getOrderId()
 * @method int getSubscriptionId()
 * @method string getVersion()
 * @method string getSig()
 */
abstract class PaymentNotification implements PaymentResponse {
    
    public const TYPE_GET_ITEM = "get_item";
    public const TYPE_GET_SUBSCRIPTION = "get_subscription";
    public const TYPE_SUBSCRIPTION_STATUS_CHANGE = "subscription_status_change";
    public const TYPE_ORDER_STATUS_CHANGE = "order_status_change";
            
    /**
     * 
     * @var array
     */
    public $input = [];
    
    /**
     * 
     * @var array
     */
    public $output = [];
    
    /**
     * Required Parameters
     * 
     * @var array
     */
    protected $required = [];
    
    /**
     * 
     * @param array $input
     */
    public function __construct(array $input = []) {
        $this->input = $input;
    }
    
    /**
     * 
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params) {
        $pref = substr($method, 0, 3);
        $noPref = substr($method, 3);
        if ($pref === "get") {
            return $this->getField(VkMethod::formatParameter($noPref));
        } elseif ($pref === "set") {
            return $this->setField(VkMethod::formatParameter($noPref), $params[0] ?? null);
        }
    }
    
    /**
     * 
     * @param string $field
     * @param mixed $value
     * @return self
     */
    public function setField($field, $value) {
        $this->output[$field] = $value;
        return $this;
    }
    /**
     * 
     * @param string $field
     * @return mixed
     */
    public function getField($field) {
        return $this->input[$field] ?? null;
    }
    
    /**
     * 
     * @return array
     */
    public function getInput() {
        return $this->input;
    }
    
    /**
     * 
     * @return array
     */
    public function getOutput() {
        return $this->output;
    }
    
    /**
     * 
     * @return array
     */
    public function getRequired() {
        return $this->required;
    }
    
    /**
     * 
     * @return array
     */
    public function getBody(): array {
        return [
            "response" => $this->getOutput()
        ];
    }
}