<?php
require_once 'connect.php';
header('Content-Type: application/json');

$fetchlimit = 99999;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username']) && isset($_POST['requestmode'])) {
  $jsonReply = new \stdClass();
  $actionArray = [];
  if ($_POST['requestmode'] == "fetch") {
    //set semua ke read
    $actionReadQuery = "UPDATE `action` SET action_read_status = 1 WHERE username = ? AND action_read_status = 0";
    $actionRead = $pdo->prepare($actionReadQuery);
    $actionRead->execute([$_SESSION['username']]);
    
    //fetch data
    $actionFetchQuery = "SELECT
    action_id,
    action_time,
    action_type,
    `action`.like_id AS like_id,
    `action`.comment_id AS comment_id,
    `action`.follow_id AS follow_id,
    `like`.username AS like_user_id,
    comment.username AS comment_user_id,
    follow.initiator_username AS follow_user_id,
    like_user.extension AS like_user_extension,
    comment_user.extension AS comment_user_extension,
    follow_user.extension AS follow_user_extension,
    `like`.post_id AS like_post_id,
    comment.post_id AS comment_post_id,
    like_post.extension AS like_post_extension,
    comment_post.extension AS comment_post_extension
FROM action
 LEFT OUTER JOIN `like` ON `action`.like_id = `like`.like_id
 LEFT OUTER JOIN comment ON `action`.comment_id = comment.comment_id
 LEFT OUTER JOIN follow ON `action`.follow_id = follow.follow_id
 LEFT OUTER JOIN user AS like_user ON `like`.username = like_user.username
 LEFT OUTER JOIN user AS comment_user ON comment.username = comment_user.username
 LEFT OUTER JOIN user AS follow_user ON follow.initiator_username = follow_user.username
 LEFT OUTER JOIN post AS like_post ON `like`.post_id = like_post.post_id
 LEFT OUTER JOIN post AS comment_post ON comment.post_id = comment_post.post_id
WHERE action.username = ?
ORDER BY action_time DESC
LIMIT ?";
    $actionFetch = $pdo->prepare($actionFetchQuery);
    $actionFetch->execute([$_SESSION['username'], $fetchlimit]);
    $bottomActionID = 0;
    while ($actionRow = $actionFetch->fetch()) {
      $actionData = new \stdClass();
      $actionData->actionid = $actionRow['action_id'];
      $actionData->actiontype = $actionRow['action_type'];
      $actionData->actiontime = $actionRow['action_time'];
      if ($actionRow['action_type'] == "like") {
        //kalo diri sendiri jangan
        if ($_SESSION['username'] == $actionRow['like_user_id']) {
          continue;
        }
        //data buat like
        $actionData->likeid = $actionRow['like_id'];
        $actionData->likeuserid = $actionRow['like_user_id'];
        $actionData->likeuserext = $actionRow['like_user_extension'];
        $actionData->likepostid = $actionRow['like_post_id'];
        $actionData->likepostext = $actionRow['like_post_extension'];
      } elseif ($actionRow['action_type'] == "comment") {
        //kalo diri sendiri jangan
        if ($_SESSION['username'] == $actionRow['comment_user_id']) {
          continue;
        }
        //data buat comment
        $actionData->commentid = $actionRow['comment_id'];
        $actionData->commentuserid = $actionRow['comment_user_id'];
        $actionData->commentuserext = $actionRow['comment_user_extension'];
        $actionData->commentpostid = $actionRow['comment_post_id'];
        $actionData->commentpostext = $actionRow['comment_post_extension'];
      } elseif ($actionRow['action_type'] == "follow") {
        //data buat follow
        $actionData->followid = $actionRow['follow_id'];
        $actionData->followuserid = $actionRow['follow_user_id'];
        $actionData->followuserext = $actionRow['follow_user_extension'];

        $checkFollowedQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE initiator_username = ? AND receiving_username = ?";
        $checkFollowed = $pdo->prepare($checkFollowedQuery);
        $checkFollowed->execute([$_SESSION['username'], $actionRow['follow_user_id']]);
        $checkFollowedFetch = $checkFollowed->fetch();
        $actionData->followstatus = 0;
        if ($checkFollowedFetch['x'] > 0) {
          $actionData->followstatus = 1;
        }
      }
      $actionArray[] = $actionData;
      $bottomActionID = $actionRow['action_id'];
    }
  } elseif ($_POST['requestmode'] == "more" && isset($_POST['bottomactionid'])) {
    //fetch more
  }
  $jsonReply->actions = $actionArray;
  $jsonReply->bottomactionid = $bottomActionID;
  echo json_encode($jsonReply);
  exit();
} else {
  header("Location: ./");
  exit();
}

?>
