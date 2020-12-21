<?php
require_once '../miscellaneous/phps/services/connect.php';
$page = "post";
if (!isset($_GET['postid'])) {
  header("Location: ../404.php");
}
if ($_GET['postid'] == "") {
  header("Location: ../404.php");
}

//check kalo post id ada
$checkPostQuery = "SELECT COUNT(post_id) AS x FROM post WHERE post_id = ?";
$checkPost = $pdo->prepare($checkPostQuery);
$checkPost->execute([$_GET['postid']]);
$checkPostFetch = $checkPost->fetch();
if ($checkPostFetch['x'] == 0) {
  //kalo gaada, error404
  header("Location: ../404.php");
}

$accessUsername = "";
$loggedIn = false;
if (isset($_SESSION['username'])) {
  //kalo logged in
  $accessUsername = $_SESSION['username'];
  $loggedIn = true;
}

//fetch data penting

$fetchlimit = 99999;
$postData = new \stdClass();

$postFetchQuery =
  "SELECT post.username AS post_username, post_time, caption, post.extension AS post_extension, user.extension AS user_extension FROM post JOIN user ON post.username = user.username WHERE post_id = ?";
$postFetch = $pdo->prepare($postFetchQuery);
$postFetch->execute([$_GET['postid']]);
$postRow = $postFetch->fetch();

$postData->postid = $_GET['postid'];
$postData->postusername = $postRow['post_username'];
$postData->postdatetime = $postRow['post_time'];
$postData->postcaption = $postRow['caption'];
$postData->postextension = $postRow['post_extension'];
$postData->profpicext = $postRow['user_extension'];

if ($loggedIn) {
  //fetch like status
  $likeStatQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE username = ? AND post_id = ?";
  $likeStat = $pdo->prepare($likeStatQuery);
  $likeStat->execute([$_SESSION['username'], $_GET['postid']]);
  $likeStatFetched = $likeStat->fetch();

  if ($likeStatFetched['x'] > 0) {
    $postData->likestatus = 1;
  } else {
    $postData->likestatus = 0;
  }
}

//fetch like count
$likeCountQuery = "SELECT COUNT(like_id) AS x FROM `like` WHERE post_id = ?";
$likeCount = $pdo->prepare($likeCountQuery);
$likeCount->execute([$_GET['postid']]);
$likeCountFetched = $likeCount->fetch();

$postData->likecount = $likeCountFetched['x'];

//fetch comment
$commentFetchQuery =
  "SELECT comment_id, comment.username AS comment_username, comment_text, comment_time, user.extension AS user_extension FROM comment JOIN user ON comment.username = user.username WHERE post_id = ? ORDER BY comment_time ASC LIMIT ?";
$commentFetch = $pdo->prepare($commentFetchQuery);
$commentFetch->execute([$_GET['postid'], $fetchlimit]);

$commentArray = [];

while ($commentRow = $commentFetch->fetch()) {
  $commentData = new \stdClass();
  $commentData->id = $commentRow['comment_id'];
  $commentData->username = $commentRow['comment_username'];
  $commentData->text = $commentRow['comment_text'];
  $commentData->time = $commentRow['comment_time'];
  $commentData->extension = $commentRow['user_extension'];
  $commentArray[] = $commentData;
}

$bottomCommentID = 0;

//
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once '../miscellaneous/phps/addons/libraries.php'; ?>
    <?php require_once '../miscellaneous/phps/addons/metatags.php'; ?>

    <title>Vibin</title>
    <style>
    .content-container{
        position: relative;
        left: 50%;
        transform: translate(-50%,0);
        padding-top: 80px;
        padding-bottom:70px;
        display: block;
        justify-content: center;
        width: 100%;
        max-width: 975px;
        color: black;
        font-size:20pt;
        text-align:center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    /* Post box generator styles */

    .postbox-base{
        width: 100%;
        background-color: #ffffff;
        border-style: solid;
        border-color: #dbdbdb;
        border-width: 1px;
        border-radius: 8px;
        margin-bottom: 60px;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .postbox-header{
        width: 100%;
        height: 60px;
        padding: 0 15px;
        border-bottom-width: 1px;
    }

    .postbox-header-poster{
        float: left;
        height: 100%;
        display: inline;
        cursor: pointer;
    }

    .postbox-header-poster img{
        float: left;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        margin-top: 12px;
    }

    .postbox-header-poster-username{
        float: left;
        color: black;
        font-size: 14px;
        font-weight: 600;
        transform: translate(0,-50%);
        margin-top: 30px;
        margin-left: 12px;
    }

    .postbox-header-poster-delete{
        float: right;
        width: 25px;
        height: 100%;
        display: grid;
        place-items: center;
        cursor: pointer;
    }

    .postbox-header-poster-delete svg{
        width: 25px;
    }

    .postbox-image{
        position: relative;
        overflow: hidden;
    }
    
    .postbox-image-hires{
        position: absolute;
        width: 100%;
        height: auto;
        transition: filter 0.75s;
        filter: blur(6px);
        opacity: 0;
        z-index: 2;
    }

    .postbox-image-lowres{
        width: 100%;
        height: auto;
        top: 0;
        left: 0;
        filter: blur(5px);
        z-index: 1;
    }

    .postbox-image-like{
        position: absolute;
        height: 100%;
        width: 100%;
        top:0;
        display: grid;
        place-items: center;
        transition: opacity 1s;
        z-index: 3;
        touch-action: pan-y !important; /*hammerjs noscroll fix*/
    }

    .postbox-image-like svg{
        position: absolute;
        width: 25%;
        transform: scale(0);
    }

    .postbox-action{
        width: 100%;
        padding-right: 10px;
        height: 45px;
        overflow: hidden;
        position: relative;
    }

    .postbox-action{
        width: 100%;
        padding-right: 10px;
        height: 45px;
        overflow: hidden;
        position: relative;
    }

    .postbox-action-like{
        position: relative;
        height: 50px;
        overflow: hidden;
        width: 45px;
    }

    .postbox-action-like-inactive{
        position: absolute;
        height: 50px;
        width: 50px;
        top: 3px;
        cursor: pointer;
    }

    .postbox-action-like-active{
        position: absolute;
        height: 50px;
        width: 50px;
        top: 3px;
        cursor: pointer;
    }

    .postbox-action-like-inactive svg{
        transform: scale(1);
        width: 25px;
    }

    .postbox-action-like-active svg{
        transform: scale(0);
        width: 25px;
    }

    .postbox-action-comment{
        position: absolute;
        left: 50px;
        top: 3px;
        cursor: pointer;
    }

    .postbox-action-comment svg{
        width: 23px;
    }

    .postbox-likecounter{
        height: 20px;
        font-size: 14px;
        text-align: left;
        padding-left: 12px;
        overflow: hidden;
    }

    .postbox-likecounter-text{
        display: inline;
        cursor: pointer;
    }
    .postbox-caption{
        width: 100%;
        font-size: 14px;
        min-height: 30px;
        text-align: left;
        padding-left: 12px;
        padding-top: 4px;
        line-height: 1.5;
        overflow-wrap: break-word;
        margin-bottom: 2px;
    }

    .postbox-caption-username{
        cursor: pointer;
        font-weight: 600;
    }

    .postbox-caption-text-content-more{
        color: #8e8e8e;
        cursor: pointer;
    }
    .postbox-comments{
        text-align: left;
        font-size: 14px;
        padding-top: 5px;
    }

    .postbox-comments-segment{
        margin-bottom: 10px;
        padding: 0 12px;
        padding-right: 14px;
        padding-top: 5px;
        position: relative;
        overflow: hidden;
    }

    .postbox-comments-segment-img{
        float: left;
        width: 34px;
        height: 100%;
        margin-right: 20px;
    }

    .postbox-comments-segment-img img{
        width: 34px;
        height: 34px!important;
        border-radius: 50%;
    }

    .postbox-comments-segment-inner{
        float: left;
        overflow-wrap: break-word;
        max-width: calc(100% - 90px);
    }
    .postbox-comments-segment-inner-username{
        cursor: pointer;
        font-weight: 600;
    }

    .postbox-comments-segment-inner-time{
        font-size: 12px;
        text-align: left;
        padding-top: 10px;
        color: #8e8e8e;
    }

    .postbox-comments-segment-delete{
        float: right;
        width: 20px;
        height: 100%;
        display: grid;
        place-items: center;
        cursor: pointer;
    }

    .postbox-comments-segment-delete svg{
        width: 20px;
    }

    .postbox-uploadtime{
        font-size: 11px;
        text-align: left;
        padding-left: 12px;
        padding-bottom: 8px;
        color: #8e8e8e;
    }

    .postbox-input-comment{
        width: 100%;
        min-height: 50px;
        position: sticky;
        bottom: 0;
        background: white;
        display: flex;
        justify-content: center;
        border-style: solid;
        border-color: #dbdbdb;
        border-top-width: 1px;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    .postbox-input-comment-textarea{
        width: 100%;
        min-height: 26px;
        height: 26px;
        font-size: 14px;
        font-weight: 400;
        margin: 20px;
        margin-top: 14px;
        margin-bottom: 10px;
        outline: 0px;
        resize: none;
    }

    .postbox-input-comment-textarea:disabled{
        background:white;
        cursor: not-allowed;
    }

    .postbox-input-comment-postbutton{
        width: 70px;
        height: auto;
        color: rgb(0,149,246);
        float: right;
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .postbox-input-comment-postbutton:disabled{
        opacity: 0.3;
        cursor: not-allowed;
    }

    @media (max-width: 975px) {
        body{
            background-color: white;
        }

        .content-container{
            padding-top: 54px;
        }

        .postbox-base{
            border-width: 0px;
            margin-bottom: 10px;
        }

        .postbox-input-comment{
            border-top-width: 0px;
        }

        .postbox-input-comment-textarea{
            border-style: solid;
            border-color: #dbdbdb;
            border-bottom-width: 1px;
        }

    }

    </style>
</head>
    <body>
    <?php require_once '../miscellaneous/phps/addons/navbar.php'; ?>
    <script>
    <?php if ($loggedIn) { ?>

        function deletePost(obj){
            //delete post
            let postID = $(obj).data("postid");
            let imgSource = $("#post_"+postID+" .postbox-image-lowres").attr("src");
            $.confirm({
                title: 'Delete this post?',
                columnClass: 'jqueryconf-pop-del col',
                content: '<img style="width: 125px; border-radius: 10px;" src="'+imgSource+'">',
                draggable: false,
                animateFromElement: false,
                backgroundDismiss: true,
                containerFluid: true,
                animationBounce: 1,
                offsetTop: 0,
                offsetBottom: 0,
                theme: 'modern',
                autoClose: 'cancel|10000',
                buttons:{
                    delete: {
                        text: 'Delete Post',
                        btnClass: 'btn-red',
                        action: function(){
                            $.ajax({
                                url:"../miscellaneous/phps/services/deletepost.php",
                                data:{
                                    postid: postID
                                },
                                method: "POST",
                                success:(data)=>{
                                    if(data.errorcode.deletestatus == 0){
                                        window.location.href="../";
                                    }else{
                                        $.confirm({
                                            title: 'Delete Failed',
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
                                error:()=>{
                                    $.confirm({
                                        title: 'Delete Failed',
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
                    cancel:{
                        text: 'Cancel',
                    }
                }
            });
        }

        function likeWhiteAnimation(postID){
            gsap.to("#post_"+ postID +" .postbox-image-like svg", { 
            transform:"scale(1)", 
            duration: 0.5, 
            ease: "elastic.out(1.75, 1)",
            onComplete: ()=>{
                    gsap.to("#post_" + postID + " .postbox-image-like svg", { 
                        transform: "scale(0)", 
                        duration: 0.2, 
                        delay: 0.25,
                        ease: "ease.out",
                    });x
                },
            }); 
        }

        function likeRedAnimation(postID,state){
            if(!state){
                gsap.to("#post_"+ postID +" .postbox-action-like-inactive svg", { 
                transform:"scale(0)", 
                duration: 0.1, 
                ease: "ease.out",
                onComplete: ()=>{
                        gsap.to("#post_" + postID + " .postbox-action-like-active svg", { 
                            transform: "scale(1)", 
                            duration: 0.2, 
                            ease: "elastic.out(1.25, 1)",
                        });
                    },
                });
            }else{
                gsap.to("#post_"+ postID +" .postbox-action-like-active svg", { 
                transform:"scale(0)", 
                duration: 0.1, 
                ease: "ease.out",
                onComplete: ()=>{
                        gsap.to("#post_" + postID + " .postbox-action-like-inactive svg", { 
                            transform: "scale(1)", 
                            duration: 0.2, 
                            ease: "elastic.out(1.25, 1)",
                        });
                    },
                });
            }
        }

        function likePushUpdate(postID,state){
                $("#post_"+postID+ " .postbox-image").attr("ondblclick","");
                $("#post_"+postID+ " .postbox-action-like").attr("onclick","");
                $("#post_"+postID).removeData("liked");

            if(state){
                $.ajax({
                    url: "../miscellaneous/phps/services/like.php",
                    data:{
                        requestmode: "remove",
                        postid: postID
                    },
                    method: "POST",
                    success:(data)=>{
                        if(data.errorcode.likestatus != 1){
                            let likestring = "";
                            let postLikeCount = data.likecount;
                            if(postLikeCount == 0){
                                likestring = "<b>Be the first one to like this</b>";
                            }else if(postLikeCount == 1){
                                likestring = "<b>1</b> Like";
                            }else{
                                likestring = "<b>"+postLikeCount+"</b> Likes";
                            }
                            $("#post_"+postID+" .postbox-likecounter-text").html(likestring);
                        }else{
                            $.confirm({
                                title: 'Post is no longer available',
                                icon: 'far fa-times-circle',
                                columnClass: 'jconfheightignore col',
                                content: '',
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
                                        text: 'Wait what?',
                                        btnClass: 'btn-red',
                                    }
                                },
                                onClose: ()=>{
                                    window.location.href = "../";
                                }
                            });
                        }
                    },
                    complete:()=>{
                        $("#post_"+postID+ " .postbox-image").attr("ondblclick","likeDoubleTap(this)");
                        $("#post_"+postID+ " .postbox-action-like").attr("onclick","likeToggle(this)");
                        $("#post_"+postID).data("liked",false);
                    }
                });
            }else{
                $.ajax({
                    url: "../miscellaneous/phps/services/like.php",
                    data:{
                        requestmode: "add",
                        postid: postID
                    },
                    method: "POST",
                    success:(data)=>{
                        if(data.errorcode.likestatus != 1){
                            let likestring = "";
                            let postLikeCount = data.likecount;
                            if(postLikeCount == 0){
                                likestring = "<b>Be the first one to like this</b>";
                            }else if(postLikeCount == 1){
                                likestring = "<b>1</b> Like";
                            }else{
                                likestring = "<b>"+postLikeCount+"</b> Likes";
                            }
                            $("#post_"+postID+" .postbox-likecounter-text").html(likestring);
                        }else{
                            $.confirm({
                                title: 'Post is no longer available',
                                icon: 'far fa-times-circle',
                                columnClass: 'jconfheightignore col',
                                content: '',
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
                                        text: 'Wait what?',
                                        btnClass: 'btn-red',
                                    }
                                },
                                onClose: ()=>{
                                    window.location.href = "../";
                                }
                            });
                        }
                    },
                    complete:()=>{
                        $("#post_"+postID+ " .postbox-image").attr("ondblclick","likeDoubleTap(this)");
                        $("#post_"+postID+ " .postbox-action-like").attr("onclick","likeToggle(this)");
                        $("#post_"+postID).data("liked",true);
                    }
                });
            }
        }

        function createCommentObject(JSONcommentdata){
            //template
            let requiredbaseComment=
            `
            <div class="postbox-comments-segment" id="comment_%/commentid/%" data-commentid="%/commentid/%" data-userid="%/username/%">
                <div class="postbox-comments-segment-img">
                        <img src="../miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
                </div>
                <div class="postbox-comments-segment-inner">
                    <span class="postbox-comments-segment-inner-username" data-userid="%/username/%" onclick="window.location.href='../'+$(this).data('userid')" >
                        <a href='../%/username/%'>%/username/%</a>
                    </span>
                    <span class="postbox-comments-segment-inner-text">
                        %/commenttext/%
                    </span>
                    <div class="postbox-comments-segment-inner-time"></div>
                    <script>
                       $("#comment_%/commentid/% .postbox-comments-segment-inner-time").html(convertDateToDuration('%/commentdatetime/%'));
                       $("#comment_%/commentid/% .postbox-comments-segment-inner-time").attr('title',convertDateToString('%/commentdatetime/%'));
                    <\/script>
                </div>
                %/deletebuttonarea/%
            </div>
            `;

            let optionalDeleteButton = 
            `
                <div class="postbox-comments-segment-delete" data-postid="%/postid/%" data-commentid="%/commentid/%" onclick="deleteComment(this)">
                    <?= file_get_contents("../miscellaneous/assets/trash_icon.svg") ?>
                </div>
            `
            ;
            //base data
            let commentID = JSONcommentdata.id;
            let commentUsername = JSONcommentdata.username;
            let profpicextension = JSONcommentdata.extension;
            let commenttext = JSONcommentdata.text.replaceAll("\n","<br>");
            let commenttime = JSONcommentdata.datetime;
            let deleteperms = JSONcommentdata.deleteperms;
            let postID = JSONcommentdata.postid;

            let deletebuttonarea = "";
            if(deleteperms == 1){
                deletebuttonarea = optionalDeleteButton;
            }

            let commentBuildStr = requiredbaseComment.replaceAll("%/commenttime/%",commenttime);
            commentBuildStr = commentBuildStr.replaceAll("%/username/%",commentUsername);
            commentBuildStr = commentBuildStr.replaceAll("%/deletebuttonarea/%",deletebuttonarea);
            commentBuildStr = commentBuildStr.replaceAll("%/profpicextension/%",profpicextension);
            commentBuildStr = commentBuildStr.replaceAll("%/commenttext/%",commenttext);
            commentBuildStr = commentBuildStr.replaceAll("%/commentid/%",commentID);
            commentBuildStr = commentBuildStr.replaceAll("%/postid/%",postID);
            let jQueryDOMobj = $.parseHTML(commentBuildStr, document, true);

            return jQueryDOMobj;
        }

        function fetchNewComment(postID){
            //fetch new comment
            $.ajax({
                url: "../miscellaneous/phps/services/comment.php",
                data: {
                    requestmode: "fetch",
                    postid: postID,
                    bottomcommentid: bottomCommentID
                },
                method: "POST",
                success:(data)=>{
                    if(data.errorcode.commentstatus != 1){
                        data.comments.forEach((commentJSON)=>{
                            let commentDOMObj = createCommentObject(commentJSON);
                            $("#post_"+postID+" .postbox-input-comment").before(commentDOMObj);
                        });
                        bottomCommentID = data.bottomcommentid;
                    }else{
                        $.confirm({
                            title: 'Post is no longer available',
                            icon: 'far fa-times-circle',
                            columnClass: 'jconfheightignore col',
                            content: '',
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
                                    text: 'Wait what?',
                                    btnClass: 'btn-red',
                                }
                            },
                            onClose: ()=>{
                                window.location.href = "../";
                            }
                        });
                    }
                },
                error:()=>{}
            });
        }

        function postComment(obj){
            //post new comment
            let postID = $(obj).data("postid");
            let strComment = $("#post_"+postID+" .postbox-input-comment-textarea").val();
            $("#post_"+postID+" .postbox-input-comment-textarea").val("");
            checkCommentArea($("#post_"+postID+" .postbox-input-comment-textarea")[0]);
            $.ajax({
                url: "../miscellaneous/phps/services/comment.php",
                data: {
                    requestmode: "add",
                    postid: postID,
                    text: strComment
                },
                method: "POST",
                success:(data)=>{
                    if(data.errorcode.commentstatus != 1){
                        const waitFetchNew = async()=>{
                            const commentFetching = await fetchNewComment(postID);
                            window.scrollTo(0,$("#comment_"+bottomCommentID).offset().top);
                        }
                        waitFetchNew();
                    }else{
                        $.confirm({
                            title: 'Post is no longer available',
                            icon: 'far fa-times-circle',
                            columnClass: 'jconfheightignore col',
                            content: '',
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
                                    text: 'Wait what?',
                                    btnClass: 'btn-red',
                                }
                            },
                            onClose: ()=>{
                                window.location.href = "../";
                            }
                        });
                    }
                },
                error:()=>{}
            });
        }

        function deleteComment(obj){
            //delete comment
            let commentID = $(obj).data("commentid");
            let postID = $(obj).data("postid");
            $.confirm({
                title: 'Delete this comment?',
                columnClass: 'jqueryconf-pop-del col',
                content: 
                `
                <div class="temp-container-comment" style="text-align: left!important;">
                </div>
                <script>
                    $('.temp-container-comment').append($('#comment_'+`+commentID+`).clone());
                    $('.temp-container-comment .postbox-comments-segment-delete').remove();
                <\/script>
                `,
                draggable: false,
                animateFromElement: false,
                backgroundDismiss: true,
                containerFluid: true,
                animationBounce: 1,
                offsetTop: 0,
                offsetBottom: 0,
                theme: 'modern',
                autoClose: 'cancel|10000',
                buttons:{
                    delete: {
                        text: 'Delete Comment',
                        btnClass: 'btn-red',
                        action: function(){
                            $.ajax({
                                url:"../miscellaneous/phps/services/comment.php",
                                data: {
                                    requestmode: "remove",
                                    postid: postID,
                                    commentid: commentID
                                },
                                method: "POST",
                                success:(data)=>{
                                    if(data.errorcode.commentstatus == 0){
                                        $('#comment_'+commentID).remove();
                                    }else if(data.errorcode.commentstatus == 1){
                                        $.confirm({
                                            title: 'Post is no longer available',
                                            icon: 'far fa-times-circle',
                                            columnClass: 'jconfheightignore col',
                                            content: '',
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
                                                    text: 'Wait what?',
                                                    btnClass: 'btn-red',
                                                }
                                            },
                                            onClose: ()=>{
                                                window.location.href = "../";
                                            }
                                        });
                                    }else{
                                        $.confirm({
                                            title: 'Delete Failed',
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
                                error:()=>{
                                    $.confirm({
                                        title: 'Delete Failed',
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
                    cancel:{
                        text: 'Cancel',
                    }
                }
            });
        }
    <?php } ?>

        function addHammerZoomTrigger(obj) {
            let postID = $(obj).data("postid");
            pinchListener = new Hammer(document.querySelector("#post_"+postID+" .postbox-image-like"), {});
            pinchListener.get('pinch').set({
                enable: true
            });
            var posX = 0,
                posY = 0,
                scale = 1,
                lastScale = 1,
                lastPosX = 0,
                lastPosY = 0,
                maxPosX = 0,
                maxPosY = 0,
                transform = "";
        


            pinchListener.on('pinch pinchend', (event) => {
                //pinch
                if (event.type == "pinch") {
                    //pan    
                    if (scale != 1) {
                    posX = lastPosX + event.deltaX;
                    posY = lastPosY + event.deltaY;
                    maxPosX = Math.ceil((scale - 1) * obj.clientWidth / 2);
                    maxPosY = Math.ceil((scale - 1) * obj.clientHeight / 2);
                    if (posX > maxPosX) {
                        posX = maxPosX;
                    }
                    if (posX < -maxPosX) {
                        posX = -maxPosX;
                    }
                    if (posY > maxPosY) {
                        posY = maxPosY;
                    }
                    if (posY < -maxPosY) {
                        posY = -maxPosY;
                    }
                }

                    scale = Math.max(0.999, Math.min(lastScale * (event.scale), 4));
                    transform =
                        "translate3d(" + posX + "px," + posY + "px, 0) " +
                        "scale3d(" + scale + ", " + scale + ", 1)";
                    obj.style.transform = transform;
                    obj.style.transition = "transform 0s";
                    obj.style.position = "fixed";
                    obj.style.zIndex = "1000";
                }
                if(event.type == "pinchend"){
                    obj.style.transform = "none";
                    obj.style.transition = "transform 0.2s cubic-bezier(0.36, 0.55, 0.19, 1)";
                    setTimeout(() => {
                        obj.style.position = "absolute";
                        obj.style.zIndex = "3";
                    }, 200);
                }
            
            });
        }

        function replaceHiRes(obj){
            let postID = $(obj).data("postid");
            $("#post_"+postID+" .postbox-image-hires").css("opacity",1);
            $("#post_"+postID+" .postbox-image-hires").css("filter","blur(0px)");
            $("#post_"+postID+" .postbox-image-lowres").css("opacity","0");
            $("#post_"+postID+" .spinner-border").remove();
            setTimeout(() => {
                $(obj).css("position","absolute");
                addHammerZoomTrigger(obj);
            }, 1000);
        }

        function likeDoubleTap(obj) {
            <?php if ($loggedIn) { ?>
                let postID = $(obj).data("postid");
                let state = $("#post_"+postID).data("liked");
                $(obj).attr("ondblclick","");
                setTimeout(() => {
                    $(obj).attr("ondblclick","likeDoubleTap(this)");
                }, 500);
                likeWhiteAnimation(postID);
                if(!state){
                    likeRedAnimation(postID,state);
                    likePushUpdate(postID,state);
                }
            <?php } else { ?>
                window.location.href = "../";
            <?php } ?>
        }

        function likeToggle(obj) {
            <?php if ($loggedIn) { ?>
                let postID = $(obj).data("postid");
                let state = $("#post_"+postID).data("liked");
                $(obj).attr("onclick","");
                setTimeout(() => {
                    $(obj).attr("onclick","likeToggle(this)");
                }, 250);
                likeRedAnimation(postID,state);
                likePushUpdate(postID,state);
            <?php } else { ?>
                window.location.href = "../";
            <?php } ?>
        }

        function checkCommentArea(obj){
            <?php if ($loggedIn) { ?>
                obj.style.height = 0;
                obj.style.height = obj.scrollHeight + 'px';
                if($(obj).val().length > 0)
                    $(obj).next().removeAttr("disabled");
                else
                    $(obj).next().attr("disabled","");
            <?php } else { ?>
                window.location.href = "../";
            <?php } ?>
        }

        function previewLike(obj) {
            <?php if ($loggedIn) { ?>
                let postID = $(obj).data("postid");           
                $.dialog({
                title: 'Likes',
                columnClass: 'appwidthnorm jqueryconf-pop-setwidth col',
                content:
                `
                <style>
                    .like-base{
                        width: 100%;
                        height: 100%;
                        overflow: hidden;
                        position: relative;
                    }

                    .like-searchbar{
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

                    .like-searchbar input{
                        font-size: 14px;
                        width: 97%;
                        margin: 0 1.5%;
                        outline: 0px;
                    }

                    .like-container{
                        width: 100%;
                        height: auto;
                        overflow: auto;
                    }
                    .messagebox-conversations-content{
                        height: calc(100% - 54px);
                        width: 100%;
                        overflow-y: auto;
                    }
                
                    .like-container-tabs{
                        width: 100%;
                        height: 60px;
                        cursor: pointer;
                        display: block;
                        position: relative;
                        overflow: hidden;
                        padding-left: 5px;
                    }
                
                    .like-container-tabs:hover{
                        background: rgba(245,245,245,0.5);
                    }

                    .like-container-tabs-header{
                        float: left;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .like-container-tabs-header-img{
                        float: left;
                        display: grid;
                        place-items: center;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .like-container-tabs-header-img img{
                        width: 45px;
                        height: 45px;
                        border-radius: 50%;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .like-container-tabs-header-text{
                        float: left;
                        padding-left: 10px;
                        text-align: left;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .like-container-tabs-header-text-username{
                        font-size: 14px;
                        font-weight: 500;
                        color: black;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                    .like-container-tabs-header-text-realname{
                        font-size: 14px;
                        font-weight: 500;
                        color: #8e8e8e;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }
                
                </style>
                <div class="like-base">
                <!--
                    <div class="like-searchbar">
                        <input oninput="queryUsers(this)" placeholder="Like"></input>
                    </div> 
                -->
                    <div class="like-container">

                    </div>
                </div>
                <script>
                    /*
                    function queryUsers(obj){
                        if(obj.value){
                            let strproc = obj.value.toLowerCase();
                            let regfill = strproc.replace(/[^abcdefghijklmnopqrstuvwxyz._0123456789]/ig,"");
                            obj.value = regfill;
                            $(".like-container").html("");
                            for (let i = 0 ; i < 100; i++){
                                $(".like-container").append(regfill+"</br>");
                        }
                        else{
                            $(".like-container").html("");
                            $(".like-container").append("empty");
                        }
                    }
                    */

                    function createLikeObject(JSONlikedata){

                        let requiredbaselike = 
                        \`
                        <div id="user_%/username/%" class="like-container-tabs" data-username="%/username/%" onclick="window.location.href='./'+$(this).data('username')">
                            <div class="like-container-tabs-header">
                                <div class="like-container-tabs-header-img">
                                    <img src="../miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
                                </div>
                                <div class="like-container-tabs-header-text">
                                    <div class="like-container-tabs-header-text-username">
                                        %/username/%
                                    </div>
                                    <div class="like-container-tabs-header-text-realname">
                                        %/realname/%
                                    </div>
                                </div>
                            </div>
                        </div>
                        \`;

                        //base data
                        let username = JSONlikedata.username;
                        let realname = JSONlikedata.realname;
                        let profpicextension = JSONlikedata.extension;

                        let likeBuildStr = requiredbaselike.replaceAll("%/username/%",username);
                        likeBuildStr = likeBuildStr.replaceAll("%/realname/%",realname);
                        likeBuildStr = likeBuildStr.replaceAll("%/profpicextension/%",profpicextension);

                        let jQueryDOMobj = $.parseHTML(likeBuildStr);

                        return jQueryDOMobj;
                    }

                    var bottomLikeID = 0;

                    function fetchLikes(){
                        $.ajax({
                            url: "../miscellaneous/phps/services/like.php",
                            data: {
                                requestmode: "fetch",
                                postid: `+postID+`
                            },
                            method: "POST",
                            success: (data)=>{
                                $(".like-container").html("");
                                bottomLikeID = data.bottomlikeid;
                                data.likes.forEach((likeJSON)=>{
                                    let likeDOMObj = createLikeObject(likeJSON);
                                    $(".like-container").append(likeDOMObj);
                                });
                            },
                            error: ()=>{}           
                        });
                    }

                    fetchLikes();

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
            <?php } else { ?>
                window.location.href = "../";
            <?php } ?>
        }

        function commentAction(obj){
            let postID = $(obj).data("postid");
            <?php if ($loggedIn) { ?>
                window.scrollTo(0,$("#post_"+postID+" .postbox-input-comment").offset().top);
            <?php } else { ?>
                window.location.href = "../";
            <?php } ?>
        }

    </script>
    <div class="content-container">
    <div id="post_<?= $postData->postid ?>" data-postid="<?= $postData->postid ?>" data-userid="<?= $postData->postusername ?>" data-liked="false" class="postbox-base">
            <div class="postbox-header">
                <div class="postbox-header-poster" data-userid="<?= $postData->postusername ?>" onclick="window.location.href='../'+$(this).data('userid')" >
                    <img src="../miscellaneous/assets/profiles/<?= $postData->postusername ?>.<?= $postData->profpicext ?>"></img>
                    <div class="postbox-header-poster-username"><a href='../<?= $postData->postusername ?>'><?= $postData->postusername ?></a></div>
                </div>
                <?php if ($accessUsername == $postData->postusername) { ?>
                    <div class="postbox-header-poster-delete" data-postid="<?= $postData->postid ?>" onclick="deletePost(this)" onlyifusername>
                            <?= file_get_contents("../miscellaneous/assets/trash_icon.svg") ?>
                    </div>
                <?php } ?>
            </div>
            <div class="postbox-image" data-postid="<?= $postData->postid ?>" ondblclick="likeDoubleTap(this)">
                <img class="postbox-image-hires" data-postid="<?= $postData->postid ?>" onload="replaceHiRes(this)" src="../miscellaneous/assets/posts/<?= $postData->postid ?>.<?= $postData->postextension ?>">
                <img class="postbox-image-lowres" src="../miscellaneous/assets/posts/<?= $postData->postid ?>_small.<?= $postData->postextension ?>">
                <div class="postbox-image-like">
                    <div class="spinner-border text-light" style="opacity:0.7;width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <?= file_get_contents("../miscellaneous/assets/like_icon_white.svg") ?>
                </div>
            </div>
            <div class="postbox-action">
                <div class="postbox-action-like" data-postid="<?= $postData->postid ?>" onclick="likeToggle(this)">
                    <div class="postbox-action-like-inactive">
                        <?= file_get_contents("../miscellaneous/assets/like_icon_nofill.svg") ?>
                    </div>
                    <div class="postbox-action-like-active">
                        <?= file_get_contents("../miscellaneous/assets/like_icon_red.svg") ?>  
                    </div>
                    <script>
                        <?php if ($loggedIn) { ?>
                            if(<?= $postData->likestatus ?> == 1){
                                $("#post_<?= $postData->postid ?> .postbox-action-like-inactive svg").css("transform","scale(0)");
                                $("#post_<?= $postData->postid ?> .postbox-action-like-active svg").css("transform","scale(1)");
                                $("#post_<?= $postData->postid ?>").data("liked",true);
                            }
                        <?php } ?>
                    </script>
                </div>
                <div class="postbox-action-comment" data-postid="<?= $postData->postid ?>" onclick="commentAction(this)">
                    <?= file_get_contents("../miscellaneous/assets/comment_icon.svg") ?>     
                </div>
            </div>
            <div class="postbox-likecounter" >
                <div class="postbox-likecounter-text" data-postid="<?= $postData->postid ?>" onclick="previewLike(this)">
                    <?php if ($postData->likecount == 0) {
                      echo "<b>Be the first one to like this</b>";
                    } elseif ($postData->likecount == 1) {
                      echo "<b>1</b> Like";
                    } else {
                      echo "<b>" . $postData->likecount . "</b> Likes";
                    } ?>
                </div>   
            </div>
            <div class="postbox-caption">
                <span class="postbox-caption-username" data-userid="<?= $postData->postusername ?>" onclick="window.location.href='../'+$(this).data('userid')" >
                    <a href='../<?= $postData->postusername ?>'><?= $postData->postusername ?> </a>
                </span>
                <span class="postbox-caption-text">
                    <?php echo str_replace("\n", "<br>", htmlspecialchars($postData->postcaption)); ?>
                </span>
            </div>
            <div class="postbox-uploadtime" title="">
            </div>
            <script>
               $("#post_<?= $postData->postid ?> .postbox-uploadtime").html(convertDateToDuration('<?= $postData->postdatetime ?>', false));
               $("#post_<?= $postData->postid ?> .postbox-uploadtime").attr('title',convertDateToString('<?= $postData->postdatetime ?>'));
            </script>
            <div class="postbox-comments">

                <?php foreach ($commentArray as $commentData) { ?>

                    <div class="postbox-comments-segment" id="comment_<?= $commentData->id ?>" data-commentid="<?= $commentData->id ?>" data-userid="<?= $commentData->username ?>">
                        <div class="postbox-comments-segment-img">
                                <img src="../miscellaneous/assets/profiles/<?= $commentData->username ?>.<?= $commentData->extension ?>"></img>
                        </div>
                        <div class="postbox-comments-segment-inner">
                            <span class="postbox-comments-segment-inner-username" data-userid="<?= $commentData->username ?>" onclick="window.location.href='../'+$(this).data('userid')" >
                                <a href='../<?= $commentData->username ?>'><?= $commentData->username ?></a>
                            </span>
                            <span class="postbox-comments-segment-inner-text">
                                <?php echo str_replace("\n", "<br>", htmlspecialchars($commentData->text)); ?>
                            </span>
                            <div class="postbox-comments-segment-inner-time"></div>
                            <script>
                               $("#comment_<?= $commentData->id ?> .postbox-comments-segment-inner-time").html(convertDateToDuration('<?= $commentData->time ?>'));
                               $("#comment_<?= $commentData->id ?> .postbox-comments-segment-inner-time").attr('title',convertDateToString('<?= $commentData->time ?>'));
                            </script>
                        </div>

                        <?php if ($accessUsername == $postData->postusername || $accessUsername == $commentData->username) { ?>
                            <div class="postbox-comments-segment-delete" data-postid="<?= $postData->postid ?>" data-commentid="<?= $commentData->id ?>" onclick="deleteComment(this)">
                                <?= file_get_contents("../miscellaneous/assets/trash_icon.svg") ?>
                            </div>
                        <?php } ?>
                    </div>
                        
                <?php $bottomCommentID = $commentData->id;} ?>
                
                <div class="postbox-input-comment" >
                <textarea required class="postbox-input-comment-textarea" id="input-comment-textarea" placeholder="<?php if ($loggedIn) {
                  echo 'Add your comment...';
                } else {
                  echo 'Login to add comment...';
                } ?>" maxlength="65535" oninput="checkCommentArea(this)" <?php if (!$loggedIn) {
  echo 'disabled onclick="window.location.href=\'../\'"';
} ?>></textarea>
                
                <?php if ($loggedIn) { ?>
                <button class="postbox-input-comment-postbutton" data-postid="<?= $postData->postid ?>" onclick="postComment(this)" disabled>
                    Post
                </button>
                <?php } ?>
            </div> 
                
            </div>
            
        </div>
    </div>
    <script>
        var bottomCommentID = <?= $bottomCommentID ?>;
    </script>
    </body>
</html>