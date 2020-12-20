<?php
require_once 'connect.php';
header('Content-Type: application/json');

$fetchlimit = 99999;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username']) && isset($_POST['requestmode']) && isset($_POST['postid'])) {
  //cek kalo post ada
  $checkPostQuery = "SELECT username, COUNT(post_id) AS x FROM post WHERE post_id = ?";
  $checkPost = $pdo->prepare($checkPostQuery);
  $checkPost->execute([$_POST['postid']]);
  $checkPostFetch = $checkPost->fetch();

  $likeStatus = 0;
  $likeCount = 0;
  $errorCodes = new \stdClass();
  $jsonReply = new \stdClass();

  if ($checkPostFetch['x'] != 0) {
    //kalo post ada
    //get like count
    $checkLikeCountQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE post_id = ?";
    $checkLikeCount = $pdo->prepare($checkLikeCountQuery);
    $checkLikeCount->execute([$_POST['postid']]);
    $checkLikeCountFetch = $checkLikeCount->fetch();
    $likeCount = $checkLikeCountFetch['x'];

    if ($_POST['requestmode'] == "add") {
      //addlike
      //cek kalo like udah ada ato blm
      $checkLikeQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE post_id = ? AND username = ?";
      $checkLike = $pdo->prepare($checkLikeQuery);
      $checkLike->execute([$_POST['postid'], $_SESSION['username']]);
      $checkLikeFetch = $checkLike->fetch();
      if ($checkLikeFetch['x'] == 0) {
        //kalo gak ada, addlike, count++

        $addLikeQuery = "INSERT INTO `like` (username, post_id) VALUES (?,?)";
        $addLike = $pdo->prepare($addLikeQuery);
        $addLike->execute([$_SESSION['username'], $_POST['postid']]);

        //fetch like id
        $likeIDQuery = "SELECT like_id FROM `like` WHERE post_id = ? AND username = ?";
        $likeID = $pdo->prepare($likeIDQuery);
        $likeID->execute([$_POST['postid'], $_SESSION['username']]);
        $likeIDFetch = $likeID->fetch();

        //add ke actions buat notif
        $addLikeActionQuery = "INSERT INTO `action` (username, from_username, like_id, action_read_status, action_type) VALUES (?,?,?,?,?)";
        $addLikeAction = $pdo->prepare($addLikeActionQuery);
        $addLikeAction->execute([$checkPostFetch['username'], $_SESSION['username'], $likeIDFetch['like_id'], 0, "like"]);

        $likeStatus = 0;
        $likeCount++;
      } else {
        $likeStatus = 3;
      }
    } elseif ($_POST['requestmode'] == "remove") {
      //removelike
      //cek kalo like udah ada ato blm
      $checkLikeQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE post_id = ? AND username = ?";
      $checkLike = $pdo->prepare($checkLikeQuery);
      $checkLike->execute([$_POST['postid'], $_SESSION['username']]);
      $checkLikeFetch = $checkLike->fetch();
      if ($checkLikeFetch['x'] != 0) {
        //kalo ada, removelike, count--

        $removeLikeQuery = "DELETE FROM `like` WHERE  username = ? AND post_id = ?";
        $removeLike = $pdo->prepare($removeLikeQuery);
        $removeLike->execute([$_SESSION['username'], $_POST['postid']]);

        $likeStatus = 0;
        $likeCount--;

        //gak perlu remove action soalnya on delete cascade
      } else {
        //kalo gak ada
        $likeStatus = 2;
      }
    } elseif ($_POST['requestmode'] == "fetch") {
      //buat display likes
      $likeFetchQuery =
        "SELECT like.like_id, user.username, user.realname, user.extension FROM `like` JOIN user ON like.username = user.username WHERE post_id = ? ORDER BY like.like_time DESC LIMIT ?";
      $likeFetch = $pdo->prepare($likeFetchQuery);
      $likeFetch->execute([$_POST['postid'], $fetchlimit]);

      $likeArray = [];
      $likeRequestIDBottom = 0;
      while ($likeRow = $likeFetch->fetch()) {
        $likeData = new \stdClass();
        $likeData->username = $likeRow['username'];
        $likeData->realname = $likeRow['realname'];
        $likeData->extension = $likeRow['extension'];
        $likeArray[] = $likeData;
        $likeRequestIDBottom = $likeRow['like_id'];
      }

      $jsonReply->likes = $likeArray;
      $jsonReply->bottomlikeid = $likeRequestIDBottom;
    } elseif ($_POST['requestmode'] == "older" && isset($_POST['bottomlikeid'])) {
      //buat display likes
      $likeFetchQuery =
        "SELECT like.like_id user.username, user.realname, user.extension FROM `like` JOIN user ON like.username = user.username WHERE post_id = ? AND like_id < ? ORDER BY like.like_time DESC LIMIT ?";
      $likeFetch = $pdo->prepare($likeFetchQuery);
      $likeFetch->execute([$_POST['postid'], $fetchlimit]);

      $likeArray = [];
      $likeRequestIDBottom = $_POST['bottomlikeid'];
      while ($likeRow = $likeFetch->fetch()) {
        $likeData = new \stdClass();
        $likeData->username = $likeRow['username'];
        $likeData->realname = $likeRow['realname'];
        $likeData->extension = $likeRow['extension'];
        $likeArray[] = $likeData;
        $likeRequestIDBottom = $likeRow['like_id'];
      }

      $jsonReply->likes = $likeArray;
      $jsonReply->bottomlikeid = $likeRequestIDBottom;
    } else {
      header("Location: ./");
      exit();
    }
  } else {
    //kalo gak ada
    $likeStatus = 1;
  }

  $errorCodes->likestatus = $likeStatus;
  $jsonReply->errorcode = $errorCodes;
  $jsonReply->likecount = $likeCount;
  echo json_encode($jsonReply);
  exit();
} else {
  header("Location: ./");
  exit();
}

?>
