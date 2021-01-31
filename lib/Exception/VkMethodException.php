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
namespace VkLib\Exception;

use VkLib\Method\Error;

class VkMethodException extends VkLibException {
    /**
     *
     * @var Error
     */
    public $error;

    /**
     *
     * @param string $message
     * @param Error $error
     */
    public function __construct($message, Error $error) {
        parent::__construct($message);
        $this->error = $error;
    }

    /**
     *
     * @return ApiError
     */
    public function getError(): Error {
        return $this->error;
    }
}