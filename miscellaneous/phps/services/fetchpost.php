<?php
require_once 'connect.php';
header('Content-Type: application/json');
$fetchlimit = 99999; //limit banyak fetch post

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username']) && isset($_POST['requestmode'])) {
  if ($_POST['requestmode'] == "simple") {
    //request buat di home
    $postFetchQuery = "SELECT * FROM post WHERE username IN (SELECT receiving_username FROM follow WHERE initiator_username = ?) OR username = ? ORDER BY post_time DESC LIMIT ?";
    $postFetch = $pdo->prepare($postFetchQuery);
    $postFetch->execute([$_SESSION['username'], $_SESSION['username'], $fetchlimit]);

    $postArray = [];
    $postCounter = 0;
    $postRequestIDTop = 0;
    $postRequestIDBottom = 0;
    while ($postRow = $postFetch->fetch()) {
      $postData = new \stdClass();
      $postData->postid = $postRow['post_id'];
      $postData->postusername = $postRow['username'];
      $postData->postdatetime = $postRow['post_time'];
      $postData->postcaption = $postRow['caption'];
      $postData->postextension = $postRow['extension'];

      if ($postRow['username'] == $_SESSION['username']) {
        $postData->deleteperms = 1;
      } else {
        $postData->deleteperms = 0;
      }

      //fetch user profile pic extension
      $profPicQuery = "SELECT extension FROM user WHERE username = ?";
      $profPic = $pdo->prepare($profPicQuery);
      $profPic->execute([$postRow['username']]);
      $profPicFetched = $profPic->fetch();

      $postData->profpicext = $profPicFetched['extension'];

      //fetch like status
      $likeStatQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE username = ? AND post_id = ?";
      $likeStat = $pdo->prepare($likeStatQuery);
      $likeStat->execute([$_SESSION['username'], $postRow['post_id']]);
      $likeStatFetched = $likeStat->fetch();

      if ($likeStatFetched['x'] > 0) {
        $postData->likestatus = 1;
      } else {
        $postData->likestatus = 0;
      }

      //fetch like count
      $likeCountQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE post_id = ?";
      $likeCount = $pdo->prepare($likeCountQuery);
      $likeCount->execute([$postRow['post_id']]);
      $likeCountFetched = $likeCount->fetch();

      $postData->likecount = $likeCountFetched['x'];

      //fetch comment count
      $commentCountQuery = "SELECT COUNT(comment_id) AS x FROM comment WHERE post_id = ?";
      $commentCount = $pdo->prepare($commentCountQuery);
      $commentCount->execute([$postRow['post_id']]);
      $commentCountFetched = $commentCount->fetch();

      $postData->commentcount = $commentCountFetched['x'];

      //fetch max 2 top comment

      $commentFetchQuery = "SELECT username, comment_text FROM comment WHERE post_id = ? ORDER BY comment_time DESC LIMIT 2";
      $commentFetch = $pdo->prepare($commentFetchQuery);
      $commentFetch->execute([$postRow['post_id']]);

      $commentArray = [];

      while ($commentRow = $commentFetch->fetch()) {
        $commentData = new \stdClass();
        $commentData->username = $commentRow['username'];
        $commentData->text = $commentRow['comment_text'];
        $commentArray[] = $commentData;
      }

      $postData->postcomments = $commentArray;

      $postArray[] = $postData;

      if ($postCounter == 0) {
        //simpan id paling atas
        $postRequestIDTop = $postRow['post_id'];
      }
      //simpan id paling bawah
      $postRequestIDBottom = $postRow['post_id'];
      $postCounter++;
    }

    $jsonReply = new \stdClass();
    $jsonReply->count = $postCounter;
    $jsonReply->toppostid = $postRequestIDTop;
    $jsonReply->bottompostid = $postRequestIDBottom;
    $jsonReply->posts = $postArray;

    echo json_encode($jsonReply);
    exit();
  } elseif ($_POST['requestmode'] == "newer" && isset($_POST['topid'])) {
    //request buat di , prepend
    $postFetchQuery = "SELECT * FROM post WHERE (username IN (SELECT receiving_username FROM follow WHERE initiator_username = ?) OR username = ?) AND post_id > ? ORDER BY post_time";
    $postFetch = $pdo->prepare($postFetchQuery);
    $postFetch->execute([$_SESSION['username'], $_SESSION['username'], $_POST['topid']]);

    $postArray = [];
    $postCounter = 0;
    $postRequestIDTop = $_POST['topid'];
    while ($postRow = $postFetch->fetch()) {
      $postData = new \stdClass();
      $postData->postid = $postRow['post_id'];
      $postData->postusername = $postRow['username'];
      $postData->postdatetime = $postRow['post_time'];
      $postData->postcaption = $postRow['caption'];
      $postData->postextension = $postRow['extension'];

      if ($postRow['username'] == $_SESSION['username']) {
        $postData->deleteperms = 1;
      } else {
        $postData->deleteperms = 0;
      }

      //fetch user profile pic extension
      $profPicQuery = "SELECT extension FROM user WHERE username = ?";
      $profPic = $pdo->prepare($profPicQuery);
      $profPic->execute([$postRow['username']]);
      $profPicFetched = $profPic->fetch();

      $postData->profpicext = $profPicFetched['extension'];

      //fetch like status
      $likeStatQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE username = ? AND post_id = ?";
      $likeStat = $pdo->prepare($likeStatQuery);
      $likeStat->execute([$_SESSION['username'], $postRow['post_id']]);
      $likeStatFetched = $likeStat->fetch();

      if ($likeStatFetched['x'] > 0) {
        $postData->likestatus = 1;
      } else {
        $postData->likestatus = 0;
      }

      //fetch like count
      $likeCountQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE post_id = ?";
      $likeCount = $pdo->prepare($likeCountQuery);
      $likeCount->execute([$postRow['post_id']]);
      $likeCountFetched = $likeCount->fetch();

      $postData->likecount = $likeCountFetched['x'];

      //fetch comment count
      $commentCountQuery = "SELECT COUNT(comment_id) AS x FROM comment WHERE post_id = ?";
      $commentCount = $pdo->prepare($commentCountQuery);
      $commentCount->execute([$postRow['post_id']]);
      $commentCountFetched = $commentCount->fetch();

      $postData->commentcount = $commentCountFetched['x'];

      //fetch max 2 top comment

      $commentFetchQuery = "SELECT username, comment_text FROM comment WHERE post_id = ? ORDER BY comment_time DESC LIMIT 2";
      $commentFetch = $pdo->prepare($commentFetchQuery);
      $commentFetch->execute([$postRow['post_id']]);

      $commentArray = [];

      while ($commentRow = $commentFetch->fetch()) {
        $commentData = new \stdClass();
        $commentData->username = $commentRow['username'];
        $commentData->text = $commentRow['comment_text'];
        $commentArray[] = $commentData;
      }

      $postData->postcomments = $commentArray;

      $postArray[] = $postData;

      //simpan id paling atas post position baru
      $postRequestIDTop = $postRow['post_id'];
      $postCounter++;
    }

    $jsonReply = new \stdClass();
    $jsonReply->count = $postCounter;
    $jsonReply->toppostid = $postRequestIDTop;
    $jsonReply->posts = $postArray;

    echo json_encode($jsonReply);
    exit();
  } elseif ($_POST['requestmode'] == "older" && isset($_POST['bottomid'])) {
    $postFetchQuery = "SELECT * FROM post WHERE (username IN (SELECT receiving_username FROM follow WHERE initiator_username = ?) OR username = ?) AND post_id < ? ORDER BY post_time DESC LIMIT ?";
    $postFetch = $pdo->prepare($postFetchQuery);
    $postFetch->execute([$_SESSION['username'], $_SESSION['username'], $_POST['bottomid'], $fetchlimit]);

    $postArray = [];
    $postCounter = 0;
    $postRequestIDBottom = $_POST['bottomid'];
    while ($postRow = $postFetch->fetch()) {
      $postData = new \stdClass();
      $postData->postid = $postRow['post_id'];
      $postData->postusername = $postRow['username'];
      $postData->postdatetime = $postRow['post_time'];
      $postData->postcaption = $postRow['caption'];
      $postData->postextension = $postRow['extension'];

      if ($postRow['username'] == $_SESSION['username']) {
        $postData->deleteperms = 1;
      } else {
        $postData->deleteperms = 0;
      }

      //fetch user profile pic extension
      $profPicQuery = "SELECT extension FROM user WHERE username = ?";
      $profPic = $pdo->prepare($profPicQuery);
      $profPic->execute([$postRow['username']]);
      $profPicFetched = $profPic->fetch();

      $postData->profpicext = $profPicFetched['extension'];

      //fetch like status
      $likeStatQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE username = ? AND post_id = ?";
      $likeStat = $pdo->prepare($likeStatQuery);
      $likeStat->execute([$_SESSION['username'], $postRow['post_id']]);
      $likeStatFetched = $likeStat->fetch();

      if ($likeStatFetched['x'] > 0) {
        $postData->likestatus = 1;
      } else {
        $postData->likestatus = 0;
      }

      //fetch like count
      $likeCountQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE post_id = ?";
      $likeCount = $pdo->prepare($likeCountQuery);
      $likeCount->execute([$postRow['post_id']]);
      $likeCountFetched = $likeCount->fetch();

      $postData->likecount = $likeCountFetched['x'];

      //fetch comment count
      $commentCountQuery = "SELECT COUNT(comment_id) AS x FROM comment WHERE post_id = ?";
      $commentCount = $pdo->prepare($commentCountQuery);
      $commentCount->execute([$postRow['post_id']]);
      $commentCountFetched = $commentCount->fetch();

      $postData->commentcount = $commentCountFetched['x'];

      //fetch max 2 top comment

      $commentFetchQuery = "SELECT username, comment_text FROM comment WHERE post_id = ? ORDER BY comment_time DESC LIMIT 2";
      $commentFetch = $pdo->prepare($commentFetchQuery);
      $commentFetch->execute([$postRow['post_id']]);

      $commentArray = [];

      while ($commentRow = $commentFetch->fetch()) {
        $commentData = new \stdClass();
        $commentData->username = $commentRow['username'];
        $commentData->text = $commentRow['comment_text'];
        $commentArray[] = $commentData;
      }

      $postData->postcomments = $commentArray;

      $postArray[] = $postData;

      //simpan id paling bawah
      $postRequestIDBottom = $postRow['post_id'];
      $postCounter++;
    }

    $jsonReply = new \stdClass();
    $jsonReply->count = $postCounter;
    $jsonReply->bottompostid = $postRequestIDBottom;
    $jsonReply->posts = $postArray;

    echo json_encode($jsonReply);
    exit();
  } else {
    header("Location: ./");
    exit();
  }
} else {
  header("Location: ./");
  exit();
}

?>
