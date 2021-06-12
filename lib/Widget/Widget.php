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

use VkLib\Exception\UnexpectedTypeException;
use VkLib\Widget\Content\Content;
use VkLib\VkClient;

use function json_encode;
use function str_replace;
use function is_array;

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
    public function __construct(string $title) {
        $this->setTitle($title);
    }
    
    /**
     * 
     * @param string $title
     */
    public function setTitle(string $title): self {
        $this->body["title"] = $title;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setTitleUrl(string $url): self {
        $this->body["title_url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param int $counter
     */
    public function setTitleCounter(int $counter): self {
        $this->body["title_counter"] = $counter;
        return $this;
    }
    
    /**
     * 
     * @param string $more
     */
    public function setMore(string $more): self {
        $this->body["more"] = $more;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setMoreUrl(string $url): self {
        $this->body["more_url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param string|VkClient $client - Required Access Token with app_widget Rights
     */
    public function update($client = VkClient::DEFAULT_CLIENT): void {
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
    public function getBody(bool $json = true) {
        if ($json) {
            return json_encode($this->body);
        }
        return $this->body;
    }
    
    /**
     * 
     * @param array|Content $val
     * @return array
     */
    public static function getContent($val): array {
        if ($val instanceof Content) {
            return $val->getContent();
        } elseif (is_array($val)) {
            return $val;
        } else {
            throw new UnexpectedTypeException($val, [Content::class, "array"]);
        }
    } 
    
    /**
     * 
     * @return string
     */
    public function getCode(): string {
        return $this->code;
    }
    
    /**
     * 
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self {
        $this->code = $code;
        return $this;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getType(): ?string {
        return $this->type;
    }
}