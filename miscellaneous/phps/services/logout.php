<?php
require_once 'connect.php';
$_SESSION['username'] = "";
session_destroy();
header("Location: ../../../");
exit();
?>
