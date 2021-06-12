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

use VkLib\Widget\Content\Tile;

/**
 * 
 * @link https://vk.com/dev/objects/appWidget?f=4.%20Tiles
 */
class TilesWidget extends Widget {
    
    /**
     * 
     * @var string
     */
    protected $type = Widget::TYPE_TILES;
    
    /**
     * 
     * @param Tile|array $tile
     */
    public function addTile($tile) {
        $this->body["tiles"][] = $this->getContent($tile);
        return $this;
    } 
}