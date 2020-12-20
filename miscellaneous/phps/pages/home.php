<?php
if (!isset($page)) {
  header("Location: ../");
  exit();
} ?>
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
        position: relative;
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
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
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
        padding-left: 12px;
        padding-top: 3px;
    }
    .postbox-comments-viewall{
        color: #8e8e8e;
        cursor: pointer;
    }

    .postbox-comments-preview{
        overflow-wrap: break-word;
    }
    .postbox-comments-preview-username{
        cursor: pointer;
        font-weight: 600;
    }
    .postbox-uploadtime{
        font-size: 11px;
        text-align: left;
        padding-left: 12px;
        padding-top: 4px;
        padding-bottom: 8px;
        color: #8e8e8e;
        cursor: default;
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
            margin-bottom: 0px;
        }

    }
    
    </style>
</head>
    <body>
    <?php require_once './miscellaneous/phps/addons/navbar.php'; ?>
    <script>

        
        //post actions
        
        function deletePost(obj){
            //delete post
            let postID = $(obj).data("postid");
            let imgSource = $("#post_"+postID+" .postbox-image-lowres").attr("src");
            let confirmed = false;
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
                                url:"./miscellaneous/phps/services/deletepost.php",
                                data:{
                                    postid: postID
                                },
                                method: "POST",
                                success:(data)=>{
                                    if(data.errorcode.deletestatus == 0){
                                        $("#post_"+postID).remove();
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
                    });
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
                    url: "./miscellaneous/phps/services/like.php",
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
                                    $("#post_"+postID).remove();
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
                    url: "./miscellaneous/phps/services/like.php",
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
                                    $("#post_"+postID).remove();
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

        function likeDoubleTap(obj) {
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
        }

        function likeToggle(obj) {
            let postID = $(obj).data("postid");
            let state = $("#post_"+postID).data("liked");
            $(obj).attr("onclick","");
            setTimeout(() => {
                $(obj).attr("onclick","likeToggle(this)");
            }, 250);
            likeRedAnimation(postID,state);
            likePushUpdate(postID,state);
        }

        function captionShowMore(obj) {
            let postID = $(obj).data("postid");
            $("#post_"+postID+" .postbox-caption-text-content-append").removeClass("d-none");
            $(obj).remove();
        }

        function previewLike(obj) {
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
                    height: 45px!important;
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
                                <img src="./miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
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
                        url: "./miscellaneous/phps/services/like.php",
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
        }

        function createPostObject(JSONpostdata){
            
            //templates
            let requiredbasePost = 
            `
            <div id="post_%/postid/%" data-postid="%/postid/%" data-userid="%/username/%" data-liked="%/liked/%" class="postbox-base">

                <div class="postbox-header">
                    <div class="postbox-header-poster" data-userid="%/username/%" onclick="window.location.href='./'+$(this).data('userid')" >
                        <img src="./miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
                        <div class="postbox-header-poster-username">
                            <a href='./%/username/%'>%/username/%</a>
                        </div>
                    </div>
                    %/deletebuttonarea/%
                </div>
                <div class="postbox-image" data-postid="%/postid/%" ondblclick="likeDoubleTap(this)">
                    <img class="postbox-image-hires" data-postid="%/postid/%" onload="replaceHiRes(this)" src="./miscellaneous/assets/posts/%/postid/%.%/postextension/%">
                    <img class="postbox-image-lowres" src="./miscellaneous/assets/posts/%/postid/%_small.%/postextension/%">
                    <div class="postbox-image-like">
                        <div class="spinner-border text-light" style="opacity:0.7;width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <?= file_get_contents("./miscellaneous/assets/like_icon_white.svg") ?>
                    </div>
                </div>
                <div class="postbox-action">
                    <div class="postbox-action-like" data-postid="%/postid/%" onclick="likeToggle(this)">
                        <div class="postbox-action-like-inactive">
                            <?= file_get_contents("./miscellaneous/assets/like_icon_nofill.svg") ?>  
                        </div>
                        <div class="postbox-action-like-active">
                            <?= file_get_contents("./miscellaneous/assets/like_icon_red.svg") ?>  
                        </div>
                    </div>
                    <div class="postbox-action-comment" data-postid="%/postid/%" onclick="window.location.href='./post/'+$(this).data('postid')">
                        <?= file_get_contents("./miscellaneous/assets/comment_icon.svg") ?>     
                    </div>
                </div>
                <div class="postbox-likecounter" >
                    <div class="postbox-likecounter-text" data-postid="%/postid/%" onclick="previewLike(this)">
                        %/likestring/%
                    </div>   
                </div>
                <div class="postbox-caption">
                    <span class="postbox-caption-username" data-userid="%/username/%" onclick="window.location.href='./'+$(this).data('userid')" >
                        <a href='./username'>%/username/%</a> 
                    </span>
                    %/captionarea/%
                </div>

                <div class="postbox-comments">
                    %/commentarea/%
                </div>

                <div class="postbox-uploadtime" title="">
                </div>

                <script>
                       $("#post_%/postid/% .postbox-uploadtime").html(convertDateToDuration('%/postdatetime/%',false));
                       $("#post_%/postid/% .postbox-uploadtime").attr('title',convertDateToString('%/postdatetime/%'));
                <\/script>
            </div>
            `;

            let optionalDeleteButton = 
            `
            <div class="postbox-header-poster-delete" data-postid="%/postid/%" onclick="deletePost(this)">
                <?= file_get_contents("./miscellaneous/assets/trash_icon.svg") ?>
            </div>
            `;

            let optionalCaptionArea =
            `
            <span class="postbox-caption-text">
                <span class="postbox-caption-text-intro">%/captionintro/%</span>
                %/captioncontentarea/%
            </span>
            `;

            let optionalCaptionContent = 
            `
            <span class="postbox-caption-text-content">
                <span class="postbox-caption-text-content-more" data-postid="%/postid/%" onclick="captionShowMore(this)">... more</span>
                <span class="postbox-caption-text-content-append d-none">%/captioncontent/%</span>
            </span>
            `;

            let optionalCommentArea =
            `
            <span class="postbox-comments-viewall" data-postid="%/postid/%" onclick="window.location.href='./post/'+$(this).data('postid')" optional>View all %/commentcount/% comments</span> 
            <br>
            `;

            let optionalCommentPreviewArea =
            `
            <span class="postbox-comments-preview" >
                <span class="postbox-comments-preview-username" data-userid="%/commentusername/%" onclick="window.location.href='./'+$(this).data('userid')" ><a href='./%/commentusername/%'>%/commentusername/%</a></span>
                <span class="postbox-comments-preview-text">%/commenttext/%</span>
            </span>
            <br>
            `

            //base data
            let postID = JSONpostdata.postid;
            let postPosterUsername = JSONpostdata.postusername;
            let postDateTime = JSONpostdata.postdatetime;
            let postCaptionText = JSONpostdata.postcaption;
            let postExtension = JSONpostdata.postextension;
            let postDeletePerms = JSONpostdata.deleteperms;
            let postLikeStatus = JSONpostdata.likestatus;
            let postLikeCount = JSONpostdata.likecount;
            let postCommentCount = JSONpostdata.commentcount;
            let profPicExtension = JSONpostdata.profpicext;

            //comments data
            let commentArray = JSONpostdata.postcomments;

            //process dari yang optional ke base

            //jadi satu

            //apply params pake string replace

            //bagian comment
            let commentarea = "";
            if(postCommentCount > 0){
                if(postCommentCount > 2){
                    commentarea = optionalCommentArea.replaceAll("%/commentcount/%", postCommentCount.toString());
                }
                commentArray.forEach((comment)=>{
                    let commentSectionCompose = optionalCommentPreviewArea.replaceAll("%/commentusername/%",comment.username);
                    commentSectionCompose = commentSectionCompose.replaceAll("%/commenttext/%",comment.text.replaceAll("\n","<br>"));
                    commentarea += commentSectionCompose;
                });
            }

            //bagian caption
            let captionarea = "";
            if(postCaptionText.length > 0){ 
                let captionStrLastCount = 0;
                if(postCaptionText.length <= 20){
                    captionStrLastCount = postCaptionText.length;
                }else{
                    captionStrLastCount = 21;
                    for (let i = 0; i < postCaptionText.length; i++) {
                        if(postCaptionText[i] === "\n"){
                            captionStrLastCount = i;
                            break;
                        }
                        if(postCaptionText[i] === " " && i <= 20)
                            captionStrLastCount = i;
                    }
                }
                let captionSubStr = postCaptionText.substring(0,captionStrLastCount);
                let captionOtherStr = postCaptionText.substring(captionStrLastCount);
                captionOtherStr = captionOtherStr.replaceAll("\n","<br>");
                captionarea = optionalCaptionArea.replaceAll("%/captionintro/%", captionSubStr);
                let captionContentArea = "";
                if(postCaptionText.length - captionStrLastCount > 0){
                    captionContentArea = optionalCaptionContent.replaceAll("%/captioncontent/%", captionOtherStr);
                }
                captionarea = captionarea.replaceAll("%/captioncontentarea/%", captionContentArea);
            }


            //bagian delete
            let deletebuttonarea = "";
            if(postDeletePerms == 1){
                deletebuttonarea = optionalDeleteButton;
            }

            //bagian like
            let likestring = "";
            if(postLikeCount == 0){
                likestring = "<b>Be the first one to like this</b>";
            }else if(postLikeCount == 1){
                likestring = "<b>1</b> Like";
            }else{
                likestring = "<b>"+postLikeCount+"</b> Likes";
            }

            //bagian likestatus
            let liked = "false";
            let styleactive = 'style="transform: scale(0);"';
            let styleinactive = 'style="transform: scale(1);"';
            if(postLikeStatus == 1){
                liked = "true";
                styleactive = 'style="transform: scale(1);"';
                styleinactive = 'style="transform: scale(0);"';
            }

            //bagian userid
            let username = postPosterUsername;
            
            //building
            let postBuildStr = requiredbasePost.replaceAll("%/commentarea/%",commentarea);
            postBuildStr = postBuildStr.replaceAll("%/captionarea/%",captionarea);
            postBuildStr = postBuildStr.replaceAll("%/deletebuttonarea/%",deletebuttonarea);
            postBuildStr = postBuildStr.replaceAll("%/postdatetime/%",postDateTime);
            postBuildStr = postBuildStr.replaceAll("%/likestring/%",likestring);
            postBuildStr = postBuildStr.replaceAll("%/liked/%",liked);
            postBuildStr = postBuildStr.replaceAll("%/styleactive/%",styleactive);
            postBuildStr = postBuildStr.replaceAll("%/styleinactive/%",styleinactive);
            postBuildStr = postBuildStr.replaceAll("%/postextension/%",postExtension);
            postBuildStr = postBuildStr.replaceAll("%/profpicextension/%",profPicExtension);
            postBuildStr = postBuildStr.replaceAll("%/username/%",username);
            postBuildStr = postBuildStr.replaceAll("%/postid/%",postID);
            let jQueryDOMobj = $.parseHTML(postBuildStr, document, true);

            return jQueryDOMobj;
        }

        var topPostID = 0;
        var bottomPostID = 0;

        function refreshPostFetch(){
            //fetch ajax
            $.ajax({
                url: "./miscellaneous/phps/services/fetchpost.php",
                data: {
                    requestmode:"simple"
                },
                method: "POST",
                success: (data)=>{
                    if(data.count > 0){
                        $(".content-container").html("");
                        topPostID = data.toppostid;
                        bottomPostID = data.bottompostid;
                        data.posts.forEach((postJSON)=>{
                            let postDOMObj = createPostObject(postJSON);
                            $(".content-container").append(postDOMObj);
                        });
                    }else{}
                },
                error: ()=>{}           
            });
        }

        function fetchNewPostFetch(){
            //fetch ajax
            $.ajax({
                url: "./miscellaneous/phps/services/fetchpost.php",
                data: {
                    requestmode:"newer",
                    topid: topPostID
                },
                method: "POST",
                success: (data)=>{
                    if(data.count > 0){
                        topPostID = data.toppostid;
                        data.posts.forEach((postJSON)=>{
                            let postDOMObj = createPostObject(postJSON);
                            $(".content-container").prepend(postDOMObj);
                        });
                    }else{}
                },
                error: ()=>{}           
            });
        }


    </script>
    <div class="content-container">

    </div>
    <script>
        window.onload = () => {
            refreshPostFetch();
        };
    </script>
    </body>
</html>