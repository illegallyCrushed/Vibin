<?php
require_once 'connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
  $strEmail = strtolower($_POST['email']);
  $strUsername = strtolower($_POST['username']);
  $strPassword = $_POST['password'];

  $strEmailStatus = 0;
  $strUsernameStatus = 0;
  $strPasswordStatus = 0;

  //cek email
  if (strlen($strEmail) != 0) {
    //kalo email gak kosong, cek email pake regular expr
    $emailRegEx = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
    $regexEmailVerify = preg_match($emailRegEx, $strEmail);
    if ($regexEmailVerify) {
      //kalo lolos test regex, cek kalo email udah ada di database
      $emailCheckQuery = "SELECT COUNT(email) AS x FROM user WHERE email = ?";
      $emailCheck = $pdo->prepare($emailCheckQuery);
      $emailCheck->execute([$strEmail]);
      $emailCheckResult = $emailCheck->fetch();
      if ($emailCheckResult['x'] == 0) {
        $strEmailStatus = 0;
      } else {
        $strEmailStatus = 3;
      }
    } else {
      //kalo gak lolos test regex
      $strEmailStatus = 2;
    }
  } else {
    //kalo email kosong
    $strEmailStatus = 1;
  }

  //cek username
  if (strlen($strUsername) != 0) {
    //kalo username gak kosong, cek ukuran
    if (strlen($strUsername) <= 20 && strlen($strUsername) >= 5) {
      //kalo ukuran aman, cek username pake regex dan cek huruf pertama huruf
      $usernameRegEx = '/[^a-z0-9\.\_]/';
      $usernameRegEx2 = '/^[a-z]+/';
      $regexUsernameVerify = preg_match($usernameRegEx, $strUsername);
      $regexUsernameVerify2 = preg_match($usernameRegEx2, $strUsername);
      if (!$regexUsernameVerify && $regexUsernameVerify2) {
        //kalo lolos regex, cek kalo username udah ada di database
        $usernameCheckQuery = "SELECT COUNT(username) AS x FROM user WHERE username = ?";
        $usernameCheck = $pdo->prepare($usernameCheckQuery);
        $usernameCheck->execute([$strUsername]);
        $usernameCheckResult = $usernameCheck->fetch();
        if ($usernameCheckResult['x'] == 0) {
          $notAllowed = ["user", "username", "login", "register", "index.php", "post", ".htaccess", "404.php", "messages", "miscellaneous", "assets", "404"];
          if (!in_array($strUsername, $notAllowed)) {
            //cek user terlarang
            $strUsernameStatus = 0;
          } else {
            $strUsernameStatus = 5;
          }
        } else {
          $strUsernameStatus = 4;
        }
      } else {
        $strUsernameStatus = 3;
      }
    } else {
      //kalo ukuran gak aman
      $strUsernameStatus = 2;
    }
  } else {
    //kalo username kosong
    $strUsernameStatus = 1;
  }

  //cek password
  if (strlen($strPassword) != 0) {
    //kalo password gak kosong, cek ukuran
    if (strlen($strPassword) <= 50 && strlen($strPassword) >= 6) {
      //kalo ukuran aman
      $strPasswordStatus = 0;
    } else {
      //kalo ukuran gak aman
      $strPasswordStatus = 2;
    }
  } else {
    //kalo password kosong
    $strPasswordStatus = 1;
  }

  if ($strEmailStatus == 0 && $strUsernameStatus == 0 && $strPasswordStatus == 0) {
    //kalo semua aman, push ke database
    $registerPushQuery = "INSERT INTO user (username, password, email, biography, realname, extension) VALUES (?,?,?,?,?,?)";
    $registerPush = $pdo->prepare($registerPushQuery);
    $registerPush->execute([$strUsername, $strPassword, $strEmail, "", "", ""]);
  }

  $errorCodes = new \stdClass();
  $errorCodes->email = $strEmailStatus;
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
