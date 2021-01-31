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

/**
 * 
 * @link https://vk.com/dev/payments_subscriptionstatus
 * @method string getCancelReason()
 * @method string getItemId()
 * @method int getItemPrice()
 * @method string getStatus()
 * @method int getNextBillTime()
 * @method int getPendingCancel()
 * @method self setSubscriptionId(int $id)
 * @method self setAppOrderId(int $id)
 */
class SubscriptionStatusChangeNotification extends PaymentNotification {
    
    public const USER_DECISION_REASON = "user_decision";
    public const APP_DECISION_REASON = "app_decision";
    public const PAYMENT_FAIL_REEASON = "payment_fail";
    public const UNKNOWN_REASON = "unknown";
    
    public const STATUS_CHARGEABLE = "chargeable";
    public const STATUS_ACTIVE = "active";
    public const STATUS_CANCELLED = "cancelled";
    
    /**
     * 
     * @var string[]
     */
    protected $required = ["subscription_id"];
}