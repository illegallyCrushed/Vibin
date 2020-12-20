<?php
require_once 'connect.php';
require '../class/GifCreator.php';
require '../class/GifFrameExtractor.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['caption']) && isset($_FILES['file']) && isset($_SESSION['username'])) {
  //cek kalo file berupa image jpg/png/gif
  $imagePath = $_FILES['file']['tmp_name'];
  $imageType = exif_imagetype($imagePath);

  $imgStatus = 0;
  $imgProcessedStatus = 0;
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
      $imgScaled = imagescale($imgHandler, 108);
      if ($imgScaled) {
        $imgExportPath = "../../assets/posts/temps/tempfile_" . $_SESSION['username'] . ".jpg";
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
        $imgScaled = imagescale($imgHandler, 108);
        if ($imgScaled) {
          imagealphablending($imgScaled, false);
          imagesavealpha($imgScaled, true);
          $imgExportPath = "../../assets/posts/temps/tempfile_" . $_SESSION['username'] . ".png";
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
    //kalo gif process gambar sebagai gif
    $imgStatus = 3;
    $imageExt = "gif";
    $imgHandler = imagecreatefromgif($imagePath);
    $imgSizeData = getimagesize($imagePath);

    if ($imgHandler) {
      //cek kalo animated GIF
      $gifExtractor = new GifFrameExtractor();
      $gifAnimaCheck = $gifExtractor->isAnimatedGif($imagePath);
      if ($gifAnimaCheck) {
        $gifFrames = $gifExtractor->extract($imagePath);
        $gifScaled = [];
        foreach ($gifFrames as $gifFrame) {
          $gifHandler = imagecreatetruecolor($imgSizeData[0], $imgSizeData[1]);
          imagealphablending($gifHandler, false);
          imagesavealpha($gifHandler, true);
          imagealphablending($gifFrame['image'], false);
          imagesavealpha($gifFrame['image'], true);
          imagecopyresampled($gifHandler, $gifFrame['image'], 0, 0, 0, 0, $imgSizeData[0], $imgSizeData[1], $imgSizeData[0], $imgSizeData[1]);
          $gifFrame = imagescale($gifHandler, 108);
          imagealphablending($gifFrame, false);
          imagesavealpha($gifFrame, true);
          $gifScaled[] = $gifFrame;
          imagedestroy($gifHandler);
        }
        $gifCreator = new GifCreator();
        $gifCreator->create($gifScaled, $gifExtractor->getFrameDurations(), 0);
        $imgExportPath = "../../assets/posts/temps/tempfile_" . $_SESSION['username'] . ".gif";
        file_put_contents($imgExportPath, $gifCreator->getGif());
        $imgProcessedStatus = 3;
        imagedestroy($imgHandler);
      } else {
        imagealphablending($imgHandler, false);
        imagesavealpha($imgHandler, true);
        $imgScaled = imagescale($imgHandler, 108);
        imagealphablending($imgScaled, false);
        imagesavealpha($imgScaled, true);
        if ($imgScaled) {
          $imgExportPath = "../../assets/posts/temps/tempfile_" . $_SESSION['username'] . ".gif";
          $imgExport = imagegif($imgScaled, $imgExportPath);
          if ($imgExport) {
            $imgProcessedStatus = 3;
            imagedestroy($imgHandler);
            imagedestroy($imgScaled);
          }
        }
      }
    }
  } else {
    //kalo bukan gambar, skip
    $imgStatus = 0;
    $imgProcessedStatus = 0;
  }

  if ($imgStatus != 0 && $imgProcessedStatus != 0) {
    //kalo file proccessing aman, push ke database

    $postAddQuery = "INSERT INTO post (username, caption, extension) VALUES (?,?,?)";
    $postAdd = $pdo->prepare($postAddQuery);
    $postAdd->execute([$_SESSION['username'], trim($_POST['caption']), $imageExt]);

    $postIDGetQuery = "SELECT post_id FROM post WHERE username = ? ORDER BY post_time DESC LIMIT 1";
    $postIDGet = $pdo->prepare($postIDGetQuery);
    $postIDGet->execute([$_SESSION['username']]);
    $postIDFetch = $postIDGet->fetch();
    $postID = $postIDFetch['post_id'];

    //move dan rename uploaded dan processed image
    $imgStorePath = "../../assets/posts/";
    rename($imgExportPath, $imgStorePath . $postID . "_small." . $imageExt);
    move_uploaded_file($imagePath, $imgStorePath . $postID . "." . $imageExt);
  }

  $errorCodes = new \stdClass();
  $errorCodes->filetype = $imgStatus;
  $errorCodes->filetypeprocessed = $imgProcessedStatus;

  $jsonReply = new \stdClass();
  $jsonReply->errorcode = $errorCodes;
  $jsonReply->newpostid = $postID;

  echo json_encode($jsonReply);
  exit();
} else {
  header("Location: ./");
  exit();
}

?>
