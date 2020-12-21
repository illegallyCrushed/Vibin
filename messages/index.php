<?php
require_once '../miscellaneous/phps/services/connect.php';
$page = "messages";

if (!isset($_SESSION['username'])) {
  //kalo blm login
  header("Location: ../"); //suruh login
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once '../miscellaneous/phps/addons/libraries.php'; ?>
    <?php require_once '../miscellaneous/phps/addons/metatags.php'; ?>
    
    <title>Vibin</title>
    <style>

    :root{
        --offsetfulldisp: 0;
    }

    body{
        height: 100vh;
        overflow: hidden;
    }
    
    .content-container{
        position: relative;
        left: 50%;
        transform: translate(-50%,0);
        padding-top: 80px;
        padding-bottom: 30px;
        display: block;
        width: 100%;
        height: 100%;
        max-width: 975px;
        color: black;
        font-size:20pt;
        text-align:center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
        overflow-y: hidden;
        position: relative;
        scroll-behavior: auto!important;
    }

    .messagebox-base{
        background: white;
        border-style: solid;
        border-color: #dbdbdb;
        border-width: 1px;
        border-radius: 8px;
        width: 100%;
        height: 100%;
        display: grid;
        grid-template-columns: minmax(300px, 25%) 1fr;
        overflow-y: hidden;
        position: relative;
    }

    .messagebox-conversations{
        border-style: solid;
        border-color: #dbdbdb;
        border-right-width: 1px;
        overflow-y: hidden;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .messagebox-conversations-navbar{
        height: 54px;
        width: 100%;
        border-style: solid;
        border-color: #dbdbdb;
        border-bottom-width: 1px;
        display: grid;
        grid-template-columns: 50px 1fr 50px;
    }

    .messagebox-conversations-navbar-home{
        height: 100%;
        display: grid;
        place-items: center;
    }

    .messagebox-conversations-navbar-home-svg{
        height: 25px;
        display: grid;
        place-items: center;
    }

    .messagebox-conversations-navbar-home-svg svg{
        height: 25px;
        cursor: pointer;
    }

    .messagebox-conversations-navbar-home-svg .active{
        height: 25px;
        position: absolute;
        opacity: 0;
        transition: opacity 0.75s;
    }

    .messagebox-conversations-navbar-title{
        height: 100%;
        font-size: 19px;
        font-weight: 600;
        display: grid;
        place-items: center;
    }

    .messagebox-conversations-navbar-newconv{
        height: 100%;
        display: grid;
        place-items: center;
    }

    .messagebox-conversations-navbar-newconv-svg{
        height:25px;
        display: grid;
        place-items: center;
        cursor: pointer;
    }

    .messagebox-conversations-navbar-newconv-svg svg{
        height:25px;
    }

    .messagebox-conversations-navbar-newconv-svg .active{
        height: 25px;
        position: relative;
        top: -25px;
        opacity: 0;
        transition: opacity 0.75s;
    }

    .messagebox-conversations-content{
        height: calc(100% - 54px);
        width: 100%;
        overflow-y: auto;

    }

    .messagebox-conversations-content-tabs{
        width: 100%;
        height: 80px;
        cursor: pointer;
        display: block;
        padding-left: 25px;
        position: relative;
        overflow: hidden;
    }

    .messagebox-conversations-content-tabs:hover{
        background: rgba(245,245,245,0.5);
    }
    
    .messagebox-conversations-content-tabs-header{
        float: left;
        height: 100%;
        display: flex;
        align-items: center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .messagebox-conversations-content-tabs-header-img{
        float: left;
        display: grid;
        place-items: center;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .messagebox-conversations-content-tabs-header-img img{
        width: 60px;
        height: 60px;
        border-radius: 50%;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .messagebox-conversations-content-tabs-header-text{
        float: left;
        padding-left: 10px;
        text-align: left;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .messagebox-conversations-content-tabs-header-text-username{
        float: left;
        font-size: 13px;
        color: black;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
    }

    .messagebox-conversations-content-tabs-header-text-preview{
        font-size: 13px;
        color: #8e8e8e;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
        clear: both;
    }
    
    .messagebox-conversations-content-tabs-header-text-preview-content{
        display: inline-block;
        float: left;
        max-width: 150px;
        overflow: hidden;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
        max-height: 1.3rem;
    }

    .messagebox-conversations-content-tabs-header-text-preview-dot{
        float: left;
    }

    .messagebox-conversations-content-tabs-header-text-preview-time{
        float: left;
    }

    .messagebox-conversations-content-tabs-symbol{
        width: 20px;
        height: 7px;
        border-radius: 7px;
        background: #0095f6;
        float: left;
        margin-left: 10px;
        display: none;
        transform: translate(0,5.5px);
    }

    .notread .messagebox-conversations-content-tabs-header-text-username , 
    .notread .messagebox-conversations-content-tabs-header-text-preview-content , 
    .notread .messagebox-conversations-content-tabs-header-text-preview-time ,
    .notread .messagebox-conversations-content-tabs-symbol{
        font-weight: 900;
        display: block;
        color: black;
    }

    .messagebox-messages{
        border-style: solid;
        border-color: #dbdbdb;
        border-left-width: 1px;
        overflow-y: hidden;
        position: relative;
        display: grid;
        grid-template-rows: 54px 1fr 50px calc(var(--offsetfulldisp) * 1px);
        height: 100%;
    }

    .messagebox-messages-navbar{
        width: 100%;
        border-style: solid;
        border-color: #dbdbdb;
        border-bottom-width: 1px;
        padding-left: 12px;
        position: relative;
    }

    .messagebox-messages-navbar-back{
        float: left;
        height: 100%;
        width: 30px;
        display: grid;
        place-items: center;
        margin-right: 12px;
    }

    .messagebox-messages-navbar-back svg{
        height: 20px;
        cursor: pointer;
    }

    .messagebox-messages-navbar-header{
        float: left;
        height: 100%;
    }

    .messagebox-messages-navbar-header-img{
        width: 34px;
        height: 100%;
        margin-right: 10px;
        display: grid;
        place-items: center;
        float: left;

    }

    .messagebox-messages-navbar-header-img img{
        width: 34px;
        height: 34px;
        border-radius: 50%;
    }

    .messagebox-messages-navbar-header-username{
        float: left;
        color: black;
        font-size: 14px;
        font-weight: 600;
        height: 100%;
        display: grid;
        place-items: center;
    }

    .messagebox-messages-content{
        width: 100%;
        overflow-y: auto;
        padding: 25px 0;
        transition-duration: 0.4s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
        transition-property: all;
        scroll-behavior: auto;
    }

    .messagebox-messages-content-you{
        width: 100%;
        text-align: right;
        display: inline-block;
    }

    .messagebox-messages-content-you-bubble {
        float: right;
	    margin: 0 30px;
        margin-bottom: 10px;
        margin-left: 6px;
        display: inline-block;
        position: relative;
	    max-width: 65%;
	    height: auto;
	    background-color: #efefef;
        border-radius: 10px;
    }

    .messagebox-messages-content-you-bubble-text{
        font-size: 14px;
        padding: 11px;
        text-align: left;
    }

    .messagebox-messages-content-you-bubble:after{
    	content: ' ';
    	position: absolute;
    	width: 0;
    	height: 0;
        left: auto;
    	right: -20px;
        top: 0px;
    	bottom: auto;
    	border: 20px solid;
    	border-color: #efefef transparent transparent transparent;
    }

    .messagebox-messages-content-you-detail{
        float: right;
        height: 100%;
    }

    .messagebox-messages-content-you-detail-time{
        font-size: 12px;
        color: #8e8e8e;
    }

    .messagebox-messages-content-you-detail-read{
        font-size: 12px;
        color: #8e8e8e;
    }

    .messagebox-messages-content-other{
        width: 100%;
        text-align: left;
        display: inline-block;
    }

    .messagebox-messages-content-other-bubble {
        float: left;
	    margin: 0 30px;
        margin-bottom: 10px;
        margin-right: 5px;
        position: relative;
	    max-width: 65%;
	    height: auto;
	    background-color: #efefef;
        border-radius: 10px;
    }

    .messagebox-messages-content-other-bubble-text{
        font-size: 14px;
        padding: 11px;
        text-align: left;
    }

    .messagebox-messages-content-other-bubble:after{
    	content: ' ';
    	position: absolute;
    	width: 0;
    	height: 0;
        left: -20px;
    	right: auto;
        top: 0px;
    	bottom: auto;
    	border: 22px solid;
    	border-color: #efefef transparent transparent transparent;
    }

    .messagebox-messages-content-other-detail{
        float: left;
        height: 100%;
    }

    .messagebox-messages-content-other-detail-time{
        font-size: 12px;
        color: #8e8e8e;
    }

    .messagebox-messages-content-date{
        font-size: 13px;
        color: #8e8e8e;
        height: 38px;
        width: 100%;
    }

    .messagebox-messages-input{
        width: 100%;
        position: relative;
        display: flex;
        justify-content: center;
        border-style: solid;
        border-color: #dbdbdb;
        border-top-width: 1px;
        overflow: hidden;
    }

    .messagebox-messages-input-textarea{
        width: 100%;
        min-height: 26px;
        max-height: 162px;
        height: 26px;
        font-size: 14px;
        font-weight: 400;
        margin: 20px;
        margin-top: 14px;
        margin-bottom: 10px;
        outline: 0px;
        resize: none;
    }

    .messagebox-messages-input-sendbutton{
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

    .messagebox-messages-input-sendbutton:disabled{
        opacity: 0.3;
        cursor: not-allowed;
    }

    @media (max-width: 975px) {
        .content-container{
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            transform: translate(0,0);
        }
        .content-container{
            padding-bottom: 0px;
            padding-top: 54px;
        }

        .messagebox-base{
            border-width: 0;
            border-radius: 0;
        }
    }

    @media (max-width: 576px) {

        .content-container{
            padding-top: 0px;
            padding-bottom: 0px;
            overflow: hidden;
        }

        .messagebox-base{
            background: white;
            border-width: 0;
            border-radius: 0;
            display: block;
            width: 200vw;
            scroll-behavior: auto!important;
        }

        .messagebox-conversations{
            width: 100vw;
            height: 100%;
            float:left;
            border-right-width: 0px;
        }

        .messagebox-conversations-content-tabs{
            height: 100px;
            padding-left: 20px;
        }

        .messagebox-conversations-content-tabs-header-text-preview-content{
            max-width: calc(100vw - 145px);
        }

        .messagebox-conversations-content-tabs-header-img img{
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .messagebox-conversations-content-tabs-header-text-username{
            font-size: 15px;
        }

        .messagebox-conversations-content-tabs-header-text-preview{
            font-size: 15px;
        }

        .messagebox-conversations-content-tabs-symbol{
            transform: translate(0, 7.5px);
        }

        .messagebox-messages{
            width: 100vw;
            height: 100%;
            float:left;
            border-left-width: 0px;
        }
    }


    


    </style>
</head>
    <body>
        <?php require_once '../miscellaneous/phps/addons/navbar.php'; ?>
        <div class="content-container">
            <div class="messagebox-base">
                <div class="messagebox-conversations">
                    <div class="messagebox-conversations-navbar">
                        <div class="messagebox-conversations-navbar-home">
                            <div class="messagebox-conversations-navbar-home-svg d-grid d-sm-none" onclick="homeMessageAction()">
                                <?= file_get_contents("../miscellaneous/assets/home_icon_nofill.svg") ?>
                                <?= file_get_contents("../miscellaneous/assets/home_icon.svg") ?>
                            </div>
                        </div>
                        <div class="messagebox-conversations-navbar-title">Conversations</div>
                        <div class="messagebox-conversations-navbar-newconv" onclick="newConversation(this)">
                            <div class="messagebox-conversations-navbar-newconv-svg">
                                <?= file_get_contents("../miscellaneous/assets/newconv_icon_nofill.svg") ?>
                                <?= file_get_contents("../miscellaneous/assets/newconv_icon.svg") ?>
                            </div>
                        </div>
                    </div>
                    <div class="messagebox-conversations-content">

                        

                    </div> 
                </div>
                <div class="messagebox-messages d-none">
                    <div class="messagebox-messages-navbar">
                        <div class="messagebox-messages-navbar-back d-grid d-sm-none" onclick="closeConversation()">
                            <?= file_get_contents("../miscellaneous/assets/back_icon.svg") ?>
                        </div>
                        <div class="messagebox-messages-navbar-header">
                            <div class="messagebox-messages-navbar-header-img">
                                <img src=""></img>
                            </div>
                            <div class="messagebox-messages-navbar-header-username">
                                <a href=''></a>
                            </div>
                        </div>
                    </div>
                    <div class="messagebox-messages-content">

                        

                    </div>
                    <div class="messagebox-messages-input">
                        <textarea required class="messagebox-messages-input-textarea" placeholder="Message..." maxlength="65535" onclick="resizeAdaptive()" oninput="checkMessageArea(this)"></textarea>
                        <button class="messagebox-messages-input-sendbutton" data-username="" data-convid="" onclick="postMessage(this)" disabled>
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>

            let openedConversationID;
            let mobileStatus = false;
            let requestUsername = "";
            let requestExtension = "";
            <?php if (isset($_GET['username']) && isset($_GET['extension'])) { ?>
                requestUsername = "<?= $_GET['username'] ?>";
                requestExtension = "<?= $_GET['extension'] ?>";
                sanitizeURL();
                fetchConversation(false,"",true,()=>{createNewConversation(requestUsername);});
            <?php } else { ?>
                fetchConversation();
            <?php } ?>

            function homeMessageAction() {
                $(".messagebox-conversations-navbar-home-svg .active").css("opacity",1);
                window.location.href="../";
            }

            function closeConversation(){
                if(openedConversationID == "temp"){
                    $("#conv_temp").remove();
                }
                mobileStatus = false;
                $(".content-container").animate({
                    scrollLeft: 0
                }, 250);
                setTimeout(() => {
                    $(".messagebox-messages").addClass("d-none");
                }, 300);
            }

            var jConfirmNewConvo;
            function newConversation() { //newconv navbar
               $(".messagebox-conversations-navbar-newconv-svg .active").css("opacity",1);
               jConfirmNewConvo = $.dialog({
                    title: 'New Conversation',
                    columnClass: 'appwidthnorm jqueryconf-pop-setwidth col',
                    content:
                    `
                    <style>
                        .newconv-base{
                            width: 100%;
                            height: 100%;
                            overflow: hidden;
                            position: relative;
                        }
                    
                        .newconv-searchbar{
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
                    
                        .newconv-searchbar input{
                            font-size: 14px;
                            width: 97%;
                            margin: 0 1.5%;
                            outline: 0px;
                        }
                    
                        .newconv-container{
                            width: 100%;
                            height: auto;
                            overflow: auto;
                        }
                        .messagebox-conversations-content{
                            height: calc(100% - 54px);
                            width: 100%;
                            overflow-y: auto;
                        }
                    
                        .newconv-container-tabs{
                            width: 100%;
                            height: 60px;
                            display: block;
                            position: relative;
                            overflow: hidden;
                            padding-left: 5px;
                            padding-right: 5px;
                        }
                    
                        .newconv-container-tabs:hover{
                            background: rgba(245,245,245,0.5);
                        }
                    
                        .newconv-container-tabs-header{
                            float: left;
                            height: 100%;
                            display: flex;
                            align-items: center;
                            transition-duration: 0.4s;
                            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                            transition-property: all;
                            cursor: pointer;
                        }
                    
                        .newconv-container-tabs-header-img{
                            float: left;
                            display: grid;
                            place-items: center;
                            transition-duration: 0.4s;
                            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                            transition-property: all;
                        }
                    
                        .newconv-container-tabs-header-img img{
                            width: 45px;
                            height: 45px!important;
                            border-radius: 50%;
                            transition-duration: 0.4s;
                            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                            transition-property: all;
                        }
                    
                        .newconv-container-tabs-header-text{
                            float: left;
                            padding-left: 10px;
                            text-align: left;
                            transition-duration: 0.4s;
                            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                            transition-property: all;
                        }
                    
                        .newconv-container-tabs-header-text-username{
                            font-size: 14px;
                            font-weight: 500;
                            color: black;
                            transition-duration: 0.4s;
                            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                            transition-property: all;
                        }
                    
                        .newconv-container-tabs-header-text-realname{
                            font-size: 14px;
                            font-weight: 500;
                            color: #8e8e8e;
                            transition-duration: 0.4s;
                            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                            transition-property: all;
                        }

                        .newconv-container-tabs-header-start{
                            height: 25px;
                            padding: 0 12px;
                            border-radius: 5px;
                            font-size: 14px;
                            font-weight: 500;
                            color: white;
                            background: #0095ff;
                            display: grid;
                            place-items: center;
                            position: absolute;
                            right: 5px;
                            cursor: pointer;
                        }
                    
                    </style>
                    <div class="newconv-base">
                        <div class="newconv-searchbar">
                            <input onkeyup="queryUsers(this)" placeholder="Search"></input>
                        </div>
                        <div class="newconv-container">
                        
                        </div>
                    </div>
                    <script>


                    function createNewconvObject(JSONnewconvdata){

                        let requiredbasenewconv = 
                        \`
                        <div id="user_%/username/%" class="newconv-container-tabs" data-extension="%/profpicextension/%" data-username="%/username/%" onclick="createNewConversation($(this).data('username'))">
                            <div class="newconv-container-tabs-header">
                                <div class="newconv-container-tabs-header-img">
                                    <img src="../miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
                                </div>
                                <div class="newconv-container-tabs-header-text">
                                    <div class="newconv-container-tabs-header-text-username">
                                        %/username/%
                                    </div>
                                    <div class="newconv-container-tabs-header-text-realname">
                                        %/realname/%
                                    </div>
                                </div>
                                <div class="newconv-container-tabs-header-start" data-username="%/username/%" onclick="createNewConversation($(this).data('username'))">
                                    Message
                                </div>
                            </div>
                        </div>
                        \`;

                        //base data
                        let username = JSONnewconvdata.username;
                        let realname = JSONnewconvdata.realname;
                        let profpicextension = JSONnewconvdata.extension;

                        let newconvBuildStr = requiredbasenewconv.replaceAll("%/username/%",username);
                        newconvBuildStr = newconvBuildStr.replaceAll("%/realname/%",realname);
                        newconvBuildStr = newconvBuildStr.replaceAll("%/profpicextension/%",profpicextension);

                        let jQueryDOMobj = $.parseHTML(newconvBuildStr);

                        return jQueryDOMobj;
                    }



                    function queryUsers(obj){
                        
                        $(".newconv-container").html("");
                        
                        if(obj.value && obj.value.trim().length > 0){
                            $(obj).attr("onkeyup","");
                            $.ajax({
                                url: "../miscellaneous/phps/services/search.php",
                                data: {
                                    requestmode: "fetch",
                                    searchquery: obj.value.trim(),
                                    exclude: "true"
                                },
                                method: "POST",
                                success: (data)=>{
                                    data.searches.forEach((newconv)=>{
                                        let newconvDOMObj = createNewconvObject(newconv);
                                        $(".newconv-container").append(newconvDOMObj);
                                    });
                                },
                                error: ()=>{},
                                complete: ()=>{
                                    $(obj).attr("onkeyup","queryUsers(this)");
                                }
                            });
                        }

                    }

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
                        $(".messagebox-conversations-navbar-newconv-svg .active").css("opacity",0);
                    }
                });
            }

            function checkMessageArea(obj){
                obj.style.height = 0;
                obj.style.height = obj.scrollHeight + 'px';
                if(parseInt(obj.style.height) < 150 )
                    $(".messagebox-messages").css("grid-template-rows","54px 1fr "+(24+parseInt(obj.style.height))+"px calc(var(--offsetfulldisp) * 1px)");
                else if(parseInt(obj.style.height) >= 150)
                    $(".messagebox-messages").css("grid-template-rows","54px 1fr "+(186)+"px calc(var(--offsetfulldisp) * 1px)");
                
                if($(obj).val().length > 0)
                    $(obj).next().removeAttr("disabled");
                else
                    $(obj).next().attr("disabled","");

                $(".messagebox-messages-content").scrollTop($(".messagebox-messages-content")[0].scrollHeight);
                resizeAdaptive();
            }

            function resizeAdaptive(){
                $(".content-container").css("height",$("body").css("height"));
                document.querySelector(":root").style.setProperty('--offsetfulldisp', (parseInt($("body").css("height"))-window.innerHeight));
            }

            function createConversationObject(JSONconvdata){

                let requiredbaseconv = 
                `
                <div id="conv_%/convid/%" class="messagebox-conversations-content-tabs %/notread/%" data-extension="%/profpicextension/%" data-convid="%/convid/%" data-username="%/username/%" onclick="accessConversation(this)">
                    <div class="messagebox-conversations-content-tabs-header">
                        <div class="messagebox-conversations-content-tabs-header-img">
                            <img src="../miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
                        </div>
                        <div class="messagebox-conversations-content-tabs-header-text">
                            <div class="messagebox-conversations-content-tabs-header-text-username">
                                %/username/%
                            </div>
                            <div class="messagebox-conversations-content-tabs-symbol">
                            </div>
                            <div class="messagebox-conversations-content-tabs-header-text-preview">
                                <div class="messagebox-conversations-content-tabs-header-text-preview-content">
                                    %/lastmessage/%
                                </div>
                                <div class="messagebox-conversations-content-tabs-header-text-preview-dot">
                                    ...&nbsp;
                                </div>
                                <div class="messagebox-conversations-content-tabs-header-text-preview-time" title="%/datetimestr/%">
                                    %/datetime/%
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                `;

                let convid = JSONconvdata.id;
                let username = JSONconvdata.username;
                let profpicextension = JSONconvdata.extension;
                let lastmessage = JSONconvdata.lastmessage;

                let notread = "";
                if(JSONconvdata.status == 0){
                    notread = "notread";
                }

                let datetime = "";
                let datetimestr = "";
                if(JSONconvdata.time != ""){
                    datetime = convertDateToDuration(JSONconvdata.time);
                    datetimestr = convertDateToString(JSONconvdata.time);
                }

                let convBuildStr = requiredbaseconv.replaceAll("%/convid/%", convid);
                    convBuildStr = convBuildStr.replaceAll("%/username/%", username);
                    convBuildStr = convBuildStr.replaceAll("%/profpicextension/%", profpicextension);
                    convBuildStr = convBuildStr.replaceAll("%/lastmessage/%", lastmessage);
                    convBuildStr = convBuildStr.replaceAll("%/notread/%", notread);
                    convBuildStr = convBuildStr.replaceAll("%/datetime/%", datetime);
                    convBuildStr = convBuildStr.replaceAll("%/datetimestr/%", datetimestr);

                let jQueryDOMobj = $.parseHTML(convBuildStr);

                return jQueryDOMobj;
            }

            function createMessageObject(JSONmessagedata){
                let requiredbasemessageother = 
                `
                <div class="messagebox-messages-content-other" id="message_%/messageid/%">
                    <div class="messagebox-messages-content-other-bubble">
                        <div class="messagebox-messages-content-other-bubble-text">
                            %/textarea/%
                        </div>
                    </div>
                    <div class="messagebox-messages-content-other-detail">
                        <div class="messagebox-messages-content-other-detail-time">
                            %/timestr/%
                        </div>
                    </div>
                </div>
                `;

                let requiredbasemessageyou = 
                ` 
                <div class="messagebox-messages-content-you" id="message_%/messageid/%">
                    <div class="messagebox-messages-content-you-bubble">
                        <div class="messagebox-messages-content-you-bubble-text">
                            %/textarea/%
                        </div>
                    </div>
                    <div class="messagebox-messages-content-you-detail">
                        <div class="messagebox-messages-content-you-detail-time">
                            %/timestr/%
                        </div>
                        <div class="messagebox-messages-content-you-detail-read">
                            %/readstatus/%
                        </div>
                    </div>
                </div>
                `;

                let messageid = JSONmessagedata.id;
                let textarea = JSONmessagedata.text.replaceAll("\n","<br>");
                let timestr = new Date(Date.parse(JSONmessagedata.time.replace(/[-]/g,'/')));
                timestr = (timestr.getHours() < 10? "0" : "") + timestr.getHours().toString() + ":" + (timestr.getMinutes() < 10? "0" : "") + timestr.getMinutes().toString();
                let readstatus = "";
                if(JSONmessagedata.status == 1){
                    readstatus="Read";
                }
                let messageBuildStr = requiredbasemessageother;
                if(JSONmessagedata.type == "you"){
                    messageBuildStr = requiredbasemessageyou.replaceAll("%/readstatus/%", readstatus);
                }
                messageBuildStr = messageBuildStr.replaceAll("%/messageid/%", messageid);
                messageBuildStr = messageBuildStr.replaceAll("%/textarea/%", textarea);
                messageBuildStr = messageBuildStr.replaceAll("%/timestr/%", timestr);

                let jQueryDOMobj = $.parseHTML(messageBuildStr);
                return jQueryDOMobj;
            }

            function createNewConversation(userid){
                if(jConfirmNewConvo){
                    requestExtension = $("#user_"+userid).data("extension");
                    jConfirmNewConvo.close();
                    jConfirmNewConvo = undefined;
                }

                $.ajax({
                    url: "../miscellaneous/phps/services/message.php",
                    data: {
                        requestmode: "check",
                        username: userid
                    },
                    method: "POST",
                    success: (data)=>{
                        if(data.available == 1){
                            //kalo nemu lama
                            fetchConversation(true, data.convid);
                        }else{
                            //kalo baru
                            let newConvJSON = {
                            id: "temp",
                            username: userid,
                            extension: requestExtension,
                            lastmessage: "",
                            status: 1,
                            time: ""
                            }
                            $("#conv_temp").remove();
                            let convDOMObj = createConversationObject(newConvJSON);
                            $(".messagebox-messages-content").html("");
                            $(".messagebox-conversations-content").prepend(convDOMObj);
                            accessConversation($("#conv_temp")[0]);
                        }
                    },
                    error: ()=>{}
                });
                    
            }

            function fetchConversation(open = false, convid ="", callback = false , func = ()=>{}) {
                $.ajax({
                    url: "../miscellaneous/phps/services/message.php",
                    data: {
                        requestmode: "fetchconv"
                    },
                    method: "POST",
                    success: (data)=>{
                        $(".messagebox-conversations-content").html("");
                        if(data.count > 0){
                            data.convs.forEach((conv)=>{
                                let convDOMObj = createConversationObject(conv);
                                $(".messagebox-conversations-content").append(convDOMObj);
                            });
                            if(open){
                                $("#conv_"+convid).trigger("click");
                            }
                            if(callback){
                                func();
                            }
                        }
                    },
                    error: ()=>{}
                });
            }


            let bottomMessageID = 0;
            var lastMessageDate = "";
            var lastMessageDateStr = "";


            function fetchMessage(obj) {
                if($(obj).data("convid") == "temp"){
                    return false;
                }
                $(".messagebox-messages-content").html("");
                $.ajax({
                    url: "../miscellaneous/phps/services/message.php",
                    data: {
                        requestmode: "fetchmessage",
                        convid: $(obj).data("convid")
                    },
                    method: "POST",
                    success: (data)=>{
                        if(data.count > 0){
                            lastMessageDate = data.messages[0].time.split(" ")[0];
                            lastMessageDateStr = data.messages[0].time;
                            data.messages.forEach((message)=>{
                                if(lastMessageDate != message.time.split(" ")[0]){
                                    let timesepDOM = $.parseHTML('<div class="messagebox-messages-content-date">' + convertDateToString(lastMessageDateStr, true, true) + '</div>');
                                    lastMessageDate = message.time.split(" ")[0];
                                    lastMessageDateStr = message.time;
                                    $(".messagebox-messages-content").prepend(timesepDOM);
                                }
                                let messageDOMObj = createMessageObject(message);
                                $(".messagebox-messages-content").prepend(messageDOMObj);
                            });
                            let timesepDOM = $.parseHTML('<div class="messagebox-messages-content-date">' + convertDateToString(lastMessageDateStr, true, true) + '</div>');
                            $(".messagebox-messages-content").prepend(timesepDOM);
                            bottomMessageID = data.bottommessageid;
                            $(".messagebox-messages-content").scrollTop($("#message_"+bottomMessageID).offset().top);
                        }
                    },
                    error: ()=>{}
                });
                //
            }

            function refreshMessage(convoid) {
                //append message baru
                if(convoid == "temp" || convoid == undefined){
                    return false;
                }
                $.ajax({
                    url: "../miscellaneous/phps/services/message.php",
                    data: {
                        requestmode: "refresh",
                        convid: convoid,
                        bottommessageid: bottomMessageID
                    },
                    method: "POST",
                    success: (data)=>{
                        if(data.count > 0){
                            lastMessageDate = data.messages[0].time.split(" ")[0];
                            lastMessageDateStr = data.messages[0].time;
                            data.messages.forEach((message)=>{
                                if(lastMessageDate != message.time.split(" ")[0]){
                                    lastMessageDate = message.time.split(" ")[0];
                                    lastMessageDateStr = message.time;
                                    let timesepDOM = $.parseHTML('<div class="messagebox-messages-content-date">' + convertDateToString(lastMessageDateStr) + '</div>');
                                    $(".messagebox-messages-content").append(timesepDOM);
                                }
                                let messageDOMObj = createMessageObject(message);
                                $(".messagebox-messages-content").append(messageDOMObj);
                            });
                            bottomMessageID = data.bottommessageid;
                            $(".messagebox-messages-content").animate({
                                scrollTop : $(".messagebox-messages-content")[0].scrollHeight
                            },400);
                        }
                    },
                    error: ()=>{}
                });
                
            }


            function accessConversation(obj){
                //akses conversation
                bottomMessageID = 0;
                openedConversationID = $(obj).data("convid");
                if(openedConversationID != "temp"){
                    $("#conv_temp").remove();
                }
                $(".content-container").animate({
                    scrollLeft: window.innerWidth
                }, 250);
                mobileStatus = true;
                $("#conv_"+$(obj).data("convid")).removeClass("notread");
                $(".messagebox-messages").removeClass("d-none");
                $(".messagebox-messages-navbar-header-img img").attr("src","../miscellaneous/assets/profiles/"+$(obj).data("username")+"."+$(obj).data("extension"));
                $(".messagebox-messages-navbar-header-username a").text($(obj).data("username"));
                $(".messagebox-messages-navbar-header-username a").attr("href","../"+$(obj).data("username") );
                $(".messagebox-messages-input-sendbutton").data("username", $(obj).data("username") );
                $(".messagebox-messages-input-sendbutton").data("convid", $(obj).data("convid") );
                fetchMessage(obj);
            }

            function postMessage(obj){
                //ngesent message
                $.ajax({
                    url: "../miscellaneous/phps/services/message.php",
                    data: {
                        requestmode: "send",
                        convid: $(obj).data("convid"),
                        message: $(obj).prev().val().trim(),
                        username: $(obj).data("username")
                    },
                    method: "POST",
                    success: (data)=>{
                        if(data.status == 2){
                            openedConversationID = data.convid;
                            $(".messagebox-messages-input-sendbutton").data("convid", data.convid );   
                        }
                        refreshMessage($(obj).data("convid"));
                        fetchConversation();
                        $(obj).prev().val("");
                        checkMessageArea($(obj).prev()[0]);
                    },
                    error: ()=>{}
                });
            }

            function setMessagesRead(){
                $(".messagebox-messages-content-you-detail-read").html("Read"); 
            }








            window.onresize = ()=>{
                resizeAdaptive();
                if(mobileStatus)
                    $(".content-container").scrollLeft(window.innerWidth);
                else
                    $(".content-container").scrollLeft(0);
            };
            window.onload = resizeAdaptive;
        </script>
    </body>
</html>