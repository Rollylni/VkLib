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
 * @link https://vk.com/dev/payments_status
 * @method int getDate()
 * @method string getStatus()
 * @method string getItem()
 * @method int getItemId()
 * @method string getItemTitle()
 * @method string getItemPhotoUrl()
 * @method string getItemPrice()
 * @method string getItemDiscount()
 * @method self setOrderId(int $id)
 * @method self setAppOrderId(int $id)
 */
class OrderStatusChangeNotification extends PaymentNotification {
    
    public const STATUS_CHARGEABLE = "chargeable";
    public const STATUS_REFUNDED = "refunded";
    
    /**
     * 
     * @var string[]
     */
    protected $required = ["order_id"];
}