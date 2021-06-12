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

/**
 * 
 * @link https://vk.com/dev/bots_docs_4?f=7.%2B%D0%98%D0%BD%D1%82%D0%B5%D0%BD%D1%82%D1%8B
 */
interface Intent {
    public const DEFAULT = "default";
    public const PROMO_NEWSLETTER = "promo_sletter";
    public const NON_PROMO_NEWSLETTER = "non_promo_newsletter";
    public const BOT_AD_INVITE = "bot_ad_invite";
    public const BOT_AD_PROMO = "bot_ad_promo";
    public const CONFIRMED_NOTIFICATION = "confirmed_notification";
    public const GAME_NOTIFICATION = "game_notification";
    public const PURCHASE_UPDATE = "purchase_update";
    public const ACCOUNT_UPDATE = "account_update";
    public const CUSTOMER_SUPPORT = "customer_support";
}