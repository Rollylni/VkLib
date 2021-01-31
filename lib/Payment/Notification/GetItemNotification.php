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
 * @link https://vk.com/dev/payments_getitem
 * @method string getItem()
 * @method string getLang()
 * @method self setTitle(string $title)
 * @method self setPrice(int $price)
 * @method self setPhotoUrl(string $url)
 * @method self setDiscount(int $discount)
 * @method self setItemId(string $id)
 * @method self setExpiration(int $exp)
 */
class GetItemNotification extends PaymentNotification {
    
    /**
     * 
     * @var string[]
     */
    protected $required = ["title", "price"];
}