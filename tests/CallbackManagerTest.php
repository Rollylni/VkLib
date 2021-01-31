<?php

namespace VkLib\Tests;

use PHPUnit\Framework\TestCase;
use VkLib\Callback\CallbackManager;
use VkLib\Callback\CallbackServer;
use VkLib\VkClient;

class CallbackManagerTest extends TestCase {
    
    public function testCallbackManager() {
        $client = new VkClient("CBManagerToken");
        $client->setToken("");
        $cb = new CallbackManager(188043878, $client);
        
        $this->assertArrayNotHasKey("group_id", $client->getApi()->params);
        
        return $cb;
    }
    
    /**
     * 
     * @depends testCallbackManager
     */
    public function testAddServer(CallbackManager $cb) {
        $cb->addServer("https://poshelnahuy.com", "TestServer");
        $server = $cb->getServer("TestServer");
        $this->assertInstanceOf(CallbackServer::class, $server);
        $this->assertEquals("TestServer", $server->getTitle());
                
        return $server;
    }
    
    /**
     * 
     * @depends testAddServer
     */
    public function testEditServer(CallbackServer $server) {
        $server->edit($server->getUrl(), "EditedServer");
        $this->assertEquals("EditedServer", $server->getTitle());
        
        return $server;
    }
    
    /**
     * 
     * @depends testEditServer
     */
    public function testDeleteServer(CallbackServer $server) {
        $server->delete();
        $this->assertNull($server->getManager()->getServer($server->getId()));
    }
}