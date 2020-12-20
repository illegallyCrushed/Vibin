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

  $commentStatus = 0;
  $errorCodes = new \stdClass();
  $jsonReply = new \stdClass();

  if ($checkPostFetch['x'] != 0) {
    //kalo post ada
    if ($_POST['requestmode'] == "add" && isset($_POST['text'])) {
      //addcomment
      if (strlen($_POST['text']) > 0) {
        $addCommentQuery = "INSERT INTO comment (username, post_id, comment_text) VALUES (?,?,?)";
        $addComment = $pdo->prepare($addCommentQuery);
        $addComment->execute([$_SESSION['username'], $_POST['postid'], $_POST['text']]);

        //fetch comment id
        $commentIDQuery = "SELECT comment_id FROM comment WHERE post_id = ? AND username = ? AND comment_text = ?";
        $commentID = $pdo->prepare($commentIDQuery);
        $commentID->execute([$_POST['postid'], $_SESSION['username'], $_POST['text']]);
        $commentIDFetch = $commentID->fetch();

        //add ke actions buat notif
        $addCommentActionQuery = "INSERT INTO `action` (username, from_username, comment_id, action_read_status, action_type) VALUES (?,?,?,?,?)";
        $addCommentAction = $pdo->prepare($addCommentActionQuery);
        $addCommentAction->execute([$checkPostFetch['username'], $_SESSION['username'], $commentIDFetch['comment_id'], 0, "comment"]);
        $commentStatus = 0;
      } else {
        //kalo comment kosongan
        $commentStatus = 5;
      }
    } elseif ($_POST['requestmode'] == "remove" && isset($_POST['commentid'])) {
      //removecomment
      //cek kalo comment udah ada ato blm
      $checkCommentQuery = "SELECT username, COUNT(comment_id) AS x FROM comment WHERE comment_id = ?";
      $checkComment = $pdo->prepare($checkCommentQuery);
      $checkComment->execute([$_POST['commentid']]);
      $checkCommentFetch = $checkComment->fetch();
      if ($checkCommentFetch['x'] != 0) {
        //kalo ada, cek authority (kalo comment milik user ato post milik user)
        if ($checkCommentFetch['username'] == $_SESSION['username'] || $checkPostFetch['username'] == $_SESSION['username']) {
          $removeCommentQuery = "DELETE FROM comment WHERE comment_id = ?";
          $removeComment = $pdo->prepare($removeCommentQuery);
          $removeComment->execute([$_POST['commentid']]);

          //gak perlu remove action soalnya on delete cascade

          $commentStatus = 0;
        } else {
          $commentStatus = 4;
        }
      } else {
        //kalo gak ada comment
        $commentStatus = 3;
      }
    } elseif ($_POST['requestmode'] == "fetch" && isset($_POST['bottomcommentid'])) {
      //buat fetch comment baru
      $commentFetchQuery =
        "SELECT comment_id, user.username AS username, user.extension AS extension, comment_text, comment_time  FROM comment JOIN user ON comment.username = user.username WHERE post_id = ? AND comment_id > ? ORDER BY comment_time ASC LIMIT ?";
      $commentFetch = $pdo->prepare($commentFetchQuery);
      $commentFetch->execute([$_POST['postid'], $_POST['bottomcommentid'], $fetchlimit]);

      $commentArray = [];
      $commentRequestIDBottom = $_POST['bottomcommentid'];
      while ($commentRow = $commentFetch->fetch()) {
        $commentData = new \stdClass();
        $commentData->postid = $_POST['postid'];
        $commentData->id = $commentRow['comment_id'];
        $commentData->username = $commentRow['username'];
        $commentData->extension = $commentRow['extension'];
        $commentData->text = htmlspecialchars($commentRow['comment_text']);
        $commentData->datetime = $commentRow['comment_time'];
        $commentData->deleteperms = 0;
        if ($commentRow['username'] == $_SESSION['username'] || $checkPostFetch['username'] == $_SESSION['username']) {
          $commentData->deleteperms = 1;
        }
        $commentArray[] = $commentData;
        $commentRequestIDBottom = $commentRow['comment_id'];
      }

      $jsonReply->comments = $commentArray;
      $jsonReply->bottomcommentid = $commentRequestIDBottom;
    } else {
      header("Location: ./");
      exit();
    }
  } else {
    //kalo gak ada
    $commentStatus = 1;
  }

  $errorCodes->commentstatus = $commentStatus;
  $jsonReply->errorcode = $errorCodes;
  echo json_encode($jsonReply);
  exit();
} else {
  header("Location: ./");
  exit();
}

?>
