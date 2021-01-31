<?php

namespace VkLib\Tests;

use PHPUnit\Framework\TestCase;

use VkLib\VkApi;
use VkLib\VkClient;
use VkLib\Upload\PhotoAlbum;
use VkLib\Upload\Document;
use VkLib\Upload\DocumentWall;
use VkLib\Upload\DocumentMessages;

(new VkClient("Uploads"))->setToken("");

class UploadTest extends TestCase {
    
    public function testAlbumPhotoUpload() {
        $photo = new PhotoAlbum("Uploads");
        $photo->addFile("C:\Users\HPg018ur\Pictures\Без названия.jpg");
        $photo->albumId = 277098178;
        $res = $photo->upload();
        $this->assertNotEmpty($res);
    }
    
    public function testDocumentUpload() {
        $doc = new Document("Uploads");
        $doc->setDocument("C:\Users\HPg018ur\Documents\NetBeansProjects\Dudoser\index.php");
        $this->assertNotEmpty($doc->upload());
    }
      
    public function testDocumentWallUpload() {
        $doc = new DocumentWall("Uploads");
        $doc->setDocument("C:\Users\HPg018ur\Documents\NetBeansProjects\Dudoser\index.php");
        $res = $doc->upload();
        
        $this->assertNotEmpty($res);
        $this->assertArrayHasKey("doc", $res);
        $this->assertArrayHasKey("id", $res["doc"]);
        
        $api = new VkApi("Uploads");
        $res_ = $api->wall->post([
            "attachments" => "doc".$res["doc"]["owner_id"]."_".$res["doc"]["id"],
            "message" => "Test"
        ])->json();
        $this->assertArrayHasKey("post_id", $res_);
    }
    
    public function testDocumentMessagesUpload() {
        $doc = new DocumentMessages("Uploads");
        $doc->setDocument("C:\Users\HPg018ur\Documents\NetBeansProjects\Dudoser\index.php");
        $doc->setPeerId(237039085);
        $res = $doc->upload();
  
        $this->assertNotEmpty($res);
        $this->assertArrayHasKey("doc", $res);
        
        $api = new VkApi("Uploads");
        $api->messages->send([
            "attachment" => "doc".$res["doc"]["owner_id"]."_".$res["doc"]["id"],
            "message" => "Test",
            "random_id" => 0,
            "peer_id" => 237039085
        ]);
    }
    
    public function testGraffitiUpload() {
        $doc = new DocumentMessages("Uploads");
        $doc->setGraffiti("C:\Users\HPg018ur\games\GTA SA\Grand Theft Auto San Andreas\sampgui.png");
        $doc->setPeerId(237039085);
        $res = $doc->upload();
 
        $this->assertNotEmpty($res);
        $this->assertArrayHasKey("graffiti", $res);
        
        $api = new VkApi("Uploads");
        $api->messages->send([
            "attachment" => "graffiti".$res["graffiti"]["owner_id"]."_".$res["graffiti"]["id"],
            "message" => "Test",
            "random_id" => 0,
            "peer_id" => 237039085
        ]);
    }
    
    public function testAudioUpload() {
        $doc = new DocumentMessages("Uploads");
        $doc->setAudio("C:\Users\HPg018ur\Downloads\Three Days Grace-Riot-kissvk.com.ogg");
        $doc->setPeerId(237039085);
        $res = $doc->upload();
 
        $this->assertNotEmpty($res);
        $this->assertArrayHasKey("audio_message", $res);
        
        $api = new VkApi("Uploads");
        $api->messages->send([
            "attachment" => "audio_message".$res["audio_message"]["owner_id"]."_".$res["audio_message"]["id"],
            "message" => "Test",
            "random_id" => 0,
            "peer_id" => 237039085
        ]);
    }
}