<?php

$fetchlimit = 99999;

require_once './miscellaneous/phps/services/connect.php';
$page = "profile";
if (!isset($_GET['username'])) {
  header("Location: ./404.php");
}
if ($_GET['username'] == "") {
  header("Location: ./404.php");
}

//check kalo username ada sekaligus fetch data
$checkUserQuery = "SELECT username, biography, realname, extension, COUNT(username) AS x FROM user WHERE username = ?";
$checkUser = $pdo->prepare($checkUserQuery);
$checkUser->execute([$_GET['username']]);
$checkUserFetch = $checkUser->fetch();
if ($checkUserFetch['x'] == 0) {
  //kalo gaada, error404
  header("Location: ./404.php");
}

$accessUsername = "";
$loggedIn = false;
if (isset($_SESSION['username'])) {
  //kalo logged in
  $accessUsername = $_SESSION['username'];
  $loggedIn = true;
}

//fetch data
$profileData = new \stdClass();
$profileData->username = $checkUserFetch['username'];
$profileData->realname = $checkUserFetch['realname'];
$profileData->biography = $checkUserFetch['biography'];
$profileData->extension = $checkUserFetch['extension'];

if ($loggedIn) {
  //check followed
  $checkFollowedQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE initiator_username = ? AND receiving_username = ?";
  $checkFollowed = $pdo->prepare($checkFollowedQuery);
  $checkFollowed->execute([$_SESSION['username'], $_GET['username']]);
  $checkFollowedFetch = $checkFollowed->fetch();

  $profileData->followed = 0;
  if ($checkFollowedFetch['x'] > 0) {
    $profileData->followed = 1;
  }

  //check follow you
  $checkFollowYouQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE initiator_username = ? AND receiving_username = ?";
  $checkFollowYou = $pdo->prepare($checkFollowYouQuery);
  $checkFollowYou->execute([$_GET['username'], $_SESSION['username']]);
  $checkFollowYouFetch = $checkFollowYou->fetch();

  $profileData->followyou = 0;
  if ($checkFollowYouFetch['x'] > 0) {
    $profileData->followyou = 1;
  }
}

//fetch followers count
$checkFollowersQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE receiving_username = ?";
$checkFollowers = $pdo->prepare($checkFollowersQuery);
$checkFollowers->execute([$_GET['username']]);
$checkFollowersFetch = $checkFollowers->fetch();

$profileData->followers = $checkFollowersFetch['x'];

//fetch following count
$checkFollowingQuery = "SELECT COUNT(follow_id) AS x FROM follow WHERE initiator_username = ?";
$checkFollowing = $pdo->prepare($checkFollowingQuery);
$checkFollowing->execute([$_GET['username']]);
$checkFollowingFetch = $checkFollowing->fetch();

$profileData->following = $checkFollowingFetch['x'];

//fetct post count
$checkPostQuery = "SELECT COUNT(post_id) AS x FROM post WHERE username = ?";
$checkPost = $pdo->prepare($checkPostQuery);
$checkPost->execute([$_GET['username']]);
$checkPostFetch = $checkPost->fetch();

$profileData->posts = $checkPostFetch['x'];

//fetch gallery data
$galleryFetchQuery = "SELECT post_id, extension FROM post WHERE username = ? ORDER BY post_time DESC LIMIT ?";
$galleryFetch = $pdo->prepare($galleryFetchQuery);
$galleryFetch->execute([$_GET['username'], $fetchlimit]);

$galleryArray = [];

while ($galleryRow = $galleryFetch->fetch()) {
  $galleryData = new \stdClass();
  $galleryData->id = $galleryRow['post_id'];
  $galleryData->extension = $galleryRow['extension'];

  //note: nambah like & comment count?
  $galleryArray[] = $galleryData;
}

$bottomGalleryID = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once './miscellaneous/phps/addons/libraries.php'; ?>
    <?php require_once './miscellaneous/phps/addons/metatags.php'; ?>

    <title>Vibin</title>
    <style>
    .content-container{
        position: relative;
        left: 50%;
        transform: translate(-50%,0);
        padding-top: 80px;
        padding-bottom: 70px;
        display: block;
        width: 100%;
        max-width: 975px;
        color: black;
        font-size: 20pt;
        text-align: center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container{
        width: 100%;
        border-style: solid;
        border-color: #dbdbdb;
        border-bottom-width: 1px;
        padding-bottom: 20px;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container-top{
        float: left;
        margin-left: 5%;
        height: auto;
        display: grid;
        grid-template-columns: auto 1fr;
        grid-template-rows: 1fr;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container-top-img{
        display: grid; 
        place-items: center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container-top-img img{
        float: left;
        width: 23vw;
        height: 23vw;
        max-width: 200px;
        max-height: 200px;
        border-radius: 50%;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container-top-detail{
        float: left;
        height: auto;
        display: grid;
        grid-template-columns: 33.333333% 33.333333% 33.333333%;
        position: absolute;
        max-width: 640px;
        width: 60vw;
        left: 210px;
        top: 100px;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container-top-detail-posts{
        display: grid;
        place-items: center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container-top-detail-posts-inner-number{
        font-weight: 900;
        font-size:  16px;
    }

    .profile-header-container-top-detail-posts-inner-desc{
        font-size:  13px;
    }

    .profile-header-container-top-detail-follower{
        display: grid;
        place-items: center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container-top-detail-follower-inner-number{
        font-weight: 900;
        font-size: 16px;
        cursor: pointer;
    }

    .profile-header-container-top-detail-follower-inner-desc{
        font-size:  13px;
        cursor: pointer;
    }

    .profile-header-container-top-detail-following{
        display: grid;
        place-items: center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .profile-header-container-top-detail-following-inner-number{
        font-weight: 900;
        font-size: 16px;
        cursor: pointer;
    }

    .profile-header-container-top-detail-following-inner-desc{
        font-size: 13px;
        cursor: pointer;
    }

    .profile-header-container-info{
        float: left;
        width: 60vw;
        margin-top: 80px;
        margin-bottom: 50px;
        margin-left: 45px;
        text-align: left;
        padding: 0 0;
        max-width: 640px;
        
    }

    .profile-header-container-info-username{
        font-size: 18px;
        font-weight: 600;
        display: inline;
        float: left;
    }

    .profile-header-container-info-followstatus{
        width: 70px;
        font-size: 10px;
        font-weight: 900;
        float: left;
        margin-left: 10px;
        margin-top: 4px;
        padding: 2px;
        background: #0095ff;
        color: white;
        border-radius: 10px;
        display: grid;
        place-items:center;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    
    .profile-header-container-info-realname{
        font-size: 14px;
        font-weight: 600;
        clear: both;
    }

    .profile-header-container-info-bio{
        font-size: 14px;
        overflow-wrap: break-word;
        clear: both;
    }

    .profile-header-container-actions{
        clear: both;
        height: 75px;
        padding-top:30px;
        display: flex;
        justify-content: space-around;
        margin: 0 calc(5% - 3px);
        margin-top: 10px;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .profile-header-container-actions-follow{
        background: #0095ff;
        flex: 3 0 20%;
        font-size: 13px;
        font-weight: 600;
        display: grid;
        place-items: center;
        margin: 3px;
        border-radius: 7px;
        cursor: pointer;
        color: white;
    }

    .profile-header-container-actions-following{
        background: white;
        flex: 3 0 20%;
        font-size: 13px;
        font-weight: 600;
        display: grid;
        place-items: center;
        margin: 3px;
        border-style: solid;
        border-color: #dbdbdb;
        border-width: 2px;
        border-radius: 7px;
        cursor: pointer;
    }

    .profile-header-container-actions-message{
        flex: 3 0 20%;
        font-size: 13px;
        font-weight: 600;
        display: grid;
        place-items: center;
        margin: 3px;
        border-style: solid;
        border-color: #dbdbdb;
        border-width: 2px;
        border-radius: 7px;
        cursor: pointer;
    }
    
    .profile-header-container-actions-edit{
        flex: 3 0 20%;
        font-size: 13px;
        font-weight: 600;
        display: grid;
        place-items: center;
        margin: 3px;
        border-style: solid;
        border-color: #dbdbdb;
        border-width: 2px;
        border-radius: 7px;
        cursor: pointer;
    }
    
    .profile-gallery-container{
        width: 100%;
        position: relative;
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }
    
    .profile-gallery-container-post{
        width: 33%;
        padding-top: 33%;
        border-style:solid;
        border-width: 1px;
        border-color: white;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        
    }

    .profile-gallery-container-post-images{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
    }

    .profile-gallery-container-post-hires{
        top: 0;
        left: 0;
        width: 100%;
        min-height:100%;
        object-fit: cover;
        transition: filter 0.75s;
        filter: blur(6px);
        opacity: 0;
        z-index: 2;
    }

    .profile-gallery-container-post-lowres{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height:100%;
        object-fit: cover;
        filter: blur(5px);
        z-index: 1;
    }

    .profile-gallery-container-post-overlay{
        position: absolute;
        height: 100%;
        width: 100%;
        top:0;
        left: 0;
        display: grid;
        place-items: center;
        transition: opacity 1s;
        z-index: 3;
    }

    @media (max-width: 576px) {

        .profile-header-container-top{
            float: none;
            width: 90%;
        }

        .profile-header-container-top-img{
           display: grid;
           place-items: center;
        }

        .profile-header-container-top-img img{
            max-width: auto;
            max-height: auto;
        }

        .profile-header-container-top-detail{
            height: 100%;
            grid-template-columns: 30% 37% 33%;
            position: static;
            max-width: auto;
            width: auto;
            left: auto;
            top: auto;
        }

        .profile-header-container-top-detail-posts{
            place-items: center end;
        }

        .profile-header-container-top-detail-follower{
            place-items: center end;
        }

        .profile-header-container-top-detail-following{
            place-items: center end;
        }

        .profile-header-container-info{
            float: none;
            width: 100%;
            margin-top: 10px;
            padding: 0 5%;
            margin-bottom: 0;
            margin-left: 0;
            max-width: auto;
        }

        .profile-header-container-actions{
            height: 35px;
            padding-top: 0;
        }

    }

    #fileImageEditRequest{
        width: 0;
        height: 0;
        left: -10000px;
        position:absolute;
    }
    
    </style>
</head>
    <body>
    <?php require_once './miscellaneous/phps/addons/navbar.php'; ?>
    <script>

        <?php if ($loggedIn) { ?>    
        function newProfileCallback(obj){
            if(obj.files.length != 0){
            let verify = obj.files[0].name.split(".");
            let fileex = verify[verify.length-1].toLowerCase();
            if(fileex && (fileex == "jpg"||fileex == "jpeg" || fileex == "png" || fileex == "gif")){
                document.querySelector(".editmenu-profilepic img").src = URL.createObjectURL(obj.files[0]);
                $(".editmenu-actions-remove").removeClass("d-none");
                $("#fileImageEditRequest").data("status","changed");
            }else{
                    $.dialog({
                        title: 'Error',
                        icon: 'fa fa-warning',
                        columnClass: 'jconfheightignore col',
                        content: 'Only Supports JPG, PNG, or GIF File',
                        draggable: false,
                        animateFromElement: false,
                        backgroundDismiss: true,
                        containerFluid: true,
                        animationBounce: 1,
                        offsetTop: 0,
                        offsetBottom: 0,
                        onClose: ()=>{
                            obj.value="";
                        }
                    });
                }
            }
        }

        function editProfile(){
            //ajax fetch old data
            $.ajax({
                url:"./miscellaneous/phps/services/edituser.php",
                data:{
                    requestmode: "init"
                    },
                method: "POST",
                success:(data)=>{
                    let username = data.userdata.username;
                    let realname = data.userdata.realname;
                    let biography = data.userdata.biography;
                    let extension = data.userdata.extension;
                    let profpicset = "false";
                    $("#fileImageEditRequest").data("status","notset");
                    if(extension != ""){
                        profpicset = "true";
                        $("#fileImageEditRequest").data("status","set");
                    }

                    $.confirm({
                        title: 'Edit Profile',
                        closeIcon: true,
                        columnClass: 'jqueryconf-pop-setwidth col',
                        content: 
                        `
                        <style>
                            .editmenu-base{
                                width: 100%;
                                max-height: 100%;
                                font-size: 30px;
                                position: relative;
                            }
                            .editmenu-profilepic{
                                display: grid;
                                place-items: center;
                                width: 100%;
                                height: auto;
                            }
                        
                            .editmenu-profilepic-frame{
                                width: 250px;
                                height: 250px;
                                position: relative;
                            }
                        
                            .editmenu-profilepic-frame img{
                                width: 100%;
                                height: 100%!important;
                                border-radius: 50%;
                                object-fit: cover;
                            }
                            .editmenu-actions{
                                display: flex;
                                width: 70%;
                                margin: 20px 15%;
                                height: 35px;
                            }
                        
                            .editmenu-actions-remove{
                                background: #ed4956;
                                flex: 2 0 20%;
                                font-size: 13px;
                                font-weight: 600;
                                display: grid;
                                place-items: center;
                                margin: 3px;
                                border-radius: 7px;
                                cursor: pointer;
                                color: white;
                            
                            }
                            .editmenu-actions-change{
                                background: #0095ff;
                                flex: 2 0 20%;
                                font-size: 13px;
                                font-weight: 600;
                                display: grid;
                                place-items: center;
                                margin: 3px;
                                border-radius: 7px;
                                cursor: pointer;
                                color: white;
                            
                            }
                            .editmenu-realname{
                                width: 100%;
                                height: auto;
                                font-size: 14px;
                                padding: 20px;
                            }
                        
                            .editmenu-realname label{
                                font-weight: 500;
                                padding-bottom: 5px;
                            }
                        
                            .editmenu-realname input{
                                width: 100%;
                                padding: 10px;
                                border-style: solid;
                                border-color: #dbdbdb;
                                border-bottom-width: 1px;
                                outline: 0;
                            }
                        
                            .editmenu-bio{
                                padding: 20px;
                                height: auto;
                                width: 100%;
                                position: relative;
                                overflow: hidden;
                                font-size: 14px;
                            }
                        
                            .editmenu-bio label{
                                font-weight: 500;
                                padding-bottom: 5px;
                            }
                        
                            .editmenu-bio-textarea{
                                width: 100%;
                                padding: 10px;
                                min-height: 30px;
                                height: 45px;
                                border-style: solid;
                                border-color: #dbdbdb;
                                border-bottom-width: 1px;
                                outline: 0px;
                                resize: none;
                            }
                        
                            @media (max-width: 350px) {
                                .editmenu-profilepic{
                                    margin: 0 5px;
                                    width: calc(100% - 10px);
                                }
                            
                                .editmenu-profilepic-frame{
                                    width: 210px;
                                    height: 210px;
                                    position: relative;
                                }   
                            
                                .editmenu-actions{
                                    width: 90%;
                                    margin: 20px 5%;
                                }
                            }
                        
                        </style>
                        <div class="editmenu-base">
                            <div class="editmenu-profilepic">
                                <div class="editmenu-profilepic-frame">
                                    <img src="./miscellaneous/assets/profiles/`+username+`.`+extension+`">
                                </div>
                            </div>
                            <div class="editmenu-actions">
                                <div class="editmenu-actions-remove d-none" onclick="removeProfPic()">
                                    Remove Picture
                                </div>
                                <div class="editmenu-actions-change" onclick="changeProfPic()">
                                    Change Picture
                                </div>
                            </div>
                            <div class="editmenu-realname">
                                <label>Display Name</label>
                                <input placeholder="Display Name" class="editmenu-realname-input"></input>
                            </div>
                            <div class="editmenu-bio">
                                <label>Biography</label>
                                <textarea class="editmenu-bio-textarea" oninput="checkBioArea(this)" placeholder="Biography"></textarea>
                            </div>

                        </div>
                        <script>
                            
                            $(".editmenu-realname-input").val(\``+realname+`\`);
                            $(".editmenu-bio-textarea").val(\``+biography+`\`);

                            if(`+profpicset+` == true){
                                $(".editmenu-actions-remove").removeClass("d-none");
                            }

                            function checkBioArea(obj){
                                obj.style.height = 0;
                                obj.style.height =( obj.scrollHeight + 5 )+ 'px';
                                $(".jconfirm-content").scrollTop($(".jconfirm-content")[0].scrollHeight);
                            };
                            function changeProfPic(){
                                $("#fileImageEditRequest").trigger('click');
                            };
                            function removeProfPic(){
                                document.querySelector(".editmenu-profilepic-frame img").src = "./miscellaneous/assets/profiles/noprofile.jpg";
                                document.querySelector("#fileImageEditRequest").value = "";
                                $("#fileImageEditRequest").data("status","removed");
                            };
                        <\/script>
                        `,
                        draggable: false,
                        animateFromElement: false,
                        backgroundDismiss: true,
                        containerFluid: true,
                        animationBounce: 1,
                        offsetTop: 0,
                        offsetBottom: 0,
                        theme: 'bootstrap',
                        onClose: ()=>{
                            document.querySelector("#fileImageEditRequest").value = "";
                        },
                        buttons:{
                            post: {
                                text: 'Save',
                                btnClass: 'btn-primary jcustom-button',
                                action: function(){
                                    let formData = new FormData();
                                    let strrealname = this.$content.find('.editmenu-realname-input').val().trim();
                                    let strbiography = this.$content.find('.editmenu-bio-textarea').val().trim();
                                    let strstatus = $("#fileImageEditRequest").data("status");

                                    formData.append("requestmode", "push");
                                    formData.append("realname", strrealname);
                                    formData.append("biography", strbiography);
                                    formData.append("status", strstatus);
                                    if($("#fileImageEditRequest").val()!=""){
                                        formData.append("profpic", $("#fileImageEditRequest")[0].files[0]);
                                    }
                                    $.ajax({
                                        url:"./miscellaneous/phps/services/edituser.php",
                                        data: formData,
                                        method: "POST",
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        success:(data)=>{
                                            if(data.errorcode.imgstatus !=0 && data.errorcode.imgprocessedstatus !=0 ){
                                                window.location.reload();
                                            }else{
                                                $.confirm({
                                                    title: 'Something went wrong...',
                                                    icon: 'far fa-times-circle',
                                                    columnClass: 'jconfheightignore col',
                                                    content: 'Try again later',
                                                    draggable: false,
                                                    theme: 'modern',
                                                    type: 'red',
                                                    animateFromElement: false,
                                                    backgroundDismiss: true,
                                                    containerFluid: true,
                                                    animationBounce: 1,
                                                    offsetTop: 0,
                                                    offsetBottom: 0,
                                                    buttons: {
                                                        close:{
                                                            text: 'Oh no..',
                                                            btnClass: 'btn-red',
                                                        }
                                                    },
                                                    onClose: ()=>{
                                                    }
                                                });
                                            }
                                        },
                                        error:(x,y,z)=>{
                                            console.log(x,y,z);
                                            $.confirm({
                                                title: 'Something went wrong...',
                                                icon: 'far fa-times-circle',
                                                columnClass: 'jconfheightignore col',
                                                content: 'Try again later',
                                                draggable: false,
                                                theme: 'modern',
                                                type: 'red',
                                                animateFromElement: false,
                                                backgroundDismiss: true,
                                                containerFluid: true,
                                                animationBounce: 1,
                                                offsetTop: 0,
                                                offsetBottom: 0,
                                                buttons: {
                                                    close:{
                                                        text: 'Oh no..',
                                                        btnClass: 'btn-red',
                                                    }
                                                },
                                                onClose: ()=>{
                                                }
                                            });
                                        }
                                    });
                                }
                            },
                        }
                    });

                },
                error:(x,y,z)=>{
                    console.log(x,y,z);
                    $.confirm({
                        title: 'Something went wrong...',
                        icon: 'far fa-times-circle',
                        columnClass: 'jconfheightignore col',
                        content: 'Try again later',
                        draggable: false,
                        theme: 'modern',
                        type: 'red',
                        animateFromElement: false,
                        backgroundDismiss: true,
                        containerFluid: true,
                        animationBounce: 1,
                        offsetTop: 0,
                        offsetBottom: 0,
                        buttons: {
                            close:{
                                text: 'Oh no..',
                                btnClass: 'btn-red',
                            }
                        },
                        onClose: ()=>{
                        }
                    });
                }
            });
            
        }

        

        <?php } ?>

        function previewFollower(obj) {
            let userID = $(obj).data("userid");           
            $.dialog({
                title: 'Followers',
                columnClass: 'appwidthnorm jqueryconf-pop-setwidth col',
                content:
                `
                <style>
                    .follower-base{
                        width: 100%;
                        height: 100%;
                        overflow: hidden;
                        position: relative;
                    }

                    .follower-searchbar{
                        margin: 0 0.25%;
                        width: 99.5%;
                        height: 40px;
                        position: relative;
                        display: grid;
                        place-items: center;
                        border-style: solid;
                        border-color: #dbdbdb;
                        border-width: 3px;
                        border-radius: 12px;
                        margin-bottom: 15px;
                    }

                    .follower-searchbar input{
                        font-size: 14px;
                        width: 97%;
                        margin: 0 1.5%;
                        outline: 0px;
                    }

                    .follower-container{
                        width: 100%;
                        height: auto;
                        overflow: auto;
                    }
                    .messagebox-conversations-content{
                        height: calc(100% - 54px);
                        width: 100%;
                        overflow-y: auto;
                    }
                
                    .follower-container-tabs{
                        width: 100%;
                        height: 60px;
                        cursor: pointer;
                        display: block;
                        position: relative;
                        overflow: hidden;
                        padding-left: 5px;
                    }
                
                    .follower-container-tabs:hover{
                        background: rgba(245,245,245,0.5);
                    }

                    .follower-container-tabs-header{
                        float: left;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .follower-container-tabs-header-img{
                        float: left;
                        display: grid;
                        place-items: center;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .follower-container-tabs-header-img img{
                        width: 45px;
                        height: 45px!important;
                        border-radius: 50%;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .follower-container-tabs-header-text{
                        float: left;
                        padding-left: 10px;
                        text-align: left;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .follower-container-tabs-header-text-username{
                        font-size: 14px;
                        font-weight: 500;
                        color: black;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .follower-container-tabs-header-text-realname{
                        font-size: 14px;
                        font-weight: 500;
                        color: #8e8e8e;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                </style>
                <div class="follower-base">
                <!--
                    <div class="follower-searchbar">
                        <input oninput="queryUsers(this)" placeholder="Follower"></input>
                    </div> 
                -->
                    <div class="follower-container">

                    </div>
                </div>
                <script>
                    /*
                    function queryUsers(obj){
                        if(obj.value){
                            let strproc = obj.value.toLowerCase();
                            let regfill = strproc.replace(/[^abcdefghijklmnopqrstuvwxyz._0123456789]/ig,"");
                            obj.value = regfill;
                            $(".follower-container").html("");
                            for (let i = 0 ; i < 100; i++){
                                $(".follower-container").append(regfill+"</br>");
                        }
                        else{
                            $(".follower-container").html("");
                            $(".follower-container").append("empty");
                        }
                    }
                    */

                    function createFollowerObject(JSONfollowerdata){

                        let requiredbasefollower = 
                        \`
                        <div id="user_%/username/%" class="follower-container-tabs" data-username="%/username/%" onclick="window.location.href='./'+$(this).data('username')">
                            <div class="follower-container-tabs-header">
                                <div class="follower-container-tabs-header-img">
                                    <img src="./miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
                                </div>
                                <div class="follower-container-tabs-header-text">
                                    <div class="follower-container-tabs-header-text-username">
                                        %/username/%
                                    </div>
                                    <div class="follower-container-tabs-header-text-realname">
                                        %/realname/%
                                    </div>
                                </div>
                            </div>
                        </div>
                        \`;

                        //base data
                        let username = JSONfollowerdata.username;
                        let realname = JSONfollowerdata.realname;
                        let profpicextension = JSONfollowerdata.extension;

                        let followerBuildStr = requiredbasefollower.replaceAll("%/username/%",username);
                        followerBuildStr = followerBuildStr.replaceAll("%/realname/%",realname);
                        followerBuildStr = followerBuildStr.replaceAll("%/profpicextension/%",profpicextension);

                        let jQueryDOMobj = $.parseHTML(followerBuildStr);

                        return jQueryDOMobj;
                    }

                    var bottomFollowerID = 0;

                    function fetchFollowers(){
                        $.ajax({
                            url: "./miscellaneous/phps/services/follow.php",
                            data: {
                                requestmode: "fetchfollowers",
                                userid: "`+userID+`"
                            },
                            method: "POST",
                            success: (data)=>{
                                $(".follower-container").html("");
                                bottomFollowerID = data.bottomfollowerid;
                                data.followers.forEach((followerJSON)=>{
                                    let followerDOMObj = createFollowerObject(followerJSON);
                                    $(".follower-container").append(followerDOMObj);
                                });
                            },
                            error: ()=>{

                            }           
                        });
                    }

                    fetchFollowers();

                <\/script>
                `,
                draggable: false,
                animateFromElement: false,
                backgroundDismiss: true,
                containerFluid: true,
                animationBounce: 1,
                offsetTop: 0,
                offsetBottom: 0,
                theme: 'bootstrap',
                onClose: ()=>{
                }
            });
        }

        function previewFollowing(obj) {
            let userID = $(obj).data("userid");           
            $.dialog({
                title: 'Followings',
                columnClass: 'appwidthnorm jqueryconf-pop-setwidth col',
                content:
                `
                <style>
                    .following-base{
                        width: 100%;
                        height: 100%;
                        overflow: hidden;
                        position: relative;
                    }

                    .following-searchbar{
                        margin: 0 0.25%;
                        width: 99.5%;
                        height: 40px;
                        position: relative;
                        display: grid;
                        place-items: center;
                        border-style: solid;
                        border-color: #dbdbdb;
                        border-width: 3px;
                        border-radius: 12px;
                        margin-bottom: 15px;
                    }

                    .following-searchbar input{
                        font-size: 14px;
                        width: 97%;
                        margin: 0 1.5%;
                        outline: 0px;
                    }

                    .following-container{
                        width: 100%;
                        height: auto;
                        overflow: auto;
                    }
                    .messagebox-conversations-content{
                        height: calc(100% - 54px);
                        width: 100%;
                        overflow-y: auto;
                    }
                
                    .following-container-tabs{
                        width: 100%;
                        height: 60px;
                        cursor: pointer;
                        display: block;
                        position: relative;
                        overflow: hidden;
                        padding-left: 5px;
                    }
                
                    .following-container-tabs:hover{
                        background: rgba(245,245,245,0.5);
                    }

                    .following-container-tabs-header{
                        float: left;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .following-container-tabs-header-img{
                        float: left;
                        display: grid;
                        place-items: center;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .following-container-tabs-header-img img{
                        width: 45px;
                        height: 45px!important;
                        border-radius: 50%;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .following-container-tabs-header-text{
                        float: left;
                        padding-left: 10px;
                        text-align: left;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .following-container-tabs-header-text-username{
                        font-size: 14px;
                        font-weight: 500;
                        color: black;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .following-container-tabs-header-text-realname{
                        font-size: 14px;
                        font-weight: 500;
                        color: #8e8e8e;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                </style>
                <div class="following-base">
                <!--
                    <div class="following-searchbar">
                        <input oninput="queryUsers(this)" placeholder="Following"></input>
                    </div> 
                -->
                    <div class="following-container">

                    </div>
                </div>
                <script>
                    /*
                    function queryUsers(obj){
                        if(obj.value){
                            let strproc = obj.value.toLowerCase();
                            let regfill = strproc.replace(/[^abcdefghijklmnopqrstuvwxyz._0123456789]/ig,"");
                            obj.value = regfill;
                            $(".following-container").html("");
                            for (let i = 0 ; i < 100; i++){
                                $(".following-container").append(regfill+"</br>");
                        }
                        else{
                            $(".following-container").html("");
                            $(".following-container").append("empty");
                        }
                    }
                    */

                    function createFollowingObject(JSONfollowingdata){

                        let requiredbasefollowing = 
                        \`
                        <div id="user_%/username/%" class="following-container-tabs" data-username="%/username/%" onclick="window.location.href='./'+$(this).data('username')">
                            <div class="following-container-tabs-header">
                                <div class="following-container-tabs-header-img">
                                    <img src="./miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
                                </div>
                                <div class="following-container-tabs-header-text">
                                    <div class="following-container-tabs-header-text-username">
                                        %/username/%
                                    </div>
                                    <div class="following-container-tabs-header-text-realname">
                                        %/realname/%
                                    </div>
                                </div>
                            </div>
                        </div>
                        \`;

                        //base data
                        let username = JSONfollowingdata.username;
                        let realname = JSONfollowingdata.realname;
                        let profpicextension = JSONfollowingdata.extension;

                        let followingBuildStr = requiredbasefollowing.replaceAll("%/username/%",username);
                        followingBuildStr = followingBuildStr.replaceAll("%/realname/%",realname);
                        followingBuildStr = followingBuildStr.replaceAll("%/profpicextension/%",profpicextension);

                        let jQueryDOMobj = $.parseHTML(followingBuildStr);

                        return jQueryDOMobj;
                    }

                    var bottomFollowingID = 0;

                    function fetchFollowings(){
                        $.ajax({
                            url: "./miscellaneous/phps/services/follow.php",
                            data: {
                                requestmode: "fetchfollowing",
                                userid: "`+userID+`"
                            },
                            method: "POST",
                            success: (data)=>{
                                $(".following-container").html("");
                                bottomFollowingID = data.bottomfollowingid;
                                data.followings.forEach((followingJSON)=>{
                                    let followingDOMObj = createFollowingObject(followingJSON);
                                    $(".following-container").append(followingDOMObj);
                                });
                            },
                            error: ()=>{

                            }           
                        });
                    }

                    fetchFollowings();

                <\/script>
                `,
                draggable: false,
                animateFromElement: false,
                backgroundDismiss: true,
                containerFluid: true,
                animationBounce: 1,
                offsetTop: 0,
                offsetBottom: 0,
                theme: 'bootstrap',
                onClose: ()=>{
                }
            });
        }

        function replaceHiRes(obj){
            let postID = $(obj).data("postid");
            $("#post_"+postID+" .profile-gallery-container-post-hires").css("opacity",1);
            $("#post_"+postID+" .profile-gallery-container-post-hires").css("filter","blur(0px)");
            $("#post_"+postID+" .profile-gallery-container-post-lowres").css("opacity","0");
            $("#post_"+postID+" .spinner-border").remove();
            setTimeout(() => {
                $("#post_"+postID+" .profile-gallery-container-post-lowres").remove();
            }, 1000);
        }

        function profileFollowReq(obj){
            <?php if ($loggedIn) { ?>
                let username = $(obj).data("userid");
                $.ajax({
                    url:"./miscellaneous/phps/services/follow.php",
                    data:{
                        requestmode:"add",
                        userid: username
                    },
                    method: "POST",
                    success:(data)=>{
                        if(data.errorcode.followstatus != 1){
                            $(".profile-header-container-top-detail-follower-inner-number").html(data.followcount);
                            $(obj).addClass("d-none");
                            $(".profile-header-container-actions-following").removeClass("d-none");
                        }
                    },
                    error:()=>{}
                });
            
            <?php } else { ?>
                window.location.href = "./";
            <?php } ?>
        }
        function profileUnfollowReq(obj){
            <?php if ($loggedIn) { ?>
                let username = $(obj).data("userid");
                $.ajax({
                    url:"./miscellaneous/phps/services/follow.php",
                    data:{
                        requestmode:"remove",
                        userid: username
                    },
                    method: "POST",
                    success:(data)=>{
                        if(data.errorcode.followstatus != 1){
                            $(".profile-header-container-top-detail-follower-inner-number").html(data.followcount);
                            $(obj).addClass("d-none");
                            $(".profile-header-container-actions-follow").removeClass("d-none");
                        }
                    },
                    error:()=>{}
                });

            <?php } else { ?>
                window.location.href = "./";
            <?php } ?>
        }

        function messageAction(obj){

            <?php if ($loggedIn) { ?>
            //pending

            window.location.href = "./messages/?username=<?= $profileData->username ?>&extension=<?= $profileData->extension ?>";
            
            <?php } else { ?>
                window.location.href = "./";
            <?php } ?>

        }
        
    </script>

    <div class="content-container">
        <div class="profile-header-container">
            <div class="profile-header-container-top">
                <div class="profile-header-container-top-img">
                    <img src="./miscellaneous/assets/profiles/<?= $profileData->username ?>.<?= $profileData->extension ?>"></img>
                </div>
                <div class="profile-header-container-top-detail">
                    <div class="profile-header-container-top-detail-posts">
                        <div class="profile-header-container-top-detail-posts-inner">
                            <div class="profile-header-container-top-detail-posts-inner-number"><?= $profileData->posts ?></div>
                            <div class="profile-header-container-top-detail-posts-inner-desc">Posts</div>
                        </div>
                    </div>
                    <div class="profile-header-container-top-detail-follower">
                        <div class="profile-header-container-top-detail-follower-inner" data-userid="<?= $profileData->username ?>" onclick="previewFollower(this)">
                            <div class="profile-header-container-top-detail-follower-inner-number"><?= $profileData->followers ?></div>
                            <div class="profile-header-container-top-detail-follower-inner-desc">Followers</div>
                        </div>
                    </div>
                    <div class="profile-header-container-top-detail-following">
                        <div class="profile-header-container-top-detail-following-inner" data-userid="<?= $profileData->username ?>" onclick="previewFollowing(this)">
                            <div class="profile-header-container-top-detail-following-inner-number"><?= $profileData->following ?></div>
                            <div class="profile-header-container-top-detail-following-inner-desc">Following</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-header-container-info">
                <div class="profile-header-container-info-username"><?= $profileData->username ?></div>
                <?php if ($loggedIn && $profileData->followyou == 1) { ?>
                    <div class="profile-header-container-info-followstatus">Follows You</div>
                <?php } ?>
                <div class="profile-header-container-info-realname"><?= $profileData->realname ?></div>
                <div class="profile-header-container-info-bio">
                    <?php echo str_replace("\n", "<br>", $profileData->biography); ?>
                </div>
            </div>
            <div class="profile-header-container-actions">
                <?php if ($accessUsername != $profileData->username) { ?>
                    <?php if (!$loggedIn || $profileData->followed == 0) { ?>
                        <div class="profile-header-container-actions-follow" data-userid="<?= $profileData->username ?>" onclick="profileFollowReq(this)">Follow</div>
                        <div class="profile-header-container-actions-following d-none" data-userid="<?= $profileData->username ?>" onclick="profileUnfollowReq(this)">Following</div>
                    <?php } elseif ($profileData->followed == 1) { ?>
                        <div class="profile-header-container-actions-follow d-none" data-userid="<?= $profileData->username ?>" onclick="profileFollowReq(this)">Follow</div>
                        <div class="profile-header-container-actions-following" data-userid="<?= $profileData->username ?>" onclick="profileUnfollowReq(this)">Following</div>
                    <?php } ?>

                    <div class="profile-header-container-actions-message" data-userid="<?= $profileData->username ?>" onclick="messageAction(this)">Message</div>
                <?php } else { ?>
                    <div class="profile-header-container-actions-edit" onclick="editProfile()">Edit Profile</div>
                <?php } ?>
            </div>
        </div>
        <div class="profile-gallery-container">

        <?php foreach ($galleryArray as $galleryData) { ?>

            <div class="profile-gallery-container-post" id="post_<?= $galleryData->id ?>" data-postid="<?= $galleryData->id ?>" onclick="window.location.href='./post/'+$(this).data('postid')">
                <div class="profile-gallery-container-post-images">
                    <img class="profile-gallery-container-post-hires" data-postid="<?= $galleryData->id ?>" onload="replaceHiRes(this)" src="./miscellaneous/assets/posts/<?= $galleryData->id ?>.<?= $galleryData->extension ?>">
                    <img class="profile-gallery-container-post-lowres" src="./miscellaneous/assets/posts/<?= $galleryData->id ?>_small.<?= $galleryData->extension ?>">
                </div>
                <div class="profile-gallery-container-post-overlay">
                    <div class="spinner-border text-light" style="opacity:0.7;width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

        <?php $bottomGalleryID = $galleryData->id;} ?>
        
        </div>
    </div>
    <input id="fileImageEditRequest" type="file" accept="image/*" onchange="newProfileCallback(this)"></input>
    <script>
        var bottomGalleryID = <?= $bottomGalleryID ?>;
    </script>
    </body>
</html>