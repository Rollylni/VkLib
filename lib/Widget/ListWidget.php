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

use VkLib\Widget\Content\ListRow;

/**
 * 
 * @link https://vk.com/dev/objects/appWidget?f=2.%20List
 */
class ListWidget extends Widget {
    
    /**
     *
     * @var string 
     */
    protected $type = Widget::TYPE_LIST;
    
    /**
     * 
     * @param ListRow|array $row
     */
    public function addRow($row): self {
        $this->body["rows"][] = $this->getContent($row);
        return $this;
    }
}