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
namespace VkLib\LongPoll;

use VkLib\VkClient;
use VkLib\Method\VkMethod;
use VkLib\Exception\VkMethodException;

use function is_array;

/**
 * 
 * @link https://vk.com/dev/using_longpoll
 */
class LongPollUser extends LongPoll {
    
    public const CURRENT_VERSION = 3;
    
    public const MODE_GET_ATTACHMENTS = 2;
    public const MODE_GET_EXTENDED = 8;
    public const MODE_GET_PTS = 32;
    public const MODE_GET_EXTRA_ONLINE = 64;
    public const MODE_GET_RANDOM_ID = 128;
    
    public const CHAT_TITLE_CHANGED_EVENT = 1;
    public const CHAT_PHOTO_CHANGED_EVENT = 2;
    public const CHAT_ADMIN_ADDED_EVENT = 3;
    public const CHAT_MESSAGE_PINNED_EVENT = 4;
    public const CHAT_USER_JOINED_EVENT = 5;
    public const CHAT_USER_LEFT_EVENT = 6;
    public const CHAT_USER_KICKED_EVENT = 7;
    public const CHAT_ADMIN_REMOVED_EVENT = 8;
    
    public const MESSAGE_UNREAD_FLAG = 1;
    public const MESSAGE_OUTBOX_FLAG = 2;
    public const MESSAGE_REPLIED_FLAG = 4;
    public const MESSAGE_IMPORTANT_FLAG = 8;
    /** @deprecated*/
    public const MESSAGE_CHAT_FLAG = 16;
    public const MESSAGE_FRIENDS_FLAG = 32;
    public const MESSAGE_SPAM_FLAG = 64;
    public const MESSAGE_DELETED_FLAG = 128;
    /** @deprecated*/
    public const MESSAGE_FIXED_FLAG = 256;
    /** @deprecated*/
    public const MESSAGE_MEDIA_FLAG = 512;
    public const MESSAGE_HIDDEN_FLAG = 65536;
    public const MESSAGE_DELETE_FOR_ALL_FLAG = 131072;
    public const MESSAGE_NOT_DELIVERED_FLAG = 262144;
    
    public const PLATFORM_MOBILE = 1;
    public const PLATFORM_IPHONE = 2;
    public const PLATFORM_IPAD = 3;
    public const PLATFORM_ANDROID = 4;
    public const PLATFORM_WPHONE = 5;
    public const PLATFORM_WINDOWS = 6;
    public const PLATFORM_WEB = 7;
    
    public const PEER_IMPORTANT_FLAG = 1;
    public const PEER_UNANSWERED_FLAG = 2;
    
    public const PARAM_NEED_PTS = "need_pts";
    public const PARAM_GROUP_ID = "group_id";
    public const PARAM_LP_VERSION = "lp_version";

    /**
     *
     * @var int
     */
    public $version = self::CURRENT_VERSION;

    /**
     *
     * @var int
     */
    public $mode = self::MODE_GET_EXTENDED;
    
    /**
     * 
     * @var mixed[]
     */
    public $params = [];
    /**
     * 
     * @var int
     */
    private $pts = null;
    
    /**
     * 
     * @param string|VkClient $client
     * @param int $mode
     * @param int $version
     */
    public function __construct($client = VkClient::DEFAULT_CLIENT, int $mode = self::MODE_GET_EXTENDED,
                                int $version = self::CURRENT_VERSION) {
        parent::__construct($client);
        $this->version = $version;
        $this->mode = $mode;
        
        $this->getHandler()->add(self::ON_FAILED, function($failed) {
            if ($failed["failed"] === self::FAILED_INVALID_VERSION) {
                $this->version = $failed["max_version"] ?? self::CURRENT_VERSION;
            }
        });
    }
    
    /**
     * 
     * @param array $params
     * @throws VkMethodException
     * @return array
     */
    public function getHistory(array $params = []): array {
        $params["ts"] = $this->ts;
        $params["pts"] = $this->pts;
        return $this->getClient()->getApi()->messages->getLongPollHistory($params)->json();
    }
    
    /**
     * 
     * @return array
     */
    public function getEvents(): array {
        $res = VkMethod::JSON($this->getClient()->getHttpClient()->post("https://{$this->server}", ["form_params" => [
            "act" => "a_check",
            "version" => $this->version,
            "mode" => $this->mode,
            "wait" => $this->wait,
            "key" => $this->key,
            "ts" => $this->ts
        ]]));
        
        if (!isset($res["updates"])) {
            return $res;
        }
        
        // this idea was taken from: https://github.com/python273/vk_api
        // :)
        $extra_fields = ["peer_id", "timestamp", "text", "extra_fields", "attachments", "random_id"];
        $events = [
            LongPollEvent::MESSAGE_FLAGS_REPLACE => array_merge(["message_id", "flags"], $extra_fields),
            LongPollEvent::MESSAGE_FLAGS_SET => array_merge(["message_id", "mask"], $extra_fields),
            LongPollEvent::MESSAGE_FLAGS_RESET => array_merge(["message_id", "mask"], $extra_fields),
            LongPollEvent::MESSAGE_SEND => array_merge(["message_id", "flags"], $extra_fields),
            LongPollEvent::MESSAGE_REDACT => array_merge(["message_id", "mask"], $extra_fields),
            LongPollEvent::READ_ALL_INCOMING_MESSAGES => ["peer_id", "local_id"],
            LongPollEvent::READ_ALL_OUTGOING_MESSAGES => ["peer_id", "local_id"],
            LongPollEvent::USER_ONLINE => ["user_id", "extra", "timestamp"],
            LongPollEvent::USER_OFFLINE => ["user_id", "flags", "timestamp"],
            LongPollEvent::PEER_FLAGS_RESET => ["peer_id", "mask"],
            LongPollEvent::PEER_FLAGS_REPLACE => ["peer_id", "flags"],
            LongPollEvent::PEER_FLAGS_SET => ["peer_id", "mask"],
            LongPollEvent::PEER_DELETE_ALL => ["peer_id", "local_id"],
            LongPollEvent::PEER_RESTORE_ALL => ["peer_id", "local_id"],
            LongPollEvent::EDIT_MAJOR_ID => ["peer_id", "major_id"],
            LongPollEvent::EDIT_MINOR_ID => ["peer_id", "minor_id"],
            LongPollEvent::CHAT_EDIT => ["chat_id", "self"],
            LongPollEvent::CHAT_UPDATE => ["type_id", "peer_id", "info"],
            LongPollEvent::USER_TYPING => ["user_id", "flags"],
            LongPollEvent::USER_TYPING_IN_CHAT => ["user_id", "chat_id"],
            LongPollEvent::USERS_TYPING_IN_CHAT => ["user_ids", "peer_id", "total_count", "ts"],
            LongPollEvent::USER_RECORDING_VOICE => ["user_ids", "peer_id", "total_count", "ts"],
            LongPollEvent::USER_CALL => ["user_id", "call_id"],
            LongPollEvent::MESSAGES_COUNTER_UPDATE => ["count"],
            LongPollEvent::NOTIFICATION_SETTINGS_UPDATE => ["peer_id", "sound", "disabled_until"]
        ];
        
        $evs = [];
        foreach ($res["updates"] as $event) {
            $combine = [];
            $type = array_shift($event);
            if(isset($events[$type])) {
                $e = $events[$type];
                foreach($event as $k => $v) {
                    if(!isset($e[$k]))
                        continue;
                    $combine[$e[$k]] = $v;
                }
            } else {
                $combine = $event;
            }
            $evs[] = [
                "type" => $type,
                "object" => $combine
            ];
        }
        $res["updates"] = $evs;
        return $res;
    }
    
    /**
     *
     * @throws VkMethodException
     * @return array
     */
    public function getServer(): array {
        $res = $this->getClient()->getApi()->messages->getLongPollServer($this->getParams())->json();
        if (isset($res["pts"])) {
            $this->pts = $res["pts"];
        }
        return $res;
    }
    
    /**
     * 
     * @return int|null
     */
    public function getPts(): ?int {
        return $this->pts;
    }
    
    /**
     *
     * @param int $version
     * @return self
     */
    public function setVersion(int $version): self {
        $this->version = $version;
        return $this;
    }

    /**
     *
     * @return int
     */
    public function getVersion(): int {
        return $this->version;
    }

    /**
     *
     * @param int $mode
     * @return self
     */
    public function setMode(int $mode): self {
        $this->mode = $mode;
        return $this;
    }

    /**
     *
     * @return int
     */
    public function getMode(): int {
        return $this->mode;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setParam(string $key, $value): self {
        $this->params[$key] = $value;
        return $this;
    }
    
    /**
     * 
     * @return mixed[]
     */
    public function getParams(): array {
        return $this->params;
    }
}