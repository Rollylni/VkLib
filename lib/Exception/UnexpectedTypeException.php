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

class UnexpectedTypeException extends \InvalidArgumentException {
    
    /**
     * 
     * @param mixed $value
     * @param string|array
     */
    public function __construct($value, $expected) {
        if (\is_array($expected)) {
            $expected = \implode('", "', $expected);
        }
        
        parent::__construct(
            \sprintf('Expected argument of type "%s", "%s" given', 
            $expected, (\is_object($value) ? \get_class($value) : \gettype($value))
        ));
    }
}