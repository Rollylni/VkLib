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
namespace VkLib\LongPoll;

use VkLib\Callback\CallbackEvent;

/**
 * 
 * @link https://vk.com/dev/using_longpoll?f=3.%20Event%20Structure
 */
interface LongPollEvent extends CallbackEvent {
    public const MESSAGE_FLAGS_REPLACE = 1;
    public const MESSAGE_FLAGS_SET = 2;
    public const MESSAGE_FLAGS_RESET = 3;
    public const MESSAGE_SEND = 4;
    public const MESSAGE_REDACT = 5;
    
    public const READ_ALL_INCOMING_MESSAGES = 6;
    public const READ_ALL_OUTGOING_MESSAGES = 7;
    
    public const USER_ONLINE = 8;
    public const USER_OFFLINE = 9;
    
    public const PEER_FLAGS_RESET = 10;
    public const PEER_FLAGS_REPLACE = 11;
    public const PEER_FLAGS_SET = 12;
    public const PEER_DELETE_ALL = 13;
    public const PEER_RESTORE_ALL = 14;
    
    public const EDIT_MAJOR_ID = 20;
    public const EDIT_MINOR_ID = 21;
    
    public const CHAT_EDIT = 51;
    public const CHAT_UPDATE = 52;
    
    public const USER_TYPING = 61;
    public const USER_TYPING_IN_CHAT = 62;
    public const USERS_TYPING_IN_CHAT = 63;
    public const USER_RECORDING_VOICE = 64;
    public const USER_CALL = 70;
    
    public const MESSAGES_COUNTER_UPDATE = 80;
    public const NOTIFICATION_SETTINGS_UPDATE = 114;
}