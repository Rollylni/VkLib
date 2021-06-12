# Long Poll Bots/User

  * [Read Clients Managing First](./Clients.md)
  * [Bots](#Bots)
  * [User](#User)

## Bots

[More About](https://vk.com/dev/bots_longpoll)

```php
use VkLib\LongPoll\{
    LongPoll,
    LongPollBot,
    LongPollEvent
};

$groupId = 0;
$lp = new LongPollBot($groupId, "YourClient");

# optional
$lp->setApiVersion(5.103);
$lp->setWait(25);

if (!$lp->isEnabled()) 
    $lp->setEnabled(true);

# Events handling
$lp->getHandler()->add(LongPollEvent::MESSAGE_NEW, function($obj) {
    var_dump($obj);
});

# there are also other events LongPoll::ON_START, LongPoll::ON_STOP, LongPoll::ON_FAILED
$lp->getHandler()->add(LongPoll::ON_LISTEN, function($res) {
    var_dump($res["updates"]);
});

# starting
$lp->connect();
$lp->start();

# stopping
$lp->stop();
```

## User

[More About](https://vk.com/dev/using_longpoll)

```php
use VkLib\LongPoll\{
    LongPoll,
    LongPollEvent,
    LongPollUser
};

$lpMode = LongPollUser::MODE_GET_ATTACHMENTS;
$lpVersion = 3;

$lp = new LongPollUser("YourClient", $lpMode, $lpVersion);
$lp->setWait(25);
$lp->setParam("need_pts", true);

# Events handling
$lp->getHandler()->add(LongPollEvent::MESSAGE_SEND, function($obj) use($lp) {
    # note that named values are returned in the event object
    # more details in the header "### Named Key Events"
    var_dump($obj["peer_id"], $obj["text"]);
    var_dump($lp->getHistory());
});

# there are also other events LongPoll::ON_START, LongPoll::ON_STOP, LongPoll::ON_FAILED
$lp->getHandler()->add(LongPoll::ON_LISTEN, function($res) {
    var_dump($res["updates"]);
});

# starting
$lp->connect();
$lp->start();

# stopping
$lp->stop();
```

### Named Key Events
**as you saw, named values are returned in event objects for ease of use, you see, it's much more convenient than getting values by their index**

  * 1 (MESSAGE_FLAGS_REPLACE) 
    * message_id
    * flags
    * peer_id
    * timestamp
    * text
    * extra_fields
    * attachments
    * random_id
  * 2 (MESSAGE_FLAGS_SET)
    * message_id
    * mask
    * peer_id
    * timestamp
    * text
    * extra_fields
    * attachments
    * random_id
  * 3 (MESSAGE_FLAGS_RESET)
    * message_id
    * mask
    * peer_id
    * timestamp
    * text
    * extra_fields
    * attachments
    * random_id
  * 4 (MESSAGE_SEND)
    * message_id
    * flags
    * peer_id
    * timestamp
    * text
    * extra_fields
    * attachments
    * random_id
  * 5 (MESSAGE_REDACT)
    * message_id
    * mask
    * peer_id
    * timestamp
    * text
    * extra_fields
    * attachments
    * random_id
  * 6 (READ_ALL_INCOMING_MESSAGES)
    * peer_id
    * local_id
  * 7 (READ_ALL_OUTGOING_MESSAGES)
    * peer_id
    * local_id
  * 8 (USER_ONLINE)
    * user_id
    * extra
    * timestamp
  * 9 (USER_OFFLINE)
    * user_id
    * flags
    * timestamp
  * 10 (PEER_FLAGS_RESET) 
    * peer_id
    * mask
  * 11 (PEER_FLAGS_REPLACE)
    * peer_id
    * flags
  * 12 (PEER_FLAGS_SET)
    * peer_id
    * mask
  * 13 (PEER_DELETE_ALL)
    * peer_id
    * local_id
  * 14 (PEER_RESTORE_ALL)
    * peer_id
    * local_id
  * 20 (EDIT_MAJOR_ID)
    * peer_id
    * major_id
  * 21 (EDIT_MINOR_ID)
    * peer_id
    * minor_id
  * 51 (CHAT_EDIT)
    * chat_id
    * self
  * 52 (CHAT_UPDATE)
    * type_id
    * peer_id
    * info
  * 61 (USER_TYPING) 
    * user_id
    * flags
  * 62 (USER_TYPING_IN_CHAT)
    * user_id
    * chat_id
  * 63 (USERS_TYPING_IN_CHAT)
    * user_ids
    * peer_id
    * total_count
    * ts
  * 64 (USER_RECORDING_VOICE)
    * user_ids
    * peer_id
    * total_count
    * ts
  * 70 (USER_CALL)
    * user_id
    * call_id
  * 80 (MESSAGES_COUNTER_UPDATE)
    * count
  * 114 (NOTIFICATION_SETTINGS_UPDATE)
    * peer_id
    * sound
    * disabled_until
