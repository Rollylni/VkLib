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
namespace VkLib\Widget;

use VkLib\Widget\Content\Match;

/**
 * 
 * @link https://vk.com/dev/objects/appWidget_2?f=8.%20Matches
 */
class MatchesWidget extends Widget {
    
    /**
     *
     * @var string 
     */
    protected $type = Widget::TYPE_MATCHES;
    
    /**
     * 
     * @param Match $match
     */
    public function addMatch(Match $match) {
        $this->body["matches"][] = $match->getContent();
        return $this;
    }
}