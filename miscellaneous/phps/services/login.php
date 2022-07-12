<?php
require_once 'connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
  $strUsername = strtolower($_POST['username']);
  $strPassword = $_POST['password'];
  $strUsernameStatus = 0;
  $strPasswordStatus = 0;

  //cek input email / username
  if (strlen($strUsername) != 0) {
    //kalo email/username gak kosong, cek email pake regular expr
    $emailRegEx = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
    $regexEmailVerify = preg_match($emailRegEx, $strUsername);
    if ($regexEmailVerify) {
      //kalo lolos regex, check di server sebagai email
      $emailCheckQuery = "SELECT COUNT(email) AS x, username, password, extension FROM user WHERE email = ?";
      $emailCheck = $pdo->prepare($emailCheckQuery);
      $emailCheck->execute([$strUsername]);
      $emailCheckResult = $emailCheck->fetch();
      if ($emailCheckResult['x'] == 1) {
        $strUsernameStatus = 0;
        $usernameReply = $emailCheckResult['username'];
        $passwordCheck = $emailCheckResult['password'];
        $extension = $emailCheckResult['extension'];
      } else {
        $strUsernameStatus = 3;
      }
    } else {
      //kalo gak lolos test regex check di server sebagai username
      $usernameCheckQuery = "SELECT COUNT(username) AS x, username, password, extension FROM user WHERE username = ?";
      $usernameCheck = $pdo->prepare($usernameCheckQuery);
      $usernameCheck->execute([$strUsername]);
      $usernameCheckResult = $usernameCheck->fetch();
      if ($usernameCheckResult['x'] == 1) {
        $strUsernameStatus = 0;
        $usernameReply = $usernameCheckResult['username'];
        $passwordCheck = $usernameCheckResult['password'];
        $extension = $usernameCheckResult['extension'];
      } else {
        $strUsernameStatus = 2;
      }
    }
  } else {
    //kalo email kosong
    $strUsernameStatus = 1;
  }

  //cek password
  if (strlen($strPassword) != 0) {
    //kalo password terisi
    if (isset($passwordCheck)) {
      //kalo acc ditemukan
      if (password_verify($strPassword, $passwordCheck)) {
        //kalo password bener
        $strPasswordStatus = 0;
      } else {
        //kalo password salah
        $strPasswordStatus = 2;
      }
    }
  } else {
    //kalo password kosong
    $strPasswordStatus = 1;
  }

  if ($strUsernameStatus == 0 && $strPasswordStatus == 0) {
    //kalo semua , set login
    $_SESSION['username'] = $usernameReply;
    $_SESSION['extension'] = $extension;
    //check in
    $checkInQuery = "UPDATE user SET last_login = now() WHERE username = ?";
    $checkIn = $pdo->prepare($checkInQuery);
    $checkIn->execute([$usernameReply]);
  }

  $errorCodes = new \stdClass();
  $errorCodes->username = $strUsernameStatus;
  $errorCodes->password = $strPasswordStatus;

  $jsonReply = new \stdClass();
  $jsonReply->errorcode = $errorCodes;

  echo json_encode($jsonReply);
  exit();
} else {
  header("Location: ./");
  exit();
}

?>
