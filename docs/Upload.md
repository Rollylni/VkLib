# Upload files

  * [Read Clients Managing First](./Clients.md)
  * [More About](https://vk.com/dev/upload_files)
  * [Video](## Video)
  * [Stories](## Stories)
  * [Photo Wall](## Photo Wall)
  * [Photo Owner](## Photo Owner)
  * [Photo Owner Cover](## Photo Owner Cover)
  * [Photo Messages](## Photo Messages)
  * [Photo Market Album](## Photo Market Album)
  * [Photo Market](## Photo Market)
  * [Photo Chat](## Photo Chat)
  * [Photo Album](## Photo Album)
  * [Audio](## Audio)
  * [Document](## Document)
  * [Messages Document](## Messages Document)
  * [Document Wall](## Document Wall)

## App Image (App Widgets)
```php
use VkLib\Upload\AppImage;

$file = "/path/cat.jpg";

$media = new AppImage("client");

$media->setImageType("160x160");
$media->setImage($file);

$media->upload();
```

## Group Image (App Widgets)
```php
use VkLib\Upload\GroupImage;

$file = "/path/cat.jpg";

$media = new GroupImage("client");

$media->setImageType("160x160");
$media->setImage($file);

$media->upload();
```

## Video
```php
use VkLib\Upload\Video;

$params = [
    "name" => "Asking Alexandria - The Black",
    "description" => "I love this song",
    "is_private" => false,
    "album_id" => 0,
    "group_id" => 0
];

$media = new Video("client");
$media->setVideo("./clip.mp4");

$media->upload($params);
```

## Stories
```php
use VkLib\Upload\Stories;

$params = [
    "extended" => true,
    "fields" => []
];

$storie = new Stories("client");
$storie->setParameters([
   "add_to_news" => false,
   "user_ids" => [],
   "link_text" => "write",
   "link_url" => "",
]);

$storie->setPhoto("./lmao.jpg");
# or
$storie->setVideo("./blog.mp4");

$storie->upload($params);
```

## Photo Wall
```php
use VkLib\Upload\PhotoWall;

$groupId = 0;
$params = [
    "user_id" => 0,
    "caption" => ""
];

$media = new PhotoWall("client");
$media->setGroupId($groupId);
$media->setPhoto("./Meme.jpg");

$media->upload($params);
```

## Photo Owner
```php
use VkLib\Upload\PhotoOwner;

$ownerId = 0;

$media = new PhotoOwner("client");
$media->setOwnerId($ownerId);
$media->setPhoto("./Avatar.jpg");
$media->setSquareCrop("0,0,0");

$media->upload();

```
 
## Photo Owner Cover
```php
use VkLib\Upload\PhotoOwnerCover;

$groupId = 0;
$cropParams = [
    "crop_x" => 0,
    "crop_y" => 0,
    "crop_x2" => 795,
    "crop_y2" => 200
];

$media = new PhotoOwnerCover("client");
$media->setGroupId($groupId);
$media->setPhoto("./Meme.jpg");
$media->setCropParams($cropParams);

$media->upload();
```

## Photo Messages
```php
use VkLib\Upload\PhotoMessages;

$groupId = 0;
$params = [
    "album_id" => 0,
    "caption" => ""
];

$media = new PhotoMessages("client");
$media->setGroupId($groupId);
$media->setPhoto("./Meme.jpg");

$media->upload($params);
```

## Photo Market Album
```php
use VkLib\Upload\PhotoMarketAlbum;

$groupId = 0;

$media = new PhotoMarketAlbum("client");
$media->setGroupId($groupId);
$media->setPhoto("./item.jpg");

$media->upload();
```

## Photo Market
```php
use VkLib\Upload\PhotoMarket;

$groupId = 0;

$media = new PhotoMarket("client");
$media->setGroupId($groupId);
$media->setPhoto("./item.jpg");
$media->setMainPhoto();

$media->upload();
```

## Photo Chat 
```php
use VkLib\Upload\PhotoChat;

$cropX = 0;
$cropY = 0;
$cropWidth = 0;
$chatId= 0;

$media = new PhotoChat("client");
$media->setChatId($chatId);
$media->setCrop($cropX, $cropY, $cropWidth);
$media->setPhoto("/bands/Slipknot.jpg");

$media->upload();
```

## Photo Album
```php
use VkLib\Upload\PhotoAlbum;

$albumId = 0;
$groupId = 0;
$params = [
    "caption" => "Cats ^_^",
    "latitude" => 0,
    "longitude" => 0
];

$media = new PhotoAlbum("client");
$media->setGroupId($groupId);
$media->setAlbumId($albumId);

$media->addPhoto("./Cat1.jpg");
$media->addPhoto("./Cat2.jpg");
$media->addPhoto("./Cat3.jpg");

$media->upload($params);
```

## Audio
```php
use VkLib\Upload\Audio;

$file = "/path/audio.mp3";
$params = [
    "artist" => "Scorpions",
    "title" => "Hit Between The Eyes"
];

$media = new Audio("client");

$media->setAudio($file);
$media->upload($params);
```

## Document
```php
use VkLib\Upload\Document;

$file = "/path/document.gif";
$params = [
    "title" => "Gachi"
];

$media = new Document("client");

# $media->setGroupId(159146575);
$media->setDocument($file);
$media->upload($params);
```

## Messages Document
```php
use VkLib\Upload\DocumentMessages;

$media = new DocumentMessages("client");

$media->setPeerId(2E9);

# Document
$media->setDocument("/path/document.gif");
$params = ["title" => "Gachi.gif"];

# Graffiti
$media->setGraffiti("/path/php-logo.png");

# Audio message
$media->setAudio("/path/voice.ogg");

$media->upload($params);
```

## Document wall
```php
use VkLib\Upload\DocumentWall;

$file = "/path/document.gif";
$params = [
    "title" => "Gachi"
];

$media = new DocumentWall("client");

# $media->setGroupId(159146575);
$media->setDocument($file);
$media->upload($params);
```