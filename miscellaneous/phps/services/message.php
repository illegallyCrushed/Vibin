<?php
require_once 'connect.php';
header('Content-Type: application/json');

$fetchlimit = 99999;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['requestmode']) && isset($_SESSION['username'])) {
  $jsonReply = new \stdClass();

  if ($_POST['requestmode'] == "check" && isset($_POST['username'])) {
    //check kalo username punya conversation id dengan user
    $checkConvIDQuery = "SELECT conversation_id, COUNT(conversation_id) AS x FROM `conversation` WHERE (username_a = ? AND username_b = ?) OR (username_a = ? AND username_b = ?)";
    $checkConvID = $pdo->prepare($checkConvIDQuery);
    $checkConvID->execute([$_SESSION['username'], $_POST['username'], $_POST['username'], $_SESSION['username']]);
    $checkConvIDFetch = $checkConvID->fetch();
    if ($checkConvIDFetch['x'] == 1) {
      $jsonReply->available = 1;
      $jsonReply->convid = $checkConvIDFetch['conversation_id'];
    } else {
      $jsonReply->available = 0;
    }
  } elseif ($_POST['requestmode'] == "fetchconv") {
    //fetch conversations
    $convFetchQuery = "SELECT `conversation`.conversation_id AS conversation_id,
        username_a,
        username_b,
        user_a.extension AS extension_a,
        user_b.extension AS extension_b,
        read_status_a,
        read_status_b,
        message_time,
        text
          FROM `conversation`
           JOIN `message` ON `conversation`.last_message_id = `message`.message_id 
           JOIN user AS user_a ON `conversation`.username_a = user_a.username 
           JOIN user AS user_b ON `conversation`.username_b = user_b.username
            WHERE username_a = ? OR username_b = ?
            ORDER BY message_time DESC";
    $convFetch = $pdo->prepare($convFetchQuery);
    $convFetch->execute([$_SESSION['username'], $_SESSION['username']]);

    $convCount = 0;
    $convArray = [];
    while ($convRow = $convFetch->fetch()) {
      $convData = new \stdClass();
      $convData->id = $convRow['conversation_id'];
      $convData->lastmessage = $convRow['text'];
      $convData->time = $convRow['message_time'];
      if ($convRow['username_a'] != $_SESSION['username']) {
        $convData->username = $convRow['username_a'];
        $convData->extension = $convRow['extension_a'];
        $convData->status = $convRow['read_status_b'];
      } elseif ($convRow['username_b'] != $_SESSION['username']) {
        $convData->username = $convRow['username_b'];
        $convData->extension = $convRow['extension_b'];
        $convData->status = $convRow['read_status_a'];
      }
      $convArray[] = $convData;
      $convCount++;
    }

    $jsonReply->count = $convCount;
    $jsonReply->convs = $convArray;
  } elseif ($_POST['requestmode'] == "fetchmessage" && isset($_POST['convid'])) {
    //kalo minta fetch message
    //set semua jadi read
    $messageReadQuery = "UPDATE `message` SET message_read_status = 1 WHERE conversation_id = ? AND message_read_status = 0 AND receiver_username = ?";
    $messageRead = $pdo->prepare($messageReadQuery);
    $messageRead->execute([$_POST['convid'], $_SESSION['username']]);

    $conversationAReadQuery = "UPDATE `conversation` SET read_status_a = 1 WHERE conversation_id = ? AND read_status_a = 0 AND username_a = ?";
    $conversationARead = $pdo->prepare($conversationAReadQuery);
    $conversationARead->execute([$_POST['convid'], $_SESSION['username']]);

    $conversationBReadQuery = "UPDATE `conversation` SET read_status_b = 1 WHERE conversation_id = ? AND read_status_b = 0 AND username_b = ?";
    $conversationBRead = $pdo->prepare($conversationBReadQuery);
    $conversationBRead->execute([$_POST['convid'], $_SESSION['username']]);

    //fetch messages
    $messageFetchQuery = "SELECT * FROM `message` WHERE conversation_id = ? ORDER BY message_time DESC LIMIT ?";
    $messageFetch = $pdo->prepare($messageFetchQuery);
    $messageFetch -> execute([$_POST['convid'], $fetchlimit]);

    $bottomMessageID = 0;
    $messageCount = 0;
    $messageArray = [];
    while ($messageRow = $messageFetch->fetch()) {
      $messageData = new \stdClass();
      $messageData->id = $messageRow['message_id'];
      $messageData->text = htmlspecialchars($messageRow['text']);
      $messageData->time = $messageRow['message_time'];
      $messageData->status = $messageRow['message_read_status'];

      if ($messageRow['sender_username'] == $_SESSION['username']) {
        $messageData->type = "you";
      } elseif ($messageRow['receiver_username'] == $_SESSION['username']) {
        $messageData->type = "other";
      }

      $messageArray[] = $messageData;
      if ($messageCount == 0) {
        $bottomMessageID = $messageRow['message_id'];
      }
      $messageCount++;
    }

    $jsonReply->count = $messageCount;
    $jsonReply->bottommessageid = $bottomMessageID;
    $jsonReply->messages = $messageArray;
  } elseif ($_POST['requestmode'] == "refresh" && isset($_POST['convid']) && isset($_POST['bottommessageid'])) {
    //kalo minta refresh
    //set semua jadi read
    $messageReadQuery = "UPDATE `message` SET message_read_status = 1 WHERE conversation_id = ? AND message_read_status = 0 AND receiver_username = ?";
    $messageRead = $pdo->prepare($messageReadQuery);
    $messageRead->execute([$_POST['convid'], $_SESSION['username']]);

    $conversationAReadQuery = "UPDATE `conversation` SET read_status_a = 1 WHERE conversation_id = ? AND read_status_a = 0 AND username_a = ?";
    $conversationARead = $pdo->prepare($conversationAReadQuery);
    $conversationARead->execute([$_POST['convid'], $_SESSION['username']]);

    $conversationBReadQuery = "UPDATE `conversation` SET read_status_b = 1 WHERE conversation_id = ? AND read_status_b = 0 AND username_b = ?";
    $conversationBRead = $pdo->prepare($conversationBReadQuery);
    $conversationBRead->execute([$_POST['convid'], $_SESSION['username']]);

    //fetch messages
    $messageFetchQuery = "SELECT * FROM `message` WHERE conversation_id = ? AND message_id > ? ORDER BY message_time ASC LIMIT ?";
    $messageFetch = $pdo->prepare($messageFetchQuery);
    $messageFetch -> execute([$_POST['convid'], $_POST['bottommessageid'], $fetchlimit]);

    $bottomMessageID = $_POST['bottommessageid'];
    $messageCount = 0;
    $messageArray = [];
    while ($messageRow = $messageFetch->fetch()) {
      $messageData = new \stdClass();
      $messageData->id = $messageRow['message_id'];
      $messageData->text =htmlspecialchars($messageRow['text']);
      $messageData->time = $messageRow['message_time'];
      $messageData->status = $messageRow['message_read_status'];

      if ($messageRow['sender_username'] == $_SESSION['username']) {
        $messageData->type = "you";
      } elseif ($messageRow['receiver_username'] == $_SESSION['username']) {
        $messageData->type = "other";
      }

      $messageArray[] = $messageData;
      $bottomMessageID = $messageRow['message_id'];
      $messageCount++;
    }

    $jsonReply->count = $messageCount;
    $jsonReply->bottommessageid = $bottomMessageID;
    $jsonReply->messages = $messageArray;
  } elseif ($_POST['requestmode'] == "send" && isset($_POST['convid']) && isset($_POST['message']) && isset($_POST['username'])) {
    //kalo send message
    //check kalo convid udah ada ato blm
    $checkConvIDQuery = "SELECT conversation_id, COUNT(conversation_id) AS x FROM `conversation` WHERE ((username_a = ? AND username_b = ?) OR (username_a = ? AND username_b = ?))";
    $checkConvID = $pdo->prepare($checkConvIDQuery);
    $checkConvID->execute([$_SESSION['username'], $_POST['username'], $_POST['username'], $_SESSION['username']]);
    $checkConvIDFetch = $checkConvID->fetch();

    if ($checkConvIDFetch['x'] == 1) {
      $jsonReply->status = 1;
      $jsonReply->convid = $_POST['convid'];
    } else {
      //kalo blm, buat baru
      $newConvQuery = "INSERT INTO `conversation` (username_a, username_b, read_status_a, read_status_b) VALUES (?,?,?,?)";
      $newConv = $pdo->prepare($newConvQuery);
      $newConv->execute([$_SESSION['username'], $_POST['username'], "0", "0"]);

      $fetchNewConvIDQuery = "SELECT conversation_id FROM `conversation` WHERE username_a = ? AND username_b = ?";
      $fetchNewConvID = $pdo->prepare($fetchNewConvIDQuery);
      $fetchNewConvID->execute([$_SESSION['username'], $_POST['username']]);
      $newConvID = $fetchNewConvID->fetch();

      $jsonReply->status = 2;
      $jsonReply->convid = $newConvID['conversation_id'];
    }

    //insert message ke database
    $newMessageQuery = "INSERT INTO `message` (conversation_id, sender_username, receiver_username, message_read_status, text) VALUES (?,?,?,?,?)";
    $newMessage = $pdo->prepare($newMessageQuery);
    $newMessage->execute([$jsonReply->convid, $_SESSION['username'], $_POST['username'], "0", trim($_POST['message'])]);

    //fetch message id baru
    $fetchNewMessageIDQuery = "SELECT message_id, message_time FROM `message` WHERE conversation_id = ? ORDER BY message_time DESC LIMIT 1";
    $fetchNewMessageID = $pdo->prepare($fetchNewMessageIDQuery);
    $fetchNewMessageID->execute([$jsonReply->convid]);
    $newMessageID = $fetchNewMessageID->fetch();

    //update conv id

    $conversationAReadQuery = "UPDATE `conversation` SET read_status_b = 0, read_status_a = 1, last_message_id = ?, last_time = ? WHERE conversation_id = ? AND username_a = ?";
    $conversationARead = $pdo->prepare($conversationAReadQuery);
    $conversationARead->execute([$newMessageID['message_id'], $newMessageID['message_time'], $jsonReply->convid, $_SESSION['username']]);

    $conversationBReadQuery = "UPDATE `conversation` SET read_status_a = 0, read_status_b = 1, last_message_id = ?, last_time = ?  WHERE conversation_id = ? AND username_b = ?";
    $conversationBRead = $pdo->prepare($conversationBReadQuery);
    $conversationBRead->execute([$newMessageID['message_id'], $newMessageID['message_time'], $jsonReply->convid, $_SESSION['username']]);
  }
  echo json_encode($jsonReply);
  exit();
} else {
  header("Location: ./");
  exit();
}

?>
