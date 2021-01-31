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
namespace VkLib\Upload;

class Video extends Upload {
    
    /**
     * 
     * @param array $params
     * @return array 
     */
    public function getServer(array $params = []) {
        return $this->getClient()->getApi()->video->save($params)->json();
    }
    
    /**
     * 
     * @param array $params
     * @return array
     */
    public function upload(array $params = []): array {
        $url = $this->getServer($params)["upload_url"];
        return $this->post($url, "video_file");
    }
    
    /**
     * 
     * @param string $src
     */
    public function setVideo($src) {
        return $this->addFile($src);
    }
}