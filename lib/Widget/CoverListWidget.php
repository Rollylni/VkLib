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

use VkLib\Widget\Content\CoverRow;

/**
 * 
 * @link https://vk.com/dev/objects/appWidget_2?f=6.%20Cover%20List
 */
class CoverListWidget extends Widget {
    
    /**
     *
     * @var string 
     */
    protected $type = Widget::TYPE_COVER_LIST;
    
    /**
     * 
     * @param CoverRow|array $row
     */
    public function addRow($row): self {
        $this->body["rows"][] = $this->getContent($row);
        return $this;
    }
}