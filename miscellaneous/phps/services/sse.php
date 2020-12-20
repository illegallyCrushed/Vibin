<?php
require_once 'connect.php';
header("Cache-Control: no-cache");
header("Content-Type: text/event-stream");

if (isset($_SESSION['username'])) {
  while (true) {
    //fetch notif count
    $notifCountQuery =
      "SELECT count(like_id) AS likecount, count(comment_id) AS commentcount, count(follow_id) AS followcount FROM `action` WHERE username = ? AND from_username <> ? AND action_read_status = '0'";
    $notifCount = $pdo->prepare($notifCountQuery);
    $notifCount->execute([$_SESSION['username'], $_SESSION['username']]);
    $notifCountFetch = $notifCount->fetch();

    //fetch unread count
    $messageCountQuery = "SELECT count(message_id) AS messagecount FROM `message` WHERE receiver_username = ? AND sender_username <> ? AND message_read_status = '0'";
    $messageCount = $pdo->prepare($messageCountQuery);
    $messageCount->execute([$_SESSION['username'], $_SESSION['username']]);
    $messageCountFetch = $messageCount->fetch();

    //fetch read count
    $readCountQuery = "SELECT count(message_id) AS readcount FROM `message` WHERE receiver_username <> ? AND sender_username = ? AND message_read_status = '1'";
    $readCount = $pdo->prepare($readCountQuery);
    $readCount->execute([$_SESSION['username'], $_SESSION['username']]);
    $readCountFetch = $readCount->fetch();

    echo "event: notifupdate\n";
    echo 'data: {"likecount": "' .
      $notifCountFetch['likecount'] .
      '","commentcount": "' .
      $notifCountFetch['commentcount'] .
      '","followcount": "' .
      $notifCountFetch['followcount'] .
      '","messagecount": "' .
      $messageCountFetch['messagecount'] .
      '","readcount": "' .
      $readCountFetch['readcount'] .
      '"}';
    echo "\n\n";

    //flush stream
    while (ob_get_level() > 0) {
      ob_end_flush();
    }
    flush();
    session_write_close(); //kalo ga ada ini php ngelock file session jadi freeze semua

    if (connection_aborted()) {
      //kalo user disconnect
      break;
    }

    sleep(1); //setiap detik
  }
} else {
  header("Location: ./");
}

exit();

?>
