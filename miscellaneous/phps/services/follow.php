<?php
require_once 'connect.php';
header('Content-Type: application/json');

$fetchlimit = 99999;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['requestmode']) && isset($_POST['userid'])) {
  $followStatus = 0;
  $followCount = 0;
  $errorCodes = new \stdClass();
  $jsonReply = new \stdClass();

  //cek kalo user ada
  $checkUserQuery = "SELECT COUNT(username) AS x FROM user WHERE username = ?";
  $checkUser = $pdo->prepare($checkUserQuery);
  $checkUser->execute([$_POST['userid']]);
  $checkUserFetch = $checkUser->fetch();

  if ($checkUserFetch['x'] != 0) {
    //kalo post ada
    //get followers count untuk user yang difollow
    $checkFollowCountQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE receiving_username = ?";
    $checkFollowCount = $pdo->prepare($checkFollowCountQuery);
    $checkFollowCount->execute([$_POST['userid']]);
    $checkFollowCountFetch = $checkFollowCount->fetch();
    $followCount = $checkFollowCountFetch['x'];

    if ($_POST['requestmode'] == "add" && isset($_SESSION['username'])) {
      //addfollow
      if ($_SESSION['username'] != $_POST['userid']) {
        //cek kalo user gak sama
        //cek kalo follow udah ada ato blm
        $checkFollowQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE receiving_username = ? AND initiator_username = ?";
        $checkFollow = $pdo->prepare($checkFollowQuery);
        $checkFollow->execute([$_POST['userid'], $_SESSION['username']]);
        $checkFollowFetch = $checkFollow->fetch();
        if ($checkFollowFetch['x'] == 0) {
          //kalo gak ada, addfollow, count++

          $addFollowQuery = "INSERT INTO follow (initiator_username, receiving_username) VALUES (?,?)";
          $addFollow = $pdo->prepare($addFollowQuery);
          $addFollow->execute([$_SESSION['username'], $_POST['userid']]);

          //fetch follow id
          $followIDQuery = "SELECT follow_id FROM follow WHERE receiving_username = ? AND initiator_username = ?";
          $followID = $pdo->prepare($followIDQuery);
          $followID->execute([$_POST['userid'], $_SESSION['username']]);
          $followIDFetch = $followID->fetch();

          //add ke actions buat notif
          $addFollowActionQuery = "INSERT INTO `action` (username, from_username, follow_id, action_read_status, action_type) VALUES (?,?,?,?,?)";
          $addFollowAction = $pdo->prepare($addFollowActionQuery);
          $addFollowAction->execute([$_POST['userid'], $_SESSION['username'], $followIDFetch['follow_id'], 0, "follow"]);

          $followStatus = 0;
          $followCount++;
        } else {
          $followStatus = 4;
        }
      } else {
        //kalo user sama
        $followStatus = 2;
      }
    } elseif ($_POST['requestmode'] == "remove" && isset($_SESSION['username'])) {
      //removefollow
      if ($_SESSION['username'] != $_POST['userid']) {
        //cek kalo user gak sama
        //cek kalo follow udah ada ato blm
        $checkFollowQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE receiving_username = ? AND initiator_username = ?";
        $checkFollow = $pdo->prepare($checkFollowQuery);
        $checkFollow->execute([$_POST['userid'], $_SESSION['username']]);
        $checkFollowFetch = $checkFollow->fetch();
        if ($checkFollowFetch['x'] != 0) {
          //kalo ada, removefollow, count--

          $removeFollowQuery = "DELETE FROM follow WHERE initiator_username = ? AND receiving_username = ?";
          $removeFollow = $pdo->prepare($removeFollowQuery);
          $removeFollow->execute([$_SESSION['username'], $_POST['userid']]);

          $followStatus = 0;
          $followCount--;

          //gak perlu remove action soalnya on delete cascade
        } else {
          //kalo gak ada
          $followStatus = 3;
        }
      } else {
        //kalo user sama
        $followStatus = 2;
      }
    } elseif ($_POST['requestmode'] == "fetchfollowers") {
      //buat display followers
      $followerFetchQuery =
        "SELECT follow_id, username, realname, extension FROM follow JOIN user ON follow.initiator_username = user.username WHERE receiving_username = ? ORDER BY follow_time DESC LIMIT ?";
      $followerFetch = $pdo->prepare($followerFetchQuery);
      $followerFetch->execute([$_POST['userid'], $fetchlimit]);

      $followerArray = [];
      $followerRequestIDBottom = 0;
      while ($followerRow = $followerFetch->fetch()) {
        $followerData = new \stdClass();
        $followerData->username = $followerRow['username'];
        $followerData->realname = $followerRow['realname'];
        $followerData->extension = $followerRow['extension'];
        $followerArray[] = $followerData;
        $followerRequestIDBottom = $followerRow['follow_id'];
      }

      $jsonReply->followers = $followerArray;
      $jsonReply->bottomfollowerid = $followerRequestIDBottom;
    } elseif ($_POST['requestmode'] == "fetchfollowing") {
      //buat display following
      //fetchfollowing count
      $checkFollowingCountQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE initiator_username = ?";
      $checkFollowingCount = $pdo->prepare($checkFollowingCountQuery);
      $checkFollowingCount->execute([$_POST['userid']]);
      $checkFollowingCountFetch = $checkFollowingCount->fetch();
      $followCount = $checkFollowingCountFetch['x'];

      $followingFetchQuery =
        "SELECT follow_id, username, realname, extension FROM follow JOIN user ON follow.receiving_username = user.username WHERE initiator_username = ? ORDER BY follow_time DESC LIMIT ?";
      $followingFetch = $pdo->prepare($followingFetchQuery);
      $followingFetch->execute([$_POST['userid'], $fetchlimit]);

      $followingArray = [];
      $followingRequestIDBottom = 0;
      while ($followingRow = $followingFetch->fetch()) {
        $followingData = new \stdClass();
        $followingData->username = $followingRow['username'];
        $followingData->realname = $followingRow['realname'];
        $followingData->extension = $followingRow['extension'];
        $followingArray[] = $followingData;
        $followingRequestIDBottom = $followingRow['follow_id'];
      }

      $jsonReply->followings = $followingArray;
      $jsonReply->bottomfollowingid = $followingRequestIDBottom;
    } elseif ($_POST['requestmode'] == "olderfollowers" && isset($_POST['bottomfollowerid'])) {
      //buat display followers
      $followerFetchQuery =
        "SELECT follow_id, username, realname, extension FROM follow JOIN user ON follow.initiator_username = user.username WHERE receiving_username = ? AND follow_id < ? ORDER BY follow_time DESC LIMIT ?";
      $followerFetch = $pdo->prepare($followerFetchQuery);
      $followerFetch->execute([$_POST['userid'], $_POST['bottomfollowerid'], $fetchlimit]);

      $followerArray = [];
      $followerRequestIDBottom = $_POST['bottomfollowerid'];
      while ($followerRow = $followerFetch->fetch()) {
        $followerData = new \stdClass();
        $followerData->username = $followerRow['username'];
        $followerData->realname = $followerRow['realname'];
        $followerData->extension = $followerRow['extension'];
        $followerArray[] = $followerData;
        $followerRequestIDBottom = $followerRow['follow_id'];
      }

      $jsonReply->followers = $followArray;
      $jsonReply->bottomfollowerid = $followerRequestIDBottom;
    } elseif ($_POST['requestmode'] == "olderfollowing" && isset($_POST['bottomfollowingid'])) {
      //buat display following
      //fetchfollowing count
      $checkFollowingCountQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE initiator_username = ?";
      $checkFollowingCount = $pdo->prepare($checkFollowingCountQuery);
      $checkFollowingCount->execute([$_POST['userid']]);
      $checkFollowingCountFetch = $checkFollowingCount->fetch();
      $followCount = $checkFollowingCountFetch['x'];

      $followingFetchQuery =
        "SELECT follow_id, username, realname, extension FROM follow JOIN user ON follow.receiving_username = user.username WHERE initiator_username = ? AND follow_id < ? ORDER BY follow_time DESC LIMIT ?";
      $followingFetch = $pdo->prepare($followingFetchQuery);
      $followingFetch->execute([$_POST['userid'], $_POST['bottomfollowingid'], $fetchlimit]);

      $followingArray = [];
      $followingRequestIDBottom = $_POST['bottomfollowingid'];
      while ($followingRow = $followingFetch->fetch()) {
        $followingData = new \stdClass();
        $followingData->username = $followingRow['username'];
        $followingData->realname = $followingRow['realname'];
        $followingData->extension = $followingRow['extension'];
        $followingArray[] = $followingData;
        $followingRequestIDBottom = $followingRow['follow_id'];
      }

      $jsonReply->followings = $followArray;
      $jsonReply->bottomfollowingid = $followingRequestIDBottom;
    } else {
      header("Location: ./");
      exit();
    }
  } else {
    //kalo gak ada
    $followStatus = 1;
  }

  $errorCodes->followstatus = $followStatus;
  $jsonReply->errorcode = $errorCodes;
  $jsonReply->followcount = $followCount;
  echo json_encode($jsonReply);
  exit();
} else {
  header("Location: ./");
  exit();
}

?>
