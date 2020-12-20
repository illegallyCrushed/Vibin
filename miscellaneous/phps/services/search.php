<?php
require_once 'connect.php';
header('Content-Type: application/json');

$fetchlimit = 100;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['requestmode']) && isset($_POST['searchquery']) && isset($_SESSION['username'])) {
  $jsonReply = new \stdClass();
  $searchArray = [];
  $strSearchQuery = "%" . trim($_POST['searchquery']) . "%";
  if ($_POST['requestmode'] == "fetch") {
    //penjelasan algoritma search priority
    //membuat score (score) dengan membandingkan posisi ditemukannya substring pada username & realname:
    //kalo posisi realname < posisi username dan posisi realname bukan 0 (not found):
    //jadikan jadikan posisi realname sebagai score
    //kalo gak, cek posisi username 0 (not found) ato bukan:
    //kalo bukan 0 jadikan posisi username sebagai score
    //kalo 0 posisi realname sebagai score
    //membuat score tiebreaker (score2), yang mengutamakan match username lebih dulu daripada realname:
    //kalo username 0 (not found) score 2 = 1
    //kalo realname 0 (not found) score 2 = 0
    //gak mungkin 2 2 nya 0, maka gak cocok pada statement like nya
    //membuat score tiebreaker ke 2 (score3), yang mengutamakan username yang lebih pendek:
    //membuat orderby score,score2,score3,username ,sehingga
    //1.diurutkan sesuai posisi match
    //2.diurutkan berdasarkan prioritas match
    //3.diurutkan berdasarkan panjang username
    //4.diurutkan secara alphabet username
    //~nsv
    $searchFetchQuery = "SELECT username, realname, extension, 
        IF((LOCATE(?,realname) < LOCATE(?,username) AND LOCATE(?,realname) <> 0) 
            , LOCATE(?,realname)
            , IF(LOCATE(?,username) <> 0
                , LOCATE(?,username)
            , LOCATE(?,realname))) 
        AS score, 
        IF((LOCATE(?,username) <> 0)
            , 0
            , 1) 
        AS score2,
        CHAR_LENGTH(username)
        AS score3
        FROM user WHERE username LIKE ? OR realname LIKE ? 
            ORDER BY score, score2, score3, username LIMIT ?";
    $searchFetch = $pdo->prepare($searchFetchQuery);
    $searchFetch->execute([
      trim($_POST['searchquery']),
      trim($_POST['searchquery']),
      trim($_POST['searchquery']),
      trim($_POST['searchquery']),
      trim($_POST['searchquery']),
      trim($_POST['searchquery']),
      trim($_POST['searchquery']),
      trim($_POST['searchquery']),
      $strSearchQuery,
      $strSearchQuery,
      $fetchlimit,
    ]);
    $bottomSearchID = "";
    while ($searchRow = $searchFetch->fetch()) {
      if (isset($_POST['exclude'])) {
        if ($_SESSION['username'] == $searchRow['username']) {
          continue;
        }
      }
      $searchData = new \stdClass();
      $searchData->username = $searchRow['username'];
      $searchData->realname = $searchRow['realname'];
      $searchData->extension = $searchRow['extension'];
      $searchArray[] = $searchData;
      $bottomSearchID = $searchRow['username'];
    }
  } elseif ($_POST['requestmode'] == "more" && isset($_POST['bottomsearchid'])) {
  }
  $jsonReply->searches = $searchArray;
  $jsonReply->bottomsearchid = $bottomSearchID;
  echo json_encode($jsonReply);
  exit();
} else {
  header("Location: ./");
  exit();
}

?>
