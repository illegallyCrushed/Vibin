<?php
require_once 'connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username']) && isset($_POST['postid'])) {
  $deleteErrorCode = 0;
  //cek di database ada postnya atau gak, pake session username buat ownership
  $checkPostQuery = "SELECT extension, COUNT(post_id) AS x FROM post WHERE post_id = ? AND username = ?";
  $checkPost = $pdo->prepare($checkPostQuery);
  $checkPost->execute([$_POST['postid'], $_SESSION['username']]);
  $checkPostFetch = $checkPost->fetch();
  if ($checkPostFetch['x'] != 0) {
    //kalo ada delete
    $deletePostQuery = "DELETE FROM post WHERE post_id = ? AND username = ?";
    $deletePost = $pdo->prepare($deletePostQuery);
    $deletePost->execute([$_POST['postid'], $_SESSION['username']]);

    unlink("../../assets/posts/" . $_POST['postid'] . "." . $checkPostFetch['extension']);
    unlink("../../assets/posts/" . $_POST['postid'] . "_small." . $checkPostFetch['extension']);

    //gak perlu remove action, likes, comments soalnya on delete cascade
  } else {
    //kalo gak ada error
    $deleteErrorCode = 1;
  }

  $errorCodes = new \stdClass();
  $errorCodes->deletestatus = $deleteErrorCode;

  $jsonReply = new \stdClass();
  $jsonReply->errorcode = $errorCodes;
  echo json_encode($jsonReply);
  exit();
} else {
  header('Location: ./');
  exit();
}

?>
