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

use function strtotime;

/**
 * 
 * @link https://vk.com/dev/objects/appWidget_2?f=9.%20Donation
 */
class DonationWidget extends Widget {
    
    /**
     * 
     * @var string
     */
    protected $type = Widget::TYPE_DONATION;
    
    /**
     * 
     * @param string $title
     * @param string $buttonUrl
     */
    public function __construct(string $title, string $buttonUrl) {
        parent::__construct($title);
        $this->setButtonUrl($buttonUrl);
    }
    
    /**
     * 
     * @param string $text
     */
    public function setText(string $text): self {
        $this->body["text"] = $text;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setTextUrl(string $url): self {
        $this->body["text_url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setButtonUrl(string $url): self {
        $this->body["button_url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param string $start
     * @param string $end
     */
    public function setDate(string $start = "now", string $end = "+7 days"): self {
        $this->body["date"] = [
            "start" => strtotime($start),
            "end" => strtotime($end)
        ];
        return $this;
    }
    
    /**
     * 
     * @param int $goal
     */
    public function setGoal(int $goal): self {
        $this->body["goal"] = $goal;
        return $this;
    }
    
    /**
     * 
     * @param int $funded
     */
    public function setFunded(int $funded): self {
        $this->body["funded"] = $funded;
        return $this;
    }
    
    /**
     * 
     * @param int $backers
     */
    public function setBackers(int $backers): self {
        $this->body["backers"] = $backers;
        return $this;
    }
    
    /**
     * 
     * @param string $currency
     */
    public function setCurrency(string $currency): self {
        $this->body["currency"] = $currency;
        return $this;
    }
}