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

use VkLib\VkClient;
use VkLib\VkApi;

use function method_exists;
use function count;
use function is_int;

/**
 * 
 * @link https://vk.com/dev/callback_api?f=1.3.%20Configuration%20via%20API
 */
class CallbackManager {
    
    /**
     * 
     * @var VkApi
     */
    protected $api;
    
    /**
     * 
     * @param int $gid Group Id
     * @param string|VkClient $client Group Token or Admin Token
     */
    public function __construct(int $gid, $client = VkClient::DEFAULT_CLIENT) {
        $this->api = clone VkClient::checkClient($client)->getApi();
        $this->api->addParam("group_id", $gid);
    }
    
    /**
     * 
     * @param string $url
     * @param string $title
     * @param string $secret
     * @return int|null
     */
    public function addServer(string $url, ?string $title = null, ?string $secret = null): ?int {
        if (!$title) {
            $servers = $this->getServersCount();
            $title = "Server ".($servers + 1);
        }
        
        $params = [
            "url" => $url,
            "title" => $title
        ];
        
        if ($secret) {
            $params["secret_key"] = $secret;
        }
        return $this->getApi()->groups->addCallbackServer($params)->getServerId();
    }
    
    /**
     * 
     * @param string|int $id Title or Id
     */
    public function deleteServer($id): void {
        $server = $this->getServer($id);
        if ($server instanceof CallbackServer) {
            $this->getApi()->groups->deleteCallbackServer([
                "server_id" => $server->getId()
            ]);
        }
    }
    
    /**
     * 
     * @param string|int $id
     * @param string $new_url
     * @param string $new_title
     * @param string $new_secret_key
     */
    public function editServer($id, string $new_url, ?string $new_title = null, ?string $new_secret_key = null): void {
        $server = $this->getServer($id);
        if (!($server instanceof CallbackServer)) {
            return;
        }
        
        if (!$new_title) {
            $new_title = $server->getTitle();
        }
        
        $params = [
            "server_id" => $server->getId(),
            "url" => $new_url,
            "title" => $new_title,
        ];
        
        if ($new_secret_key) {
            $params["secret_key"] = $new_secret_key;
        }
        $this->getApi()->groups->editCallbackServer($params);
    }
    
    /**
     * 
     * @param string|int $id
     * @param (string|int)[] $settings
     */
    public function setServerSettings($id, array $settings): void {
        $server = $this->getServer($id);
        if ($server instanceof CallbackServer) {
            $settings["server_id"] = $server->getId();
            $this->getApi()->groups->setCallbackSettings($settings);
        }
    }
    
    /**
     * 
     * @param string|int $id
     * @return array
     */
    public function getServerSettings($id): array {
        $server = $this->getServer($id);
        if ($server === null) return [];
        return $this->getApi()->groups->getCallbackSettings([
            "server_id" => $server->getId()
        ])->json();
    }
    
    /**
     * 
     * @return CallbackServer|null
     */
    public function getLastServer(): ?CallbackServer {
        $servers = $this->getServers();
        $index = count($servers) - 1;
        return $servers[$index] ?? null;
    }
    
    /**
     * 
     * @param string|int $id
     * @return CallbackServer|null
     */
    public function getServer($id): ?CallbackServer {
        $mode = "getTitle";
        if (is_int($id)) {
            $mode = "getId";
        }
        return $this->getServers(false, $mode, $id)[0] ?? null;
    }

    /**
     * 
     * @param bool $items
     * @param string $mode
     * @param mixed $q
     * @return (CallbackServer|array)[]
     */
    public function getServers(bool $items = false, ?string $mode = null, $q = null): array {
        $res = $this->getApi()->groups->getCallbackServers()->json();
        if (!isset($res["items"])) return [];
        if ($items) {
            return $res["items"];
        }
        
        $servers = [];
        foreach ($res["items"] as $item) {
            $server = new CallbackServer($item, $this);
            if ($mode !== null and $q !== null) {
                if (method_exists($server, $mode)) {
                    if ($server->{$mode}() === $q) {
                        $servers[] = $server;
                    }
                }
            } else {
                $servers[] = $server;
            }
        }
        return $servers;
    }
    
    /**
     * 
     * @return int
     */
    public function getServersCount(): int {
        return $this->getApi()->groups->getCallbackServers()->json()["count"] ?? 0;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getConfirmationCode(): ?string {
        return $this->getApi()->groups->getCallbackConfirmationCode()->json()["code"] ?? null;
    }
    
    /**
     * 
     * @return VkApi
     */
    public function getApi(): VkApi {
        return $this->api;
    }
}