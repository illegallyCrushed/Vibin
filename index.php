<?php
require_once './miscellaneous/phps/services/connect.php';

if(isset($_GET['username'])){ //kalo request user profile
    $page = "profile";
    require_once './miscellaneous/phps/pages/user.php'; //include file profile
}else{  //kalo gak
    if (isset($_SESSION['username'])) {
        //kalo udah login
        $page = "home";
        require_once './miscellaneous/phps/pages/home.php'; //include file home
    }else{
        //kalo blm login
        $page = "login";
        require_once './miscellaneous/phps/pages/login.php'; //include file login
    }
}
?>
