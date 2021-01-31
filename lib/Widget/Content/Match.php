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
namespace VkLib\Widget\Content;

use VkLib\Widget\MatchWidget;

class Match extends Content {

    /**
     * 
     * @param string $url
     */
    public function setLiveUrl($url) {
        $this->content["live_url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setUrl($url) {
        $this->content["url"] = $url;
        return $this;
    }
    
    /**
     * 
     * @param string $team
     * @param string $name
     * @param string $descr
     * @param string $iconId
     */
    public function setTeam($team, $name, $descr = null, $iconId = null) {
        $team = MatchWidget::getTeam($team);
        if (!$team) {
            return;
        }
        
        $info = ["name" => $name];
        if ($descr) {
            $info["descr"] = $descr;
        } if ($iconId) {
            $info["icon_id"] = $iconId;
        }
        $this->content[$team] = $info;
        return $this;
    }
    
    /**
     * 
     * @param string $state
     */
    public function setState($state) {
        $this->content["state"] = $state;
        return $this;
    }
    
    /**
     * 
     * @param int $scoreA
     * @param int $scoreB
     */
    public function setScore($scoreA, $scoreB) {
        $this->content["score"] = [
            "team_a" => $scoreA,
            "team_b" => $scoreB
        ];
        return $this;
    }
}