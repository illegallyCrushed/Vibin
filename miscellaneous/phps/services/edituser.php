<?php
require_once 'connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username']) && isset($_POST['requestmode'])) {
  //fetch user data
  $userFetchQuery = "SELECT * FROM user WHERE username = ?";
  $userFetch = $pdo->prepare($userFetchQuery);
  $userFetch->execute([$_SESSION['username']]);
  $userData = $userFetch->fetch();
  $jsonReply = new \stdClass();
  $errorCodes = new \stdClass();
  if ($_POST['requestmode'] == "init") {
    $userReply = new \stdClass();
    $userReply->username = $userData['username'];
    $userReply->realname = $userData['realname'];
    $userReply->biography = $userData['biography'];
    $userReply->extension = $userData['extension'];
    $jsonReply->userdata = $userReply;
  } elseif ($_POST['requestmode'] == "push") {
    if (isset($_POST['status']) && isset($_POST['realname']) && isset($_POST['biography'])) {
      $imgStatus = 5;
      $imgProcessedStatus = 5;
      $setExtension = $userData['extension'];
      $setRealname = $_POST['realname'];
      $setBiography = $_POST['biography'];

      if ($_POST['status'] == "removed") {
        $setExtension = "";
        unlink("../../assets/profiles/" . $userData['username'] . "." . $userData['extension']);
      } elseif ($_POST['status'] == "changed") {
        if (isset($_FILES['profpic'])) {
          $imgStatus = 0;
          $imgProcessedStatus = 0;
          $imagePath = $_FILES['profpic']['tmp_name'];
          $imageType = exif_imagetype($imagePath);
          $imageExt = "";
          $imgExportPath = "";
          if ($imageType == IMAGETYPE_JPEG) {
            //kalo jpg, process gambar sebagai jpg
            $imageExif = exif_read_data($imagePath);
            if (isset($imageExif['Orientation'])) {
              $imageOrientation = $imageExif['Orientation'];
            } else {
              $imageOrientation = 1;
            }

            $imgStatus = 1;
            $imageExt = "jpg";
            $imgHandler = imagecreatefromjpeg($imagePath);
            if ($imgHandler) {
              //adjust orientation
              switch ($imageOrientation) {
                case 1: // nothing
                  break;

                case 2: // horizontal flip
                  $imgHandler = imageflip($imgHandler, IMG_FLIP_HORIZONTAL);
                  break;

                case 3: // 180 rotate left
                  $imgHandler = imagerotate($imgHandler, 180, 0);
                  break;

                case 4: // vertical flip
                  $imgHandler = imageflip($imgHandler, IMG_FLIP_VERTICAL);
                  break;

                case 5: // vertical flip + 90 rotate right
                  $imgHandler = imageflip($imgHandler, IMG_FLIP_VERTICAL);
                  $imgHandler = imagerotate($imgHandler, -90, 0);
                  break;

                case 6: // 90 rotate right
                  $imgHandler = imagerotate($imgHandler, -90, 0);
                  break;

                case 7: // horizontal flip + 90 rotate right
                  $imgHandler = imageflip($imgHandler, IMG_FLIP_HORIZONTAL);
                  $imgHandler = imagerotate($imgHandler, -90, 0);
                  break;

                case 8: // 90 rotate left
                  $imgHandler = imagerotate($imgHandler, 90, 0);
                  break;
              }
              $imgScaled = imagescale($imgHandler, 480);
              if ($imgScaled) {
                $imgExportPath = "../../assets/profiles/temps/tempfile_" . $_SESSION['username'] . ".jpg";
                $imgExport = imagejpeg($imgScaled, $imgExportPath);
                if ($imgExport) {
                  $imgProcessedStatus = 1;
                  imagedestroy($imgHandler);
                  imagedestroy($imgScaled);
                }
              }
            }
          } elseif ($imageType == IMAGETYPE_PNG) {
            //kalo png, process gambar sebagai png
            $imgStatus = 2;
            $imageExt = "png";
            $imgHandlerTemp = imagecreatefrompng($imagePath);
            $imgSizeData = getimagesize($imagePath);
            if ($imgHandlerTemp) {
              $imgHandler = imagecreatetruecolor($imgSizeData[0], $imgSizeData[1]);
              imagealphablending($imgHandlerTemp, false);
              imagesavealpha($imgHandlerTemp, true);
              imagealphablending($imgHandler, false);
              imagesavealpha($imgHandler, true);
              imagecopyresampled($imgHandler, $imgHandlerTemp, 0, 0, 0, 0, $imgSizeData[0], $imgSizeData[1], $imgSizeData[0], $imgSizeData[1]);
              if ($imgHandler) {
                $imgScaled = imagescale($imgHandler, 480);
                if ($imgScaled) {
                  imagealphablending($imgScaled, false);
                  imagesavealpha($imgScaled, true);
                  $imgExportPath = "../../assets/profiles/temps/tempfile_" . $_SESSION['username'] . ".png";
                  $imgExport = imagepng($imgScaled, $imgExportPath);
                  if ($imgExport) {
                    $imgProcessedStatus = 2;
                    imagedestroy($imgHandler);
                    imagedestroy($imgHandlerTemp);
                    imagedestroy($imgScaled);
                  }
                }
              }
            }
          } elseif ($imageType == IMAGETYPE_GIF) {
            $imgStatus = 3;
            $imgProcessedStatus = 3;
            $imageExt = "gif";
            $imgExportPath = $_FILES['profpic']['tmp_name'];
            //kalo gif
          } else {
            //kalo bukan gambar
            $imgStatus = 0;
            $imgProcessedStatus = 0;
          }
          if ($imgStatus != 0 && $imgProcessedStatus != 0) {
            $imgStorePath = "../../assets/profiles/";
            if ($userData['extension'] != "") {
              unlink("../../assets/profiles/" . $userData['username'] . "." . $userData['extension']);
            }
            if ($imageExt == "gif") {
              move_uploaded_file($imgExportPath, $imgStorePath . $_SESSION['username'] . "." . $imageExt);
            } else {
              rename($imgExportPath, $imgStorePath . $_SESSION['username'] . "." . $imageExt);
            }
            $setExtension = $imageExt;
          }
        }
      }
      $modifyUserQuery = "UPDATE user SET realname  = ?, biography = ?, extension  = ? WHERE username = ?";
      $modifyUser = $pdo->prepare($modifyUserQuery);
      $modifyUser->execute([trim($setRealname), trim($setBiography), $setExtension, $_SESSION['username']]);
      $_SESSION['extension'] = $setExtension;
    }
    $errorCodes->imgstatus = $imgStatus;
    $errorCodes->imgprocessedstatus = $imgProcessedStatus;
    $jsonReply->errorcode = $errorCodes;
  } else {
    header('Location: ./');
    exit();
  }
  echo json_encode($jsonReply);
  exit();
} else {
  header('Location: ./');
  exit();
}
?>
