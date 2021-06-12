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

use VkLib\Payment\Notification\GetItemNotification;
use VkLib\Payment\Notification\GetSubscriptionNotification;
use VkLib\Payment\Notification\OrderStatusChangeNotification;
use VkLib\Payment\Notification\SubscriptionStatusChangeNotification;
use VkLib\Payment\Notification\PaymentNotification;

use function md5;
use function ksort;
use function substr;
use function json_encode;
use function json_decode;
use function file_get_contents;

/**
 * 
 * @link https://vk.com/dev/payments_callbacks
 */
abstract class PaymentHandler {
    
    /**
     * 
     * @param string $secretKey
     * @param bool $headers
     */
    public function __construct(string $secretKey, bool $headers = true) {
        if ($headers) {
            $this->setHeaders();
        }
        
        $data = $this->readData();
        if ($this->checkSig($data, $secretKey)) {
            $this->handleData($data);
        } else {
            $this->writeData(new PaymentError(PaymentError::SIGNATURES_ERROR, "Invalid signature!", true));
        }
    }
    
    /**
     * 
     * @param array $data
     */
    public function handleData(array $data = []): void {
        if ($data === []) {
            $data = $this->readData();
        }
        
        $nfs = [
            PaymentNotification::TYPE_GET_ITEM => GetItemNotification::class,
            PaymentNotification::TYPE_GET_SUBSCRIPTION => GetSubscriptionNotification::class,
            PaymentNotification::TYPE_ORDER_STATUS_CHANGE => OrderStatusChangeNotification::class,
            PaymentNotification::TYPE_SUBSCRIPTION_STATUS_CHANGE => SubscriptionStatusChangeNotification::class
        ];
        $type = $data["notification_type"] ?? NULL;
        if (substr($type, -5) === "_test") {
            $type = substr($type, 0, -5);
            if (!isset($nfs[$type])) {
                $this->writeData(new PaymentError(PaymentError::COMMON_ERROR, "Unknown notification! (test_mode)", true));
                return;
            }
            $this->writeData($this->handleTest(new $nfs[$type]($data)));
            return;
        }
        
        if (!isset($nfs[$type])) {
            $this->writeData(new PaymentError(PaymentError::COMMON_ERROR, "Unknown notification!", true));
            return;
        }
        $this->writeData($this->{$type}(new $nfs[$type]($data)));
    }
    
    /**
     * 
     * @param PaymentNotification $nf
     * @return PaymentResponse|array
     */
    public function handleTest(PaymentNotification $nf) {
        return new PaymentError(PaymentError::COMMON_ERROR, "test mode not processed!", false);
    }
    
    /**
     * 
     * @param GetItemNotification $nf
     * @return PaymentResponse|array
     */
    public function get_item(GetItemNotification $nf) {
        return $nf;
    }
    
    /**
     * 
     * @param GetSubscriptionNotification $nf
     * @return PaymentResponse|array
     */
    public function get_subscription(GetSubscriptionNotification $nf) {
        return $nf;
    }
    
    /**
     * 
     * @param OrderStatusChangeNotification $nf
     * @return PaymentResponse|array
     */
    public function order_status_change(OrderStatusChangeNotification $nf) {
        return $nf;
    }
    
    /**
     * 
     * @param SubscriptionStatusChangeNotification $nf
     * @return PaymentResponse|array
     */
    public function subscription_status_change(SubscriptionStatusChangeNotification $nf) {
        return $nf;
    }
    
    /**
     * 
     * @link https://vk.com/dev/payments_callbacks?f=3.%20Signature%20verification
     * @param array $data
     * @param string $secretKey
     * @return bool
     */
    public static function checkSig(array $data, string $secretKey): bool {
        $sig = $data["sig"];
        unset($data["sig"]);
        ksort($data);
        $str = "";
        foreach ($data as $k => $v) {
            $str .= $k.'='.$v;
        }
        return $sig === md5($str.$secretKey);
    }
    
    /**
     * 
     * @param array|PaymentResponse $data
     */
    public static function writeData($data): void {
        if ($data instanceof PaymentResponse) {
            $data = $data->getBody();
        }
        print json_encode($data);
    }

    /**
     * 
     * @return mixed[]|NULL
     */
    public static function readData(): ?array {
        return json_decode(file_get_contents("php://input"), true);
    }
    
    public static function setHeaders(): void {
        header("Content-Type: application/json; encoding=utf-8");
    }
}