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
namespace VkLib\Callback;

class CallbackServer {
    
    /**
     * 
     * @var array
     */
    private $res;
    
    /**
     * 
     * @var CallbackManager|null
     */
    private $manager;
    
    public const STATUS_UNCONFIGURED = "unconfigured";
    public const STATUS_FAILED = "failed";
    public const STATUS_WAIT = "wait";
    public const STATUS_OK = "ok";
    
    /**
     * 
     * @param array $res
     * @param CallbackManager|null
     */
    public function __construct($res, ?CallbackManager $manager = null) {
        $this->res = $res;
        $this->manager = $manager;
    }
    
    /**
     * 
     * @return self
     */
    public function update() {
        if ($this->getManager() instanceof CallbackManager) {
            $server = $this->getManager()->getServer($this->getId());
            if ($server instanceof CallbackServer) {
                $this->res = $server->getBody();
            }
        }
        return $this;
    }
    
    /**
     * 
     * @param string $new_url
     * @param string $new_title
     * @param string $new_secret_key
     * @return self
     */
    public function edit($new_url, $new_title = false, $new_secret_key = null) {
        if ($this->getManager() instanceof CallbackManager) {
            $this->getManager()->editServer($this->getId(), $new_url, $new_title, $new_secret_key);
            $this->update();
        }
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getSettings() {
        if ($this->getManager() instanceof CallbackManager) {
            return $this->getManager()->getServerSettings($this->getId());
        }
        return [];
    }
    
    /**
     * 
     * @param (string|int)[] $settings
     * @return self
     */
    public function setSettings($settings) {
        if ($this->getManager() instanceof CallbackManager) {
            $this->getManager()->setServerSettings($this->getId(), $settings);
        }
        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public function delete() {
        if ($this->getManager() instanceof CallbackManager) {
            $this->getManager()->deleteServer($this->getId());
        }
        return $this;
    }
    
    /**
     * 
     * @param string $version
     * @return self
     */
    public function setApiVersion($version) {
        $this->setSettings([
            "api_version" => $version
        ]);
        return $this;
    }
    /**
     * 
     * @return string|null
     */
    public function getApiVersion(): ?string {
        return $this->getSettings()["api_version"] ?? null;
    }
    
    /**
     * 
     * @return int[]|null
     */
    public function getEventSettings(): ?array {
        return $this->getSettings()["events"] ?? null;
    }
    
    /**
     * 
     * @return int|null
     */
    public function getId(): ?int {
        return $this->res["id"] ?? null;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->res["title"] ?? null;
    }
    
    /**
     * 
     * @return int|null
     */
    public function getCreator(): ?int {
        return $this->res["creator_id"] ?? null;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getUrl(): ?string {
        return $this->res["url"] ?? null;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getSecret(): ?string {
        return $this->res["secret_key"] ?? null;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getStatus(): ?string {
        return $this->res["status"] ?? null;
    }
    
    /**
     * 
     * @return mixed[] 
     */
    public function getBody() {
        return $this->res;
    }
    
    /**
     * 
     * @return CallbackManager|null
     */
    public function getManager(): ?CallbackManager {
        return $this->manager;
    }
}