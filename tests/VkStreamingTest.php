<?php

namespace VkLib\Tests;

use PHPUnit\Framework\TestCase;

use VkLib\VkClient;
use VkLib\VkStreaming;

use Triggers;

class VkStreamingTest extends TestCase {
    
    public function testStreaming() {
        $client = new VkClient("ServiceClient");
        $client->setToken("");
        $stream = new VkStreaming($client);
        $stream->authorization();
        
        $this->assertInstanceOf(Triggers::class, $stream->getHandler());
        $this->assertIsString($stream->getEndpoint());
        $this->assertIsString($stream->getKey());
        return $stream;
    }
    
    /**
     * 
     * @depends testStreaming
     */
    public function testRules(VkStreaming $stream) {
        if (($ruls = $stream->getRules()) !== []) {
            foreach ($ruls as $rule) {
                $stream->deleteRule($rule["tag"]);
            }
        }
        $this->assertEmpty($stream->getRules());
        $stream->addRule("Rule-1", "cat");
        $rules = $stream->getRules();
        
        $this->assertNotEmpty($rules);
        $this->assertArrayHasKey(0, $rules);
        $this->assertArrayHasKey("tag", $rules[0]);
        $this->assertArrayHasKey("value", $rules[0]);
        $this->assertEquals("Rule-1", $rules[0]["tag"]);
        $this->assertEquals("cat", $rules[0]["value"]);
    }
}