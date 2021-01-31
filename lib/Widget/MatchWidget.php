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

use function in_array;

/**
 * 
 * @link https://vk.com/dev/objects/appWidget_2?f=7.%20Match
 */
class MatchWidget extends Widget {
    
    /**
     *
     * @var string 
     */
    protected $type = Widget::TYPE_MATCH;
    
    /**
     * 
     * @param string $team
     * @param string $event
     * @param int $minute
     */
    public function addEvent($team, $event, $minute) {
        $team = $this->getTeam($team);
        if (!$team) {
            return;
        }
        $this->body["match"]["events"][$team] = [
            "event" => $event,
            "minute" => $minute
        ];
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
        $team = $this->getTeam($team);
        if (!$team) {
            return;
        }
        
        $info = ["name" => $name];
        if ($descr) {
            $info["descr"] = $descr;
        } if ($iconId) {
            $info["icon_id"] = $iconId;
        }
        $this->body["match"][$team] = $info;
        return $this;
    }
    
    /**
     * 
     * @param string $state
     */
    public function setState($state) {
        $this->body["match"]["state"] = $state;
        return $this;
    }
    
    /**
     * 
     * @param int $scoreA
     * @param int $scoreB
     */
    public function setScore($scoreA, $scoreB) {
        $this->body["match"]["score"] = [
            "team_a" => $scoreA,
            "team_b" => $scoreB
        ];
        return $this;
    }
    
    /**
     *
     * @param string $team
     * @return string|false
     */
    public static function getTeam($team) {
        return in_array($team, ['a', 'b']) ? "team_$team" : false;
    }
}