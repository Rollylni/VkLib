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

use function method_exists;
use function json_decode;
use function file_get_contents;
use function getallheaders;
use function gmdate;
use function header;
use function trim;
use function time;

#assert(\PHP_SAPI !== "cli", "web-server required!");

/**
 * 
 * @link https://vk.com/dev/callback_api
 */
abstract class CallbackHandler {
    
    /** @var string*/
    public $token = "";
    
    /**
     * CallbackServer constructor.
     * 
     * @param string $token
     */
    public function __construct(?string $token = null) {
        if ($token !== null) {
            $this->token = $token;
        }
        
        if ($this->isPost()) {
            $input = $this->readData();
            if ($input) {
                $this->handle($input);
            }
        }
    }
    
    /**
     * send token for confirmation
     */
    public function confirmation(): void {
        $this->writeData($this->token);
    }
    
    /**
     * 
     * @since 0.7.1
     * 
     * @param string   $type
     * @param mixed[]  $object
     * @param integer  $groupId
     * @param string   $secretKey
     * @return void
     */
    public function onHandle(string $type, array $object, int $groupId, ?string $secretKey): void {}


    /**
     * Event handling
     * 
     * @param array $data
     */
    public function handle(array $data): void {
        $type = $data["type"] ?? null;
        $obj = $data["object"] ?? null;
        $gid = $data["group_id"] ?? null;
        $key = $data["secret"] ?? null;
        
        if ($type === "confirmation") {
            $this->confirmation($gid);
            return;
        }
        
        //                                 // for camel case, example "newMessage"        
        if (method_exists($this, $type) or method_exists($this, $type = trim($type, '_'))) {
            $this->{$type}($obj, $gid, $key);
        } 
        
        $this->onHandle($type, $obj, $gid, $key);
        $this->writeData("ok");
    }
    
    /**
     * Delete Callback Server
     * 
     * @link https://vk.com/wall-1_397761
     * 
     * @since 0.7.1
     */
    public static function remove(): void {
        static::writeData(__METHOD__);
    }
    
    /**
     * Repeat request later
     * 
     * @since 0.7.1
     * 
     * @param int $time in seconds, maximum 3 hours
     */
    public static function retryAfter(int $time = 10): void {
        if ($time >= (60 * 60 * 3)) {
            $time = 60 * 60 * 3;
        }
        header("Retry-After: " . gmdate("D, d M Y H:i:s T", time() + $time), true, 503);
    }
    
    /**
     * Failed attempts
     * 
     * @since 0.7.1
     * 
     * @return int
     */
    public static function getRetryCounter(): int {
        return getallheaders()["X-Retry-Counter"] ?? 0;
    }
    
    /**
     * 
     * @since 0.7.1
     * 
     * @return bool
     */
    public static function isPost(): bool {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }
    
    /**
     * Write Response
     * 
     * @since 0.7.1
     * 
     * @param scalar $data
     */
    public static function writeData($data): void {
        print $data;
    }
    
    /**
     * Read Post Data
     * 
     * @return array|null
     */
    public static function readData(): ?array {
        return json_decode(file_get_contents("php://input"), true);
    }
}