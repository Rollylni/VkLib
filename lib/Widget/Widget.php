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

use VkLib\VkClient;

use function json_encode;
use function str_replace;

/**
 * 
 * @link https://vk.com/dev/apps_widgets
 */
abstract class Widget {
    
    public const TYPE_TEXT = "text";
    public const TYPE_LIST = "list";
    public const TYPE_TABLE = "table";
    public const TYPE_TILES = "tiles";
    public const TYPE_COMPACT_LIST = "compact_list";
    public const TYPE_COVER_LIST = "cover_list";
    public const TYPE_MATCH = "match";
    public const TYPE_MATCHES = "matches";
    public const TYPE_DONATION = "donation";
    
    /**
     * 
     * @var string
     */
    public $code = "return {body};";
    
    /**
     * 
     * @var array
     */
    protected $body = [];
    
    /**
     * 
     * @var strring 
     */
    protected $type = null;
    
    /**
     * 
     * @param string $title
     */
    public function __construct($title) {
        $this->setTitle($title);
    }
    
    /**
     * 
     * @param string $title
     */
    public function setTitle($title) {
        $this->body["title"] = $title;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setTitleUrl($url) {
        $this->body["title_url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param int $counter
     */
    public function setTitleCounter($counter) {
        $this->body["title_counter"] = $counter;
        return $this;
    }
    
    /**
     * 
     * @param string $more
     */
    public function setMore($more) {
        $this->body["more"] = $more;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setMoreUrl($url) {
        $this->body["more_url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param $client - Required Access Token with app_widget Rights
     */
    public function update($client = VkClient::DEFAULT_CLIENT) {
        $client = VkClient::checkClient($client);
        $client->getApi()->appWidgets->update([
            "type" => $this->getType(),
            "code" => str_replace("{body}", $this->getBody(), $this->getCode())
        ]);
    }
    
    /**
     * 
     * @param bool $json
     * @return array|string
     */
    public function getBody($json = true) {
        if ($json) {
            return json_encode($this->body);
        }
        return $this->body;
    }
    
    /**
     * 
     * @return string
     */
    public function getCode() {
        return $this->code;
    }
    
    /**
     * 
     * @param string $code
     * @return self
     */
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getType() {
        return $this->type;
    }
}