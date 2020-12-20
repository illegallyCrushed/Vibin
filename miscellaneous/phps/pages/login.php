<?php
if (!isset($page)) {
  header("Location: ./");
  exit();
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once './miscellaneous/phps/addons/libraries.php'; ?>
    <?php require_once './miscellaneous/phps/addons/metatags.php'; ?>

    <title>Vibin</title>
    <style>
        body {
            background: #ffffff;
            width: 100vw;
            height: 100vh;
            position: relative;
            display: grid;
            place-items: center;
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .login-base{
            width: 100%;
            max-width: 975px;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            padding: 50px;
        }

        .login-maincontainer{
            width: 100%;
            height: 100%;
            position: relative;
            display: grid;
            grid-template-columns: 50% 50%;
        }

        .login-svgcontainer{
            width: 100%;
            height: 100%;
            float: left;
            display: grid;
            place-items: center;
            z-index: 2;
        }

        .login-svgcontainer-svgs{
            position: absolute;
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
            transform: translate(0,0) scale(1.5);
        }

        .introsvgtrans{
            transform: translate(-25%,0) scale(1)!important;
        }

        .strokelayer,
        .colorlayer {
            position: absolute;
            height: auto;
            top: 0;
            left: 0;
            width: 100%;
            display: grid;
            place-items: center;
        }

        .strokelayer svg,
        .colorlayer svg{
            max-width: 300px; 
            width: 100%;
        }

        .strokelayer{
            position: static;
        }

        .colorlayer{
            transform: translate(0,-50%);
            top: 50%;
        }

        .strokedraw {
            transition: stroke-dashoffset 1.5s;
            transition-timing-function: cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .animatefillkey path {
            opacity: 0;
            transition: opacity 0.5s;
        }

        .login-formcontainer{
            width: 100%;
            height: 100%;
            position: relative;
            transform: translate(-50%,0);
            opacity: 0;
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
            display: grid;
            padding-top: 15px;
            place-items:center;
        }

        .introformtrans{
            opacity: 1;
            transform: translate(0,0) !important;
        }

        .login-formcontainer-box{
            width: 100%;
            height: 400px;
            border-radius: 10px;
            border-style: solid;
            border-color: #dbdbdb;
            border-width: 2px;
            /* background-color: #fafafa; */
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
            display: grid;
            grid-template-rows: 80% 20%;
            place-items: center;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .login-formcontainer-box-inner{
            overflow: hidden;
            overflow-x: hidden;
            height: 100%;
        }

        .login-formcontainer-box-inner-expander{
            width: 200%;
            height: 100%;
            display: grid;
            grid-template-columns: 50% 50%;
        }

        .loginform-container{
            width: 100%;
            height: 100%;
            float: left;
            position: relative;
            display: grid;
            grid-template-rows: 50% 50%;
            place-items: center;
        }

        .login-formcontainer-box-username{
            width: 100%;
            padding: 0 40px;
            transform: translate(0, 0)!important;
        }

        .login-formcontainer-box-username input{
            width: 100%;
            outline: 0;
            border-width: 0;
            border-bottom-width: 2px;
            background-color: none;
        }

        .login-formcontainer-box-username .login-username-name-label{
            font-size: 16px;
            font-weight: 600;
            transform: translate(17px,30px) scale(1.3);
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
        }

        .login-formcontainer-box-username .login-username-error-label{
            font-size: 14px;
            font-weight: 500;
            color: #ed4956;
        }

        .login-formcontainer-box-password{
            width: 100%;
            padding: 0 40px;
            transform: translate(0,-60%);
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
        }

        .login-formcontainer-box-password input{
            width: 100%;
            outline: 0;
            border-width: 0;
            border-bottom-width: 2px;
            background-color: none;
            
        }

        .login-formcontainer-box-password .login-password-name-label{
            font-size: 16px;
            font-weight: 600;
            transform: translate(10px,30px) scale(1.3);
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
        }

        .login-formcontainer-box-password .login-password-error-label{
            font-size: 14px;
            font-weight: 500;
            color: #ed4956;
        }

        .login-password-show-toggle{
            width: 25px;
            height: 25px;
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translate(-10%,-50%);
            display: grid;
            place-items: center;
            cursor: pointer;
            opacity: 0.5;
        }

        .login-password-show-toggle svg{
            width: 25px;
            position: absolute;
        }

        .login-password-show-toggle .hide{
            display: none;
        }

        .registerform-container{
            width: 100%;
            height: 100%;
            float: left;
            position: relative;
            display: grid;
            grid-template-rows: 33.3333% 33.3333% 33.3333%;
            place-items: center;
            padding-top: 20px;
            padding-bottom: 30px;
        }

        .register-formcontainer-box-email{
            width: 100%;
            padding: 0 40px;
            transform: translate(0, 0)!important;
        }

        .register-formcontainer-box-email input{
            width: 100%;
            outline: 0;
            border-width: 0;
            border-bottom-width: 2px;
            background-color: none;
        }

        .register-formcontainer-box-email .register-email-name-label{
            font-size: 16px;
            font-weight: 600;
            transform: translate(5px,30px) scale(1.3);
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
        }

        .register-formcontainer-box-email .register-email-error-label{
            font-size: 14px;
            font-weight: 500;
            color: #ed4956;
        }

        .register-formcontainer-box-username{
            width: 100%;
            padding: 0 40px;
            transform: translate(0, 0)!important;
        }

        .register-formcontainer-box-username input{
            width: 100%;
            outline: 0;
            border-width: 0;
            border-bottom-width: 2px;
            background-color: none;
        }

        .register-formcontainer-box-username .register-username-name-label{
            font-size: 16px;
            font-weight: 600;
            transform: translate(10px,30px) scale(1.3);
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
        }

        .register-formcontainer-box-username .register-username-error-label{
            font-size: 14px;
            font-weight: 500;
            color: #ed4956;
        }

        .register-formcontainer-box-password{
            width: 100%;
            padding: 0 40px;
            transform: translate(0, 0)!important;
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
            position: relative;

        }

        .register-formcontainer-box-password input{
            width: 100%;
            outline: 0;
            border-width: 0;
            border-bottom-width: 2px;
            background-color: none;
            
        }

        .register-formcontainer-box-password .register-password-name-label{
            font-size: 16px;
            font-weight: 600;
            transform: translate(10px,30px) scale(1.3);
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
        }

        .register-formcontainer-box-password .register-password-error-label{
            font-size: 14px;
            font-weight: 500;
            color: #ed4956;
        }

        .register-password-show-toggle{
            width: 25px;
            height: 25px;
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translate(-10%,-50%);
            display: grid;
            place-items: center;
            cursor: pointer;
            opacity: 0.5;
        }

        .register-password-show-toggle svg{
            width: 25px;
            position: absolute;
        }

        .register-password-show-toggle .hide{
            display: none;
        }

        .inputfilled .login-username-name-label,
        .inputfilled .login-password-name-label,
        .inputfilled .register-email-name-label,
        .inputfilled .register-username-name-label,
        .inputfilled .register-password-name-label
        {
            transform: translate(0,0) scale(1);
        }

        .inputfilled {
            transform: translate(0, -30%);
        }

        .login-formcontainer-box-actions{
            width: 100%;
            padding: 0 40px;
            padding-bottom: 30px;
            display: grid;
            grid-template-columns: 50% 50%;
        }

        .login-formcontainer-box-actions-login{
            display: grid;
            place-items: center;
            background: #0095ff;
            border-radius: 10px;
            height: 40px;
            margin-right: 7px;
            font-weight: 500;
            color: white;
            position: relative;
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
            cursor: pointer;
        }

        .login-formcontainer-box-actions-register{
            display: grid;
            place-items: center;
            background: white;
            border-radius: 10px;
            height: 40px;
            margin-left: 7px;
            font-weight: 500;
            color: black;
            position: relative;
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
            cursor: pointer;
        }

        .login-formcontainer-box-actions-login-arrow{
            position: absolute;
            display: grid;
            place-items: center;
            left: 10%;
            opacity: 0;
            transform: translate(50px,0) rotate(180deg) scale(0);
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
        }

        .login-formcontainer-box-actions-login-arrow svg{
            width: 25px;
        }

        .login-formcontainer-box-actions-register-arrow{
            position: absolute;
            display: grid;
            place-items: center;
            right: 10%;
            transform: translate(0,0) rotate(180deg) scale(1);
            transition-duration: 0.4s!important;
            transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
            transition-property: all!important;
        }

        .login-formcontainer-box-actions-register-arrow svg{
            width: 25px;
        }

        .login-formcontainer-box-loading{
            width: 100%;
            height: 400px;
            position: relative;
            top: -397px;
            left: 0;
            background: rgba(0,0,0,0.5);
            border-radius: 10px;
            display: grid;
            place-items: center;
        }

        /* .login-box-accepted{
            transform: translate(-50%,0) scaleY(3) scaleX(4);
        } */

        .jqueryconf-pop-setwidth{
            max-width: 576px!important;
            width: 100%!important;
        }

        .jconfirm-box{
            border-radius: 7px!important;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (max-width: 576px) {
            .jqueryconf-pop-setwidth{
                max-width: 100%!important;
                width: 100%!important;
                padding: 0!important;
            }

            .jconfirm-box{
                border-radius: 0px!important;
                border-width: 0px!important;
            }
        }

        @media (max-width: 800px) {

            .login-base{
                padding: 0;
            }

            .login-maincontainer{
                grid-template-columns: 100%;
                grid-template-rows: 50% 50%;
            }

            .login-svgcontainer-svgs{
                position: absolute;
                width: 100%;
                height: 100%;
                display: grid;
                place-items: center;
            }


            .login-formcontainer{
                padding: 0 5%;
                transform: translate(0,-50%);
            }

            .introformtrans{
                transform: translate(0,-30%) !important;
            }

            .login-svgcontainer-svgs{
                transform: translate(0,0) scale(1.2);
            }

            .introsvgtrans{
                transform: translate(0,-25%) scale(1)!important;
            }

            .strokelayer svg,
            .colorlayer svg{
                max-width: 200px; 
                width: 100%;
            }
            
            .login-formcontainer-box-username{
                padding: 0 20px;
            }

            .login-formcontainer-box-password{
                padding: 0 20px;
            }

            .login-password-show-toggle{
                right: 20px;
            }

            .register-formcontainer-box-email{
                padding: 0 20px;
            }

            .register-formcontainer-box-username{
                padding: 0 20px;
            }

            .register-formcontainer-box-password{
                padding: 0 20px;
            }

            .register-password-show-toggle{
                right: 20px;
            }

            .login-formcontainer-box-actions{
                padding: 0 20px;
                padding-bottom: 30px;
            }

        }

    </style>
</head>
    <script>
    </script>
    <body>
        <div class="login-base">
            <div class="login-maincontainer">

                <div class="login-svgcontainer">
                    <div class="login-svgcontainer-svgs">
                        <div class="strokelayer animatekey">
                            <?= file_get_contents("./miscellaneous/assets/vibin_logo_nofill.svg") ?>
                        </div>
                        <div class="colorlayer animatefillkey">
                            <?= file_get_contents("./miscellaneous/assets/vibin_logo.svg") ?>
                        </div>
                    </div>
                </div>
                
                <div class="login-formcontainer">
                    <div class="login-formcontainer-box">
                        <div class="login-formcontainer-box-inner">
                            <div class="login-formcontainer-box-inner-expander">
                                <div class="loginform-container">
                                    <div class="login-formcontainer-box-username">
                                        <label class="login-username-name-label" for="login-username">Username/Email</label>
                                        <input onfocus="inputFocus(this)" onblur="inputBlur(this)" type="text" id="login-username" required>
                                        <label class="login-username-error-label" for="login-username"></label>
                                    </div>

                                    <div class="login-formcontainer-box-password">
                                        <div class="login-password-show-toggle" onclick="toggleVisibility('login')">
                                            <?= file_get_contents("./miscellaneous/assets/show_pass_icon.svg") ?>
                                            <?= file_get_contents("./miscellaneous/assets/hide_pass_icon.svg") ?>
                                        </div>
                                        <label class="login-password-name-label" for="login-password">Password</label>
                                        <input onfocus="inputFocus(this)" onblur="inputBlur(this)" type="password" id="login-password" required>
                                        <label class="login-password-error-label" for="login-password"></label>
                                    </div>
                                </div>
                                <div class="registerform-container">

                                    <div class="register-formcontainer-box-email">
                                        <label class="register-email-name-label" for="register-email">Email</label>
                                        <input onfocus="inputFocus(this)" onblur="inputBlur(this)" type="text" id="register-email" required>
                                        <label class="register-email-error-label" for="register-email"></label>
                                    </div>

                                    <div class="register-formcontainer-box-username">
                                        <label class="register-username-name-label" for="register-username">Username</label>
                                        <input onfocus="inputFocus(this)" onblur="inputBlur(this)" type="text" id="register-username" required>
                                        <label class="register-username-error-label" for="register-username"></label>
                                    </div>

                                    <div class="register-formcontainer-box-password">
                                        <div class="register-password-show-toggle" onclick="toggleVisibility('register')">
                                            <?= file_get_contents("./miscellaneous/assets/show_pass_icon.svg") ?>
                                            <?= file_get_contents("./miscellaneous/assets/hide_pass_icon.svg") ?>
                                        </div>
                                        <label class="register-password-name-label" for="register-password">Password</label>
                                        <input onfocus="inputFocus(this)" onblur="inputBlur(this)" type="password" id="register-password" required>
                                        <label class="register-password-error-label" for="register-password"></label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="login-formcontainer-box-actions">
                            <div class="login-formcontainer-box-actions-login" onclick="loginAction()">
                                Login
                                <div class="login-formcontainer-box-actions-login-arrow">
                                    <?= file_get_contents("./miscellaneous/assets/back_icon.svg") ?>
                                </div>
                            </div>
                            <div class="login-formcontainer-box-actions-register" onclick="registerAction()">
                                Register
                                <div class="login-formcontainer-box-actions-register-arrow">
                                    <?= file_get_contents("./miscellaneous/assets/back_icon.svg") ?>
                                </div>
                            </div>
                        </div>
                        <div class="login-formcontainer-box-loading d-none">
                            <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            //prepping
            var SVGStrokeHandle = document.querySelectorAll(".animatekey svg path");
            var SVGFillHandle = document.querySelectorAll(".animatefillkey svg path");
            SVGStrokeHandle.forEach((elm) => {
              let pathlen = elm.getTotalLength();
              elm.style.strokeDasharray = pathlen;
              elm.style.strokeDashoffset = pathlen;
            });
            setTimeout(() => {
              SVGStrokeHandle.forEach((elm) => {
                elm.classList.add("strokedraw");
              });
            }, 100);
        
            function spawnCharLogo(index, strokehandle, fillhandle) {
              strokehandle[index].style.strokeDashoffset = 0;
              setTimeout(() => {
                fillhandle[index].style.opacity = 1;
              }, 1000);
            }
            function despawnCharLogo(index, strokehandle, fillhandle) {
              fillhandle[index].style.opacity = 0;
              setTimeout(() => {
                strokehandle[index].style.strokeDashoffset = strokehandle[index].style.strokeDasharray;
              }, 300);
            }
        
            //animate segment
            // var retSegCall = despawnWholeLogo;
            function spawnWholeLogo(callback) {
              let pathCntr = SVGStrokeHandle.length;
              let cntrUp = 0;
              let invHandle = setInterval(() => {
                spawnCharLogo(cntrUp, SVGStrokeHandle, SVGFillHandle);
                cntrUp++;
                if (pathCntr == cntrUp) {
                  clearInterval(invHandle);
                  setTimeout(() => {
                    callback();
                  }, 1500);
                }
              }, 250);
            }

            spawnWholeLogo(()=>{
                $(".login-svgcontainer-svgs").addClass("introsvgtrans");
                $(".login-formcontainer").addClass("introformtrans");
                setTimeout(() => {
                    $(".login-formcontainer").css("z-index","4");
                }, 500);
            });

            var loginFormType = "login";

            function toggleVisibility(str, bypassclose=false){
                if(!bypassclose){
                    if($("#"+str+"-password").attr("type") == "password"){
                        $("#"+str+"-password").attr("type","text")
                        $("."+str+"-password-show-toggle .hide").addClass("d-block");
                        $("."+str+"-password-show-toggle .show").addClass("d-none");
                    }else{
                        $("#"+str+"-password").attr("type","password")
                        $("."+str+"-password-show-toggle .hide").removeClass("d-block");
                        $("."+str+"-password-show-toggle .show").removeClass("d-none");
                    }
                }else{
                    $("#"+str+"-password").attr("type","password")
                    $("."+str+"-password-show-toggle .hide").removeClass("d-block");
                    $("."+str+"-password-show-toggle .show").removeClass("d-none");
                }
            }

            function inputFocus(obj){
                objid = $(obj).attr("id");
                if(objid=="login-username"){
                    $(".login-formcontainer-box-username").addClass("inputfilled");
                    toggleVisibility("login",true);
                    toggleVisibility("register",true); 
                }else if(objid=="login-password"){
                    $(".login-formcontainer-box-password").addClass("inputfilled");
                }else if(objid=="register-email"){
                    $(".register-formcontainer-box-email").addClass("inputfilled");
                    toggleVisibility("login",true);
                    toggleVisibility("register",true);
                }else if(objid=="register-username"){
                    $(".register-formcontainer-box-username").addClass("inputfilled");
                    toggleVisibility("login",true);
                    toggleVisibility("register",true);
                }else if(objid=="register-password"){
                    $(".register-formcontainer-box-password").addClass("inputfilled");
                }
            };

            function inputBlur(obj){
                objid = $(obj).attr("id");
                toggleVisibility("login",true);
                toggleVisibility("register",true);
                if(obj.value.length==0){
                    if(objid=="login-username"){
                        $(".login-formcontainer-box-username").removeClass("inputfilled");
                    }else if(objid=="login-password"){
                        $(".login-formcontainer-box-password").removeClass("inputfilled");
                    }else if(objid=="register-email"){
                        $(".register-formcontainer-box-email").removeClass("inputfilled");
                    }else if(objid=="register-username"){
                        $(".register-formcontainer-box-username").removeClass("inputfilled");
                    }else if(objid=="register-password"){
                        $(".register-formcontainer-box-password").removeClass("inputfilled");
                    }
                }
            };

            function loginAction(){
                //pas tombil login dipencet
                if(loginFormType == "login"){
                    $(".login-username-error-label").text("");
                    $(".login-password-error-label").text("");
                    $(".login-formcontainer-box-loading").removeClass("d-none");
                    let strUsername = $("#login-username").val().toLowerCase();
                    let strPassword = $("#login-password").val();

                    if(strUsername.length != 0 && strPassword.length != 0){
                        //kalo semua gak kosong
                        $.ajax({
                            url: "./miscellaneous/phps/services/login.php",
                            data: {
                            username: strUsername,
                            password: strPassword,
                            },
                            method: "POST",
                            success: (data)=>{
                                if(data.errorcode.username == 0 && data.errorcode.password == 0){
                                    //kalo gaada error, refresh page
                                    window.location.href = "./";
                                }else{
                                    //kalo ada error
                                    switch (data.errorcode.username) {
                                        case 1:
                                            $(".login-username-error-label").text("Username/Email cannot be empty!");
                                            break;
                                        case 2:
                                            $(".login-username-error-label").text("Username not found!");
                                            break;
                                        case 3:
                                            $(".login-username-error-label").text("Email not found!");
                                            break;
                                    }
                                    switch (data.errorcode.password) {
                                        case 1:
                                            $(".login-password-error-label").text("Password cannot be empty!");
                                            break;
                                        case 2:
                                            $(".login-password-error-label").text("Incorrect password!");
                                            break;
                                    }
                                    $(".login-formcontainer-box-loading").addClass("d-none");
                                }
                            },
                            error: ()=>{
                                //kalo ajax error
                                $.confirm({
                                    title: 'Login Failed!',
                                    icon: 'far fa-times-circle',
                                    columnClass: 'jqueryconf-pop-setwidth col',
                                    content: 'Try again later',
                                    type: 'red',
                                    draggable: false,
                                    animateFromElement: false,
                                    backgroundDismiss: true,
                                    containerFluid: true,
                                    animationBounce: 1,
                                    offsetTop: 0,
                                    offsetBottom: 0,
                                    theme: 'modern',
                                    buttons: {
                                        close:{
                                            text: 'Oh no..',
                                            btnClass: 'btn-red',
                                        }
                                    },
                                    onClose: ()=>{
                                        $(".login-formcontainer-box-loading").addClass("d-none");
                                    }
                                });
                            }
                        });
                    }else{
                        if(strUsername.length == 0){
                            $(".login-username-error-label").text("Username/Email cannot be empty!");
                        }
                        if(strPassword.length == 0){
                            $(".login-password-error-label").text("Password cannot be empty!");
                        }
                        $(".login-formcontainer-box-loading").addClass("d-none");
                    }

                }else{
                    $(".login-formcontainer-box-inner").animate({
                        scrollLeft: 0
                    }, 250);
                    $(".login-formcontainer-box-actions-login-arrow").attr("style","");
                    $(".login-formcontainer-box-actions-login").attr("style","");
                    $(".login-formcontainer-box-actions-register-arrow").attr("style","");
                    $(".login-formcontainer-box-actions-register").attr("style","");
                    
                    loginFormType = "login";
                }
            }

            function registerAction(){
                //pas tombol register di pencet
                if(loginFormType == "register"){
                    $(".register-email-error-label").text("");
                    $(".register-username-error-label").text("");
                    $(".register-password-error-label").text("");
                    $(".login-formcontainer-box-loading").removeClass("d-none");
                    let strEmail = $("#register-email").val().toLowerCase();
                    let strUsername = $("#register-username").val().toLowerCase();
                    let strPassword = $("#register-password").val();
                    let strEmailStatus = false;
                    let strUsernameStatus = false;
                    let strPasswordStatus = false;

                    //cek tahap lokal

                    //cek email
                    if(strEmail.length != 0){
                        //kalo email gak kosong, cek email pake regular expr
                        let emailRegEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i;
                        let regexEmailVerify = emailRegEx.test(strEmail);
                        if(regexEmailVerify){
                            //kalo lolos test regex
                            strEmailStatus = true;
                        }else{
                            //kalo gak lolos test regex
                            strEmailStatus = false;
                            $(".register-email-error-label").text("Email is invalid!");
                        }
                    }else{
                        //kalo email kosong
                        strEmailStatus = false;
                        $(".register-email-error-label").text("Email cannot be empty!");
                    }

                    //cek username
                    if(strUsername.length != 0){
                        //kalo username gak kosong, cek ukuran
                        if(strUsername.length <= 20 && strUsername.length >= 5){
                            //kalo ukuran aman, cek username pake regex dan cek huruf pertama huruf
                            let usernameRegEx = /[^a-z0-9\.\_]/i;
                            let usernameRegEx2 = /^[a-z]+/i;
                            let regexUsernameVerify = !usernameRegEx.test(strUsername);
                            let regexUsernameVerify2 = usernameRegEx2.test(strUsername);
                            if(regexUsernameVerify && regexUsernameVerify2){
                                //kalo lolos test regex
                                strUsernameStatus = true;
                            }else{
                                //kalo gak lolos test regex
                                strUsernameStatus = false;
                                $(".register-username-error-label").text("Username must starts with an alphabet and can only contain alphabets, numbers, '.', and '_'!");
                            }
                        }else{
                            //kalo ukuran gak aman
                            strUsernameStatus = false;
                            $(".register-username-error-label").text("Username must be at least (5) and not more than (20) characters!");
                        }
                    }else{
                        //kalo username kosong
                        strUsernameStatus = false;
                        $(".register-username-error-label").text("Username cannot be empty!");
                    }

                    //cek password
                    if(strPassword.length != 0){
                        //kalo password gak kosong, cek ukuran
                        if(strPassword.length <= 50 && strPassword.length >= 6){
                            //kalo ukuran aman
                            strPasswordStatus = true;
                        }else{
                            //kalo ukuran gak aman
                            strPasswordStatus = false;
                            $(".register-password-error-label").text("Password must be at least (6) and not more than (50) characters!");
                        }
                    }else{
                        //kalo password kosong
                        strPasswordStatus = false;
                        $(".register-password-error-label").text("Password cannot be empty!");
                    }

                    //forward dan cek ke server

                    if(strEmailStatus && strUsernameStatus && strPasswordStatus){
                        //kalo semua aman, push ke server

                        $.ajax({
                            url: "./miscellaneous/phps/services/register.php",
                            data: {
                              email: strEmail,
                              username: strUsername,
                              password: strPassword,
                            },
                            method: "POST",
                            success: (data)=>{
                                if(data.errorcode.email == 0 && data.errorcode.username == 0 && data.errorcode.password == 0){
                                    //kalo gaada error
                                    $.confirm({
                                        title: 'Registration Successfull!',
                                        icon: 'far fa-check-circle',
                                        columnClass: 'jqueryconf-pop-setwidth col',
                                        content: 'Login to continue',
                                        type: 'green',
                                        draggable: false,
                                        animateFromElement: false,
                                        backgroundDismiss: true,
                                        containerFluid: true,
                                        animationBounce: 1,
                                        offsetTop: 0,
                                        offsetBottom: 0,
                                        theme: 'modern',
                                        buttons: {
                                            close:{
                                                text: 'Bring it on!',
                                                btnClass: 'btn-green',
                                            }
                                        },
                                        onClose: ()=>{
                                            loginAction();
                                            $("#register-email").val("");
                                            $("#register-username").val("");
                                            $("#register-password").val("");
                                            inputBlur($("#register-email")[0]);
                                            inputBlur($("#register-username")[0]);
                                            inputBlur($("#register-password")[0]);
                                            $(".login-formcontainer-box-loading").addClass("d-none");
                                        }
                                    });
                                }else{
                                    //kalo ada error
                                    switch (data.errorcode.email) {
                                        case 1:
                                            $(".register-email-error-label").text("Email cannot be empty!");
                                            break;
                                        case 2:
                                            $(".register-email-error-label").text("Email is invalid!");
                                            break;
                                        case 3:
                                            $(".register-email-error-label").text("Email is registered to other account!");
                                            break;
                                    }
                                    switch (data.errorcode.username) {
                                        case 1:
                                            $(".register-username-error-label").text("Username cannot be empty!");
                                            break;
                                        case 2:
                                            $(".register-username-error-label").text("Username must be at least (5) and not more than (20) characters!");
                                            break;
                                        case 3:
                                            $(".register-username-error-label").text("Username must starts with an alphabet and can only contain alphabets, numbers, '.', and '_'!");
                                            break;
                                        case 4:
                                            $(".register-username-error-label").text("Username is taken!");
                                            break;
                                        case 5:
                                            $(".register-username-error-label").text("Username is not allowed!");
                                            break;
                                    }
                                    switch (data.errorcode.password) {
                                        case 1:
                                            $(".register-password-error-label").text("Password cannot be empty!");
                                            break;
                                        case 2:
                                            $(".register-password-error-label").text("Password must be at least (6) and not more than (50) characters!");
                                            break;
                                    }
                                    $(".login-formcontainer-box-loading").addClass("d-none");
                                }
                            },
                            error: ()=>{
                                //kalo ajax error
                                $.confirm({
                                    title: 'Registration Failed!',
                                    icon: 'far fa-times-circle',
                                    columnClass: 'jqueryconf-pop-setwidth col',
                                    content: 'Try again later',
                                    type: 'red',
                                    draggable: false,
                                    animateFromElement: false,
                                    backgroundDismiss: true,
                                    containerFluid: true,
                                    animationBounce: 1,
                                    offsetTop: 0,
                                    offsetBottom: 0,
                                    theme: 'modern',
                                    buttons: {
                                        close:{
                                            text: 'Oh no..',
                                            btnClass: 'btn-red',
                                        }
                                    },
                                    onClose: ()=>{
                                        $(".login-formcontainer-box-loading").addClass("d-none");
                                    }
                                });
                            }
                        });
                        
                    }else{
                        $(".login-formcontainer-box-loading").addClass("d-none");
                    }


                }else{
                    //ganti form
                    $(".login-formcontainer-box-inner").animate({
                        scrollLeft: $(".login-formcontainer-box-inner")[0].scrollWidth/2
                    }, 250);
                    $(".login-formcontainer-box-actions-register-arrow").css("transform","translate(-50px,0) rotate(0) scale(0)");
                    $(".login-formcontainer-box-actions-register-arrow").css("opacity",0);
                    $(".login-formcontainer-box-actions-register").css("background","#0095ff");
                    $(".login-formcontainer-box-actions-register").css("color","white");
                    $(".login-formcontainer-box-actions-login-arrow").css("transform","translate(0,0) rotate(0) scale(1)");
                    $(".login-formcontainer-box-actions-login-arrow").css("opacity",1);
                    $(".login-formcontainer-box-actions-login").css("background","white");
                    $(".login-formcontainer-box-actions-login").css("color","black");
                    loginFormType = "register";
                }
            }

            window.onresize = ()=>{
                if(loginFormType == "login"){
                    $(".login-formcontainer-box-inner").scrollLeft(0);
                }else{
                    $(".login-formcontainer-box-inner").scrollLeft($(".login-formcontainer-box-inner")[0].scrollWidth/2);
                }
            }

        // setTimeout(() => {
        //     $(".login-formcontainer-box").addClass("login-box-accepted");
        // }, 5000);

        </script>
    </body>
</html>