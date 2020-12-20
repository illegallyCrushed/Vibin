<?php
if (isset($page)) {
  //kalo bagian dari sebuah page

  $lochead = "./"; //set kepala directory
  if ($page != "home" && $page != "profile" && $page != "login") {
    $lochead = "../";
  } //selain home profile login lokasinya dlm folder, harus mundur
} else {
  //kalo bukan bagian dari page (dipanggil langsung dll)

  header("Location: ../"); //balik ke home
  exit();
} ?>

<style>

    *{
        margin:0;
        padding:0;
        border:0;
        font-family: 'Roboto';
        scroll-behavior: smooth;
    }

    body{
        min-height: 100vh;
        overflow-x: hidden;
        background-color: #fafafa;
        width: 100vw;
    }

    img{
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        object-fit: cover;
    }

    a{
        color: black;
    }

    a:hover{
        color:black;
    }

    a:visited{
        color:black;
    }

    button{
        background: white;
        outline: none;
    }

    *:focus{
        outline: none!important;
    }
   
    ::-webkit-scrollbar{
	    width: 10px;
    }

    ::-webkit-scrollbar-track-piece{
    	background-color: #FFF;
    }

    ::-webkit-scrollbar-thumb{
    	background-color: #CBCBCB;
    	border: 2px solid #FFF;
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover{
    	background-color: #909090;
    }

    /* bootstrap and jconfirm overidding */

    .btn-red{
        background-color: #ed4956!important;
    }

    .btn-red:hover{
        background-color: #e74c3c!important;
    }

    .container-fluid{
        padding: 0!important;
    }

    .jqueryconf-pop-setwidth{
        max-width: 576px!important;
        width: 100%!important;
    }

    .jqueryconf-pop-del{
        max-width: 576px!important;
        width: 100%!important;
    }

    .jqueryconf-pop-setwidth .jconfirm-box{
        height: calc(100vh - 216px)!important;
        max-height: calc(100vh - 216px)!important;
        transition-duration: 0.4s!important;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
        transition-property: all!important;
    }

    .jqueryconf-pop-setwidth .jconfirm-content{
        height: calc(100vh - 362px)!important;
        max-height: calc(100vh - 362px)!important;
        transition-duration: 0.4s!important;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
        transition-property: all!important;
    }

    .jqueryconf-pop-setwidth .jconfirm-content-pane{
        height: calc(100vh - 362px)!important;
        max-height: calc(100vh - 362px)!important;
        transition-duration: 0.4s!important;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
        transition-property: all!important;
    }

    .jqueryconf-pop-setwidth .jconfirm-buttons{
        border: 0!important;
        padding-right: 15px!important;
        transition-duration: 0.4s!important;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
        transition-property: all!important;
    }

    .jcustom-button {
        width: 100%!important;
        height: 50px;
        transition-duration: 0.4s!important;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
        transition-property: all!important;
    }

    .appwidthnorm .jconfirm-content{
        height: calc(100vh - 284px)!important;
        max-height: calc(100vh - 284px)!important;
        transition-duration: 0.4s!important;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
        transition-property: all!important;
    }

    .appwidthnorm .jconfirm-content-pane{
        height: calc(100vh - 284px)!important;
        max-height: calc(100vh - 284px)!important;
        transition-duration: 0.4s!important;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1)!important;
        transition-property: all!important;
    }

    .jconfirm-box{
        border-radius: 7px!important;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .jconfirm-profileprompt{
        max-width: 576px!important;
        width: 100%!important;
    }

    .jconfirm-profileprompt .jconfirm-box{
        padding-top: 5px!important;
    }

    .jconfirm-profileprompt .jconfirm-content-pane{
        margin-bottom: 0px!important;
    }

    .jconfheightignore{
        max-width: 576px!important;
        width: 100%!important;
    }

    .jconfirm-content{
        scroll-behavior: auto!important;
    }

    .profileprompt-profile{
        width: 100%;
        height: 50px;
        font-size: 15px;
    }

    .profileprompt-profile-img{
        float: left;
        border-radius: 50px;
        border-style: solid;
        transition: border-color 0.75s, transform 0.75s;
        overflow: hidden;
        display: grid;
        place-items: center;
        border-width: 1px;
        transform: translate(0,-50%) scale(0.9);
        position: relative;
        top:50%;
    }

    .profileprompt-profile img{
        height: 39px!important;
        width: 39px;
        transition: transform 0.75s;
        border-radius: 100px;
    }

    .profileprompt-profile-text{
        float:left;
        transform: translate(0,-50%);
        position: relative;
        top: 50%;
        padding-left: 20px;
        font-weight:0;
        transition: letter-spacing 0.2s, font-weight 0.2s;
    }

    .profileprompt-logout{
        width: 100%;
        height: 50px;
        font-size: 15px;
    }

    .profileprompt-logout svg{
        float: left;
        height: 34px!important;
        width: auto;
        transform: translate(0,-50%);
        position: relative;
        top: 50%;
        margin-left: 6px;
    }

    .profileprompt-logout svg path{
        transition: transform 0.75s;
        position: relative;
    }

    .profileprompt-logout-text{
        float:left;
        transform: translate(0,-50%);
        position: relative;
        top: 50%;
        padding-left: 8px;
        font-weight:0;
        transition: letter-spacing 0.2s, font-weight 0.2s;
    }

    @media (max-width: 576px) {
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
            height: 0px;
        }

        ::-webkit-scrollbar-thumb {
            background: transparent;
            border: 0px;
        }

        .jconfheightignore{
            max-width: 100%!important;
            width: 100%!important;
        }

        .jqueryconf-pop-setwidth{
            max-width: 100%!important;
            width: 100%!important;
        }

        .jqueryconf-pop-setwidth .jconfirm-box{
            height: 100vh!important;
            max-height: 100vh!important;
        }

        .jqueryconf-pop-setwidth .jconfirm-content{
            height: calc(100vh - 144px)!important;
            max-height: calc(100vh - 144px)!important;
        }

        .jqueryconf-pop-setwidth .jconfirm-content-pane{
            height: calc(100vh - 144px)!important;
            max-height: calc(100vh - 144px)!important;
        }

        .appwidthnorm .jconfirm-content{
            height: calc(100vh - 66px)!important;
            max-height: calc(100vh - 66px)!important;
        }

        .appwidthnorm .jconfirm-content-pane{
            height: calc(100vh - 66px)!important;
            max-height: calc(100vh - 66px)!important;
        }

        .jconfirm-box{
            border-radius: 0px!important;
            border-width: 0px!important;
        }
    }

    /* navbars */

    nav{
        position: fixed;
        width: 100vw;
        height: 54px;
        border-color: #dbdbdb;
        border-style: solid;
        background-color: #ffffff;
        left: 0;
        z-index: 999;
    }

    .topnav{
        border-bottom-width: 1px;
        display: flex;
        justify-content: center;
        z-index: 999;
    }

    .toplargenav{
        max-width: 975px;
        width: 100%;
        height: 100%;
        padding: 0 20px;
    }

    .topsmallnav{
        width: 100%;
        height: 100%;
        padding: 0 20px;
        overflow: hidden;
    }

    .topsmallnav .navlist svg{
        top:-1.5px;
    }

    .navmainlogocontainer svg{
        height : 25px;
    }

    .navmainlogocontainer{
        display: inline-block;
        position: relative;
        padding-top: 12px;
    }

    .topnav .toplargenav{
        overflow-y:hidden;
    }

    .topnav .navlist {
        display: inline-block;
        float: right;
    }
    .topnav .navlist ul{
        margin: 0;
        list-style: none;
        height: 100%;
    }

    .topnav .toplargenav .navlist ul li{
        float: left;
        width: 45px;
        position: relative;
        height: 54px;
        display: grid;
        place-items: center;
    }

    .topnav .navlist ul li .inactive{
        height: 25px;
    }

    .topnav .navlist ul li .active{
        height: 25px;
        position: absolute;
        opacity:0;
        transition: opacity 0.75s;
    }

    .topnav .navprofile{
        margin-left: 10px!important;;
        margin-top: 12px!important;
        width: auto!important;
        height: auto!important;
    }

    .topnav .topsmallnav .navlist ul li{
        float: left;
        position: relative;
        height: 54px;
        display: grid;
        place-items: center;
    }

    .topnav .topsmallnav .navlist ul li .active{
        height: 25px;
        position: absolute;
        top: 15px;
        opacity: 0;
        transition: opacity 0.75s;
    }

    .navmessage-badge{
        position: absolute;
        display: grid;
        place-items: center;
        top: 5px;
        right: 2px;
        padding: 0px 5px;
        border-radius: 12px;
        background: #ed4956;
        color: white;
        font-size: 12px;
        text-align: center;
        opacity: 0;
        transition: opacity 0.2s;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .topsmallnav .navmessage-badge {
        top: 5px;
        right: 13px;
    }

    .botnav{
        border-top-width: 1px;
        bottom: 0;
        position: fixed;
        overflow-y:hidden;
        z-index: 999;
    }

    .botnav .navlist {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .botnav .navlist ul{
        margin: 0;
        list-style: none;
    }
    .botnav .navlist ul li{
        float: left;
        display: grid;
        place-items: center;
        width: 20vw;
        height: 54px;
        position: relative;
    }

    .botnav .navlist li .inactive{
        height: 25px;
    }

    .botnav .navlist li .active{
        position: absolute;
        transform : translate(-50%,0);
        top: 15px;
        left: 50%;
        height: 25px;
        opacity: 0;
        transition: opacity 0.75s;
    }

    .navprofile{
        border-radius: 50px;
        border-style: solid;
        transition: border-color 0.75s, transform 0.75s;
        height: 30px;
        width: 30px;
        overflow: hidden;
        display: grid;
        place-items: center;
        border-width: 1px;
    }

    .navprofile img{
        height: 29px;
        width: 29px;
        transition: transform 0.75s;
        border-radius: 100px;
    }

    .navlist svg, .navlist img{
        cursor: pointer;
    }

    .navlist{
        overflow: hidden;
    }

    /* tooltip */

    #topnotiftooltip {
        background: #ed4956;
        color: white;
        font-weight: bold;
        padding: 4px 8px;
        font-size: 13px;
        border-radius: 7px;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        position: absolute;
        opacity: 0;
        top: 50px!important;
        bottom: auto!important;
        transition: transform 0.3s, opacity 0.2s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
    }

    #topnotiftooltip svg{
        height:14px;
        padding-bottom: 3px;
    }

    #arrow,
    #arrow::before {
        position: absolute;
        width: 8px;
        height: 8px;
        z-index: -1;
    }

    #arrow::before {
        background: #ed4956;
        transform: rotate(45deg);
        content:'';
    }

    #topnotiftooltip[data-popper-placement^='top'] > #arrow {
        bottom: -4px;
    }

    #topnotiftooltip[data-popper-placement^='bottom'] > #arrow {
        top: -4px;
    }

    #topnotiftooltip[data-popper-placement^='left'] > #arrow {
        right: -4px;
    }

    #topnotiftooltip[data-popper-placement^='right'] > #arrow {
        left: -4px;
    }

    #botnotiftooltip {
        background: #ed4956;
        color: white;
        font-weight: bold;
        padding: 4px 8px;
        font-size: 13px;
        border-radius: 7px;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        top: auto!important;
        bottom: 48px!important;
        position: absolute;
        opacity: 0;
        transition: transform 0.3s, opacity 0.2s;
        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
    }

    #botnotiftooltip svg{
        height: 14px;
        padding-bottom: 3px;
    }

    #botnotiftooltip[data-popper-placement^='top'] > #arrow {
        bottom: -4px;
    }

    #botnotiftooltip[data-popper-placement^='bottom'] > #arrow {
        top: -4px;
    }

    #botnotiftooltip[data-popper-placement^='left'] > #arrow {
        right: -4px;
    }

    #botnotiftooltip[data-popper-placement^='right'] > #arrow {
        left: -4px;
    }

    #fileImageUploadRequest{
        width:0;
        height:0;
        left: -10000px;
        position:absolute;
    }

</style>

<nav class="topnav <?php if ($page == "messages") {
  echo "d-none d-sm-flex";
}
//kalo page message, hide top navbar pas mobile
?> ">
    <div class="toplargenav d-none d-sm-block">
    <!-- top navbar desktop -->
        <div class="navmainlogocontainer" onclick="homeAction()">
            <?= file_get_contents($lochead . "miscellaneous/assets/vibin_logo.svg") ?>
        </div>
        <div class="navlist">
            <ul>
                <?php if (isset($_SESSION['username'])) { ?>
                <!-- kalo user logged in, tampilin full top navbar -->
                <li class="navhome" onclick="homeAction()">
                    <?= file_get_contents($lochead . "miscellaneous/assets/home_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/home_icon.svg") ?>
                </li>
                <li class="navnotif" onclick="notifAction()">
                    <?= file_get_contents($lochead . "miscellaneous/assets/notif_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/notif_icon.svg") ?>
                    <div id="topnotiftooltip" class="d-none" role="tooltip">
                            <span class="tooltiplikecontainer d-none">
                                <?= file_get_contents($lochead . "miscellaneous/assets/like_icon_white.svg") ?>
                                <span class="notiflikes"></span>
                            </span>
                            <span class="tooltiplikecommentspacer d-none">&nbsp;</span>
                            <span class="tooltipcommentcontainer d-none">
                                <?= file_get_contents($lochead . "miscellaneous/assets/comment_icon_white.svg") ?>
                                <span class="notifcomments"></span>
                            </span>
                            <span class="tooltipcommentfollowspacer d-none">&nbsp;</span>
                            <span class="tooltipfollowcontainer d-none">
                                <?= file_get_contents($lochead . "miscellaneous/assets/user_icon_white.svg") ?>
                                <span class="notiffollowers"></span>
                            </span>
                            <div id="arrow" data-popper-arrow></div>
                    </div>
                </li>
                <li class="navupload" onclick="uploadAction()">
                    <?= file_get_contents($lochead . "miscellaneous/assets/upload_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/upload_icon.svg") ?>
                </li>
                <li class="navsearch" onclick="searchAction()">
                    <?= file_get_contents($lochead . "miscellaneous/assets/search_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/search_icon.svg") ?>
                </li>
                <li class="navmessages" onclick="messagesAction()">
                    <?= file_get_contents($lochead . "miscellaneous/assets/messages_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/messages_icon.svg") ?>
                    <span class="navmessage-badge"></span>
                </li>
                <li class="navprofile" style="border-color: rgba(0,0,0,0);transform: scale(0.9);" onclick="profileAction()">
                    <img src="<?= $lochead . "miscellaneous/assets/profiles/" . $_SESSION['username'] . "." . $_SESSION['extension'] ?>"></img>
                </li>
                <?php } else { ?>
                <!-- kalo blm login tampilin login button -->
                <li class="navlogin" onclick="window.location.href='<?= $lochead ?>'">
                    <?= file_get_contents($lochead . "miscellaneous/assets/login_icon.svg") ?>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="topsmallnav d-block d-sm-none">
    <!-- top navbar mobile -->
        <div class="navmainlogocontainer" onclick="homeAction()">
            <?= file_get_contents($lochead . "miscellaneous/assets/vibin_logo.svg") ?>
        </div>
        <div class="navlist">
            <ul>
                <?php if (isset($_SESSION['username'])) { ?>
                <!-- kalo user logged in, tombol dm -->
                <li class="navmessages" onclick="messagesAction()">
                    <?= file_get_contents($lochead . "miscellaneous/assets/messages_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/messages_icon.svg") ?>
                </li>
                <div class="navmessage-badge"></div>
                <?php } else { ?>
                <!-- kalo blm login tampilin login button -->
                <li class="navlogin" onclick="window.location.href='<?= $lochead . 'login' ?>'">
                    <?= file_get_contents($lochead . "miscellaneous/assets/login_icon.svg") ?>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<?php if ($page != "messages" && isset($_SESSION['username'])) { ?>
<!-- kalo page message atau blm login, hide bottom navbar -->
<nav class="botnav d-block d-sm-none ">
    <div class="navlist">
        <ul>
            <li onclick="homeAction()">
                <div class="navhome" >
                    <?= file_get_contents($lochead . "miscellaneous/assets/home_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/home_icon.svg") ?>
                </div>
            </li>
            <li onclick="notifAction()">
                <div class="navnotif" >
                    <?= file_get_contents($lochead . "miscellaneous/assets/notif_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/notif_icon.svg") ?>
                </div>
                <div id="botnotiftooltip" class="d-none" role="tooltip">
                    <span class="tooltiplikecontainer d-none">
                        <?= file_get_contents($lochead . "miscellaneous/assets/like_icon_white.svg") ?>
                        <span class="notiflikes"></span>
                    </span>
                    <span class="tooltiplikecommentspacer d-none">&nbsp;</span>
                    <span class="tooltipcommentcontainer d-none">
                        <?= file_get_contents($lochead . "miscellaneous/assets/comment_icon_white.svg") ?>
                        <span class="notifcomments"></span>
                    </span>
                    <span class="tooltipcommentfollowspacer d-none">&nbsp;</span>
                    <span class="tooltipfollowcontainer d-none">
                        <?= file_get_contents($lochead . "miscellaneous/assets/user_icon_white.svg") ?>
                        <span class="notiffollowers"></span>
                    </span>
                    <div id="arrow" data-popper-arrow></div>
                </div>
            </li>
            <li onclick="uploadAction()">
                <div class="navupload" >
                    <?= file_get_contents($lochead . "miscellaneous/assets/upload_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/upload_icon.svg") ?>
                </div>
            </li>
            <li onclick="searchAction()">
                <div class="navsearch" >
                    <?= file_get_contents($lochead . "miscellaneous/assets/search_icon_nofill.svg") ?>
                    <?= file_get_contents($lochead . "miscellaneous/assets/search_icon.svg") ?>
                </div>
            </li>
            <li onclick="profileAction()">
                <div class="navprofile" style="border-color: rgba(0,0,0,0);transform: scale(0.9);" >
                    <img src="<?= $lochead . "miscellaneous/assets/profiles/" . $_SESSION['username'] . "." . $_SESSION['extension'] ?>"></img>
                </div>
            </li>
        </ul>
    </div>
</nav>
<input id="fileImageUploadRequest" type="file" accept="image/*" onchange="uploadCallback(this)"></input>
<?php } ?>
<!-- script public -->
<script>
    
    function sanitizeURL() {
      var url = new URL(window.location);
      history.pushState({}, "", window.location.href.split("?")[0]);
    }

    function convertDateToString(sqldate, year = true, separator = false) {
        let datestr = new Date(Date.parse(sqldate.replace(/[-]/g,'/')));
        let strtemp = "";
        let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        if(!separator){
            strtemp = months[datestr.getMonth()] + " " +datestr.getDate()
            if(year){
                strtemp += ", " + datestr.getFullYear();
            }
        }else{

            strtemp = days[datestr.getDay()] + ", "+ datestr.getDate() + " " + months[datestr.getMonth()] + " " + datestr.getFullYear();

        }
        return strtemp;
    }

    function convertDateToDuration(sqldate,shorter=true){
        let datenow = new Date();
        let datestr = new Date(Date.parse(sqldate.replace(/[-]/g,'/')));
        let datediff = Math.trunc((datenow - datestr)/1000);
        let retstr = "";
        if(!shorter){
            if(!datediff){retstr = "A few moments ago"}
            else if(datediff < 604800){
                if(datediff < 60){
                    //<1min (seconds ago)
                    let second = datediff;
                    retstr = second + " second";
                    if(second != 1){
                        retstr += "s";
                    }
                }else if(datediff < 3600){
                    //<1hour (minutes ago)
                    let minute = Math.trunc(datediff/60);
                    retstr = minute + " minute";
                    if(minute != 1){
                        retstr += "s";
                    }

                }else if(datediff < 86400){
                    //<1day (hours ago)
                    let hour = Math.trunc(datediff/3600);
                    retstr = hour + " hour";
                    if(hour != 1){
                        retstr += "s";
                    }
                }else{
                    //<7day (days ago)
                    let day = Math.trunc(datediff/86400);
                    retstr = day + " day";
                    if(day != 1){
                        retstr += "s";
                    }
                }
                retstr += " ago";
            }else{
            //max 1 mgg habis itu real date
                if(datenow.getFullYear() != datestr.getFullYear()){
                    retstr = convertDateToString(sqldate);
                }else{
                    retstr = convertDateToString(sqldate,false);
                }
            }
        }else{
            if(!datediff){retstr = "now"}
            else if(datediff < 60){
                //<1min (s)
                let second = datediff;
                retstr = second + "s";
            }else if(datediff < 3600){
                //<1hour (m)
                let minute = Math.trunc(datediff/60);
                retstr = minute + "m";
            }else if(datediff < 86400){
                //<1day (h)
                let hour = Math.trunc(datediff/3600);
                retstr = hour + "h";

            }else if(datediff < 604800){
                //<7day (d)
                let day = Math.trunc(datediff/86400);
                retstr = day + "d";
            }else{
                //(w)
                let week = Math.trunc(datediff/604800);
                retstr = week + "w";
            }
            
        }
        return retstr;
    }
</script>
<?php if (isset($_SESSION['username'])) { ?>
<!-- kalo udah login, include semua script -->
<script>
    let pageVar = "<?= $page ?>";
    let usernameVar = "<?= $_SESSION['username'] ?>";

    function setNavActive(page){ //animasi logo navbar
        $(".navlist .active").css("opacity",0);
        $(".nav"+page+" .active").css("opacity",1);
        $(".navprofile img").css("transform","scale(1)");
        $(".navprofile").css("transform","scale(1)");
        if(page=="profile"){
            $(".navprofile").css("transform","scale(0.9)");
            $(".navprofile").css("border-color","rgba(0,0,0,1)");
            $(".navprofile img").css("transform","scale(0.8)");
        }
        else{
            $(".navprofile").css("border-color","rgba(0,0,0,0)");
        }
    }

    function hoverProfile(stat){ //animasi logo profile
        if(stat){
            $(".profileprompt-profile-img").css("transform","translate(0,-50%) scale(1)");
            $(".profileprompt-profile-img").css("border-color","rgba(0,0,0,1)");
            $(".profileprompt-profile img").css("transform","scale(0.8)");
            $(".profileprompt-profile-text").css("letter-spacing","0.5px");
            $(".profileprompt-profile-text").css("font-weight","600");
        }
        else{
            $(".profileprompt-profile-img").css("transform","translate(0,-50%) scale(0.9)");
            $(".profileprompt-profile-img").css("border-color","rgba(0,0,0,0)");
            $(".profileprompt-profile img").css("transform","scale(1)");
            $(".profileprompt-profile-text").css("letter-spacing","0");
            $(".profileprompt-profile-text").css("font-weight","400");
        }
    }

    function hoverLogout(stat){ //animasi logo logout
        if(stat){
            $(".profileprompt-logout svg .arrow").css("transform","translate(90px,0)");
            $(".profileprompt-logout svg .arrow2").css("transform","translate(90px,0)");
            $(".profileprompt-logout-text").css("letter-spacing","0.5px");
            $(".profileprompt-logout-text").css("font-weight","600");
        }
        else{
            $(".profileprompt-logout svg .arrow").css("transform","translate(0, 0)");
            $(".profileprompt-logout svg .arrow2").css("transform","translate(0, 0)");
            $(".profileprompt-logout-text").css("letter-spacing","0");
            $(".profileprompt-logout-text").css("font-weight","400");
        }
    }

    function homeAction() { //home navbar
        setNavActive("home");
        window.location.href = "<?= $lochead ?>";
    }

    function notifAction() { //notif navbar
        setNavActive("notif");
        //get notif & push all read ajax
        $.dialog({
            title: 'Notifications',
            columnClass: 'appwidthnorm jqueryconf-pop-setwidth col',
            content:
            `
            <style>
                .notify-base{
                    width: 100%;
                    height: 100%;
                    overflow: hidden;
                    position: relative;
                }

                .notify-searchbar{
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

                .notify-searchbar input{
                    font-size: 14px;
                    width: 97%;
                    margin: 0 1.5%;
                    outline: 0px;
                }

                .notify-container{
                    width: 100%;
                    height: auto;
                    overflow: auto;
                }
                .messagebox-conversations-content{
                    height: calc(100% - 54px);
                    width: 100%;
                    overflow-y: auto;
                }
            
                .notify-container-tabs{
                    width: 100%;
                    height: 60px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    position: relative;
                    overflow: hidden;
                    padding-left: 5px;
                }
            
                .notify-container-tabs:hover{
                    background: rgba(245,245,245,0.5);
                }

                .notify-container-tabs-header{
                    float: left;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .notify-container-tabs-header-img{
                    float: left;
                    display: grid;
                    place-items: center;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .notify-container-tabs-header-img img{
                    width: 45px;
                    height: 45px!important;
                    border-radius: 50%;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .notify-container-tabs-header-text{
                    float: left;
                    padding-left: 15px;
                    text-align: left;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .notify-container-tabs-header-text-username{
                    float: left;
                    font-size: 14px;
                    font-weight: 500;
                    color: black;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                    margin-right: 3px;
                }
            
                .notify-container-tabs-header-text-description{
                    float: left;
                    font-size: 14px;
                    color: black;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }

                .notify-container-tabs-header-text-time{
                    float: left;
                    font-size: 14px;
                    color: #b9b9b9;
                    padding-left: 4px;
                }

                .notify-container-tabs-header-follow{
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

                .notify-container-tabs-header-following{
                    height: 25px;
                    padding: 0 12px;
                    border-style: solid;
                    border-color: #dbdbdb;
                    border-width: 2px;
                    border-radius: 5px;
                    font-size: 14px;
                    font-weight: 500;
                    color: black;
                    background: white;
                    display: grid;
                    place-items: center;
                    position: absolute;
                    right: 5px;
                    cursor: pointer;
                }

                .notify-container-tabs-header-postimg{
                    height: 45px;
                    width: 69px;
                    padding: 0 12px;
                    display: grid;
                    place-items: center;
                    position: absolute;
                    right: 5px;
                    cursor: pointer;
                    overflow: hidden;
                }

                .notify-container-tabs-header-postimg img{
                    width: 100%;
                    border-radius: 5px;
                    height: 100%!important;
                    object-fit: cover;
                }

                @media (max-width: 576px) {
                    .notify-container-tabs-header-text-username{
                        float: none;
                    }

                    .notify-container-tabs-header-img img{
                        width: 40px;
                        height: 40px!important;
                        border-radius: 50%;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                    }

                    .notify-container-tabs-header-postimg{
                        height: 40px;
                        width: 64px;    
                    }

                    .notify-container-tabs-header-text{
                        padding-left: 10px;
                    }
                }

                @media (max-width: 350px) {
                    .notify-container-tabs-header-follow,.notify-container-tabs-header-following,.notify-container-tabs-header-postimg{
                        display: none!important;
                    }
                }
            
            </style>
            <div class="notify-base">
                <div class="notify-container">


                    


                </div>
            </div>
            <script>
                function createActionObject(JSONactiondata){
                    let requiredbaseaction = 
                    \`
                    <div id="user_%/username/%" class="notify-container-tabs" data-actionid="%/actionid/%" %/detailid/% data-actiontype="%/actiontype/%" data-userid="%/username/%" onclick="accessAction(this)">
                        <div class="notify-container-tabs-header" data-userid="%/username/%" onclick="window.location.href='<?= $lochead ?>'+$(this).data('userid')">
                            <div class="notify-container-tabs-header-img">
                                <img src="<?= $lochead ?>miscellaneous/assets/profiles/%/username/%.%/userext/%"></img>
                            </div>
                            <div class="notify-container-tabs-header-text">
                                <div class="notify-container-tabs-header-text-username">
                                    <a href='<?= $lochead ?>%/username/%'>%/username/% </a>
                                </div>
                                <div class="notify-container-tabs-header-text-description">
                                    %/descstring/%
                                    
                                    
                                </div>
                                <div class="notify-container-tabs-header-text-time" title=%/datetimestr/%>
                                    %/datetime/%
                                </div>
                            </div>


                        </div>
                            %/detailarea/%
                    </div>
                    \`;

                    let optionallikecomment = 
                    \`
                    <div class="notify-container-tabs-header-postimg" data-postid="%/postid/%" onclick="window.location.href='<?= $lochead ?>post/'+$(this).data('postid')">
                        <img src="<?= $lochead ?>miscellaneous/assets/posts/%/postid/%_small.%/postext/%"></img>
                    </div>
                    \`;

                    let optionalfollow =
                    \`
                    <div class="notify-container-tabs-header-follow %/followparam/%" data-userid="%/username/%" onclick="followRequest(this)">
                        Follow
                    </div>
                    <div class="notify-container-tabs-header-following %/followingparam/%" data-userid="%/username/%" onclick="unfollowRequest(this)">
                        Following
                    </div>
                    \`;

                    let actionid = JSONactiondata.actionid;
                    let actiontype = JSONactiondata.actiontype;
                    let actiontime = JSONactiondata.actiontime;
                    let datetime =  
                    let datetimestr 
                    let username = "";
                    let extension = "";
                    let detailarea = "";
                    let descstring = "";
                    let detailid = "";

                    if(actiontype == "like"){
                        descstring = "liked your post.";
                        detailid = 'data-likeid="'+JSONactiondata.likeid+'" data-postid="'+JSONactiondata.likepostid+'"';
                        username = JSONactiondata.likeuserid;
                        extension = JSONactiondata.likeuserext;
                        detailarea = optionallikecomment.replaceAll("%/postid/%", JSONactiondata.likepostid);
                        detailarea = detailarea.replaceAll("%/postext/%", JSONactiondata.likepostext);

                    }else if(actiontype == "comment"){
                        descstring = "commented on your post.";
                        detailid = 'data-commentid="'+JSONactiondata.commentid+'" data-postid="'+JSONactiondata.commentpostid+'"';
                        username = JSONactiondata.commentuserid;
                        extension = JSONactiondata.commentuserext;
                        detailarea = optionallikecomment.replaceAll("%/postid/%", JSONactiondata.commentpostid);
                        detailarea = detailarea.replaceAll("%/postext/%", JSONactiondata.commentpostext);


                    }else if(actiontype == "follow"){
                        descstring = "started following you.";
                        username = JSONactiondata.followuserid;
                        extension = JSONactiondata.followuserext;
                        detailarea = optionalfollow.replaceAll("%/username/%", JSONactiondata.followuserid);

                        let followparam = "";
                        let followingparam = "d-none";
                        if(JSONactiondata.followstatus == 1){
                            followparam = "d-none";
                            followingparam = "";
                        }

                        detailarea = optionalfollow.replaceAll("%/followparam/%", followparam);
                        detailarea = optionalfollow.replaceAll("%/followingparam/%", followingparam);


                    }

                    let actionBuildStr = requiredbaseaction.replaceAll("%/detailarea/%", detailarea);
                    actionBuildStr = actionBuildStr.replaceAll("%/descstring/%", descstring);
                    actionBuildStr = actionBuildStr.replaceAll("%/datetime/%", datetime);
                    actionBuildStr = actionBuildStr.replaceAll("%/datetimestr/%", datetimestr);
                    actionBuildStr = actionBuildStr.replaceAll("%/actionid/%", actionid);
                    actionBuildStr = actionBuildStr.replaceAll("%/actiontype/%", actiontype);
                    actionBuildStr = actionBuildStr.replaceAll("%/userext/%", extension);
                    actionBuildStr = actionBuildStr.replaceAll("%/username/%", username);
                    actionBuildStr = actionBuildStr.replaceAll("%/detailid/%", detailid);

                    let jQueryDOMobj = $.parseHTML(actionBuildStr);
                    return jQueryDOMobj;
                }

                var bottomActionID = 0;

                function fetchActions(){
                    $.ajax({
                        url: "<?= $lochead ?>miscellaneous/phps/services/action.php",
                        data: {
                            requestmode: "fetch",
                        },
                        method: "POST",
                        success: (data)=>{
                            $(".notify-container").html("");
                            bottomActionID = data.bottomactionid;
                            data.actions.forEach((actionJSON)=>{
                                let actionDOMObj = createActionObject(actionJSON);
                                $(".notify-container").append(actionDOMObj);
                            });
                        },
                        error: ()=>{
                        }           
                    });
                }

                function followRequest(obj){
                    window.event.stopImmediatePropagation();
                    let username = $(obj).data("userid");
                    $.ajax({
                        url:"<?= $lochead ?>miscellaneous/phps/services/follow.php",
                        data:{
                            requestmode:"add",
                            userid: username
                        },
                        method: "POST",
                        success:(data)=>{
                            if(data.errorcode.followstatus != 1){
                                $(obj).addClass("d-none");
                                $(".notify-container-tabs-header-following").removeClass("d-none");
                                if(pageVar == "profile"){
                                    $(".profile-header-container-top-detail-follower-inner-number").html(data.followcount);
                                    $(".profile-header-container-actions-follow").addClass("d-none");
                                    $(".profile-header-container-actions-following").removeClass("d-none");
                                }
                            }
                        },
                        error:()=>{}
                    });
                }
                function unfollowRequest(obj){
                    window.event.stopImmediatePropagation();
                    let username = $(obj).data("userid");
                    $.ajax({
                        url:"<?= $lochead ?>miscellaneous/phps/services/follow.php",
                        data:{
                            requestmode:"remove",
                            userid: username
                        },
                        method: "POST",
                        success:(data)=>{
                            if(data.errorcode.followstatus != 1){
                                $(obj).addClass("d-none");
                                $(".notify-container-tabs-header-follow").removeClass("d-none");
                                if(pageVar == "profile"){
                                    $(".profile-header-container-top-detail-follower-inner-number").html(data.followcount);
                                    $(".profile-header-container-actions-following").addClass("d-none");
                                    $(".profile-header-container-actions-follow").removeClass("d-none");
                                }
                            }
                        },
                        error:()=>{}
                    });
                }

                fetchActions();

                function accessAction(obj){
                    let actionType = $(obj).data("actiontype");
                    if (actionType == "like"){
                        window.location.href = "<?= $lochead ?>post/"+$(obj).data("postid");
                    }else if(actionType == "comment"){
                        window.location.href = "<?= $lochead ?>post/"+$(obj).data("postid");
                    }else if(actionType == "follow"){
                        window.location.href = "<?= $lochead ?>"+$(obj).data("userid");
                    }
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
                setNavActive(pageVar);
            }
        });
    }

    function uploadAction() { //upload navbar
        $("#fileImageUploadRequest").trigger('click');
    }

    function uploadCallback(obj){
        if(obj.files.length != 0){
            setNavActive("upload");
            let verify = obj.files[0].name.split(".");
            let fileex = verify[verify.length-1].toLowerCase();
            if(fileex && (fileex == "jpg"||fileex == "jpeg" || fileex == "png" || fileex == "gif")){
                $.confirm({
                    title: 'Post a Picture',
                    closeIcon: true,
                    columnClass: 'jqueryconf-pop-setwidth col',
                    content: 
                    `
                    <style>
                    .upload-base{
                        width: 100%;
                        max-height: 100%;
                        font-size: 30px;
                        position: relative;
                    }
                    .upload-content{
                        position: relative;
                        width: 100%;
                    }
                    .upload-content img{
                        width: 100%;
                        transition-duration: 0.4s;
                        transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                        transition-property: all;
                        border-radius: 10px;
                    }
                    .upload-content-caption{
                        margin-top: 20px;
                        height: auto;
                        width: 100%;
                        position: relative;
                        overflow: hidden;
                    }
                    .upload-content-caption-textarea{
                        width: 100%;
                        font-size: 14px;
                        padding: 5px;
                        min-height: 30px;
                        height: 30px;
                        padding-bottom: 0px;
                        border-style: solid;
                        border-color: #dbdbdb;
                        border-bottom-width: 1px;
                        outline: 0px;
                        resize: none;
                    }

                    </style>
                    <div class="upload-base">
                        <div class="upload-content">
                            <img src="">
                            <div class="upload-content-caption">
                                <textarea class="upload-content-caption-textarea" oninput="checkCaptionArea(this)" placeholder="Add a caption..."></textarea>
                            </div>
                        </div>
                    </div>
                    <script>
                        function checkCaptionArea(obj){
                            obj.style.height = 0;
                            obj.style.height =( obj.scrollHeight + 5 )+ 'px';
                            $(".jconfirm-content").scrollTop($(".jconfirm-content")[0].scrollHeight);
                        };
                        document.querySelector(".upload-content img").src = URL.createObjectURL(document.querySelector("#fileImageUploadRequest").files[0]);
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
                        setNavActive(pageVar);
                        obj.value="";
                    },
                    buttons:{
                        post: {
                            text: 'Post',
                            btnClass: 'btn-primary jcustom-button',
                            action: function(){
                                let formData = new FormData();
                                let strCaption = this.$content.find('.upload-content-caption-textarea').val().trim();
                                formData.append("caption", strCaption);
                                formData.append("file", obj.files[0]);

                                $.ajax({
                                    url: "<?= $lochead ?>miscellaneous/phps/services/uploadpost.php",
                                    data: formData,
                                    method: "POST",
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: (data)=>{
                                        if(data.errorcode.filetype != 0 && data.errorcode.filetypeprocessed != 0){
                                            //append new post if home, else go home
                                            setNavActive(pageVar);
                                            obj.value="";
                                            if(pageVar == "home"){
                                                const waitFetchNew = async()=>{
                                                    const postFetching = await fetchNewPostFetch();
                                                    window.scrollTo(0,$("#post_"+topPostID).offset().top-100);
                                                }
                                                waitFetchNew();
                                            }else{
                                                window.location.href = "<?= $lochead ?>";
                                            }
                                        }else{
                                            $.confirm({
                                                title: 'Unsupported Filetype',
                                                icon: 'far fa-times-circle',
                                                columnClass: 'jconfheightignore col',
                                                content: 'Only Supports JPG, PNG or GIF File',
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
                                                        text: 'Whoops..',
                                                        btnClass: 'btn-red',
                                                    }
                                                },
                                                onClose: ()=>{
                                                    setNavActive(pageVar);
                                                    obj.value="";
                                                }
                                            });
                                        }
                                    },
                                    error: ()=>{
                                        $.confirm({
                                            title: 'Upload Failed',
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
                                                setNavActive(pageVar);
                                                obj.value="";
                                            }
                                        });
                                    }
                                });
                            }
                        },
                    }
                });
            }else{
                $.confirm({
                    title: 'Unsupported Filetype',
                    icon: 'far fa-times-circle',
                    columnClass: 'jconfheightignore col',
                    content: 'Only Supports JPG, PNG or GIF File',
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
                            text: 'Whoops..',
                            btnClass: 'btn-red',
                        }
                    },
                    onClose: ()=>{
                        setNavActive(pageVar);
                        obj.value="";
                    }
                });
            }
        }

    }
    function searchAction() { //search navbar
        setNavActive("search");
        $.dialog({
            title: 'Search',
            columnClass: 'appwidthnorm jqueryconf-pop-setwidth col',
            content:
            `
            <style>
                .search-base{
                    width: 100%;
                    height: 100%;
                    overflow: hidden;
                    position: relative;
                }

                .search-searchbar{
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

                .search-searchbar input{
                    font-size: 14px;
                    width: 97%;
                    margin: 0 1.5%;
                    outline: 0px;
                }

                .search-container{
                    width: 100%;
                    height: auto;
                    overflow: auto;
                }
                .messagebox-conversations-content{
                    height: calc(100% - 54px);
                    width: 100%;
                    overflow-y: auto;
                }
            
                .search-container-tabs{
                    width: 100%;
                    height: 60px;
                    cursor: pointer;
                    display: block;
                    position: relative;
                    overflow: hidden;
                    padding-left: 5px;
                }
            
                .search-container-tabs:hover{
                    background: rgba(245,245,245,0.5);
                }

                .search-container-tabs-header{
                    float: left;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .search-container-tabs-header-img{
                    float: left;
                    display: grid;
                    place-items: center;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .search-container-tabs-header-img img{
                    width: 45px;
                    height: 45px!important;
                    border-radius: 50%;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .search-container-tabs-header-text{
                    float: left;
                    padding-left: 10px;
                    text-align: left;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .search-container-tabs-header-text-username{
                    font-size: 14px;
                    font-weight: 500;
                    color: black;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
                .search-container-tabs-header-text-realname{
                    font-size: 14px;
                    font-weight: 500;
                    color: #8e8e8e;
                    transition-duration: 0.4s;
                    transition-timing-function: cubic-bezier(0.36, 0.55, 0.19, 1);
                    transition-property: all;
                }
            
            </style>
            <div class="search-base">
                <div class="search-searchbar">
                    <input onkeyup="queryUsers(this)" placeholder="Search"></input>
                </div>
                <div class="search-container">
                    
                    
                </div>
            </div>
            <script>

                function createSearchObject(JSONsearchdata){

                    let requiredbasesearch = 
                    \`
                    <div id="user_%/username/%" class="search-container-tabs" data-username="%/username/%" onclick="window.location.href='./'+$(this).data('username')">
                        <div class="search-container-tabs-header">
                            <div class="search-container-tabs-header-img">
                                <img src="./miscellaneous/assets/profiles/%/username/%.%/profpicextension/%"></img>
                            </div>
                            <div class="search-container-tabs-header-text">
                                <div class="search-container-tabs-header-text-username">
                                    %/username/%
                                </div>
                                <div class="search-container-tabs-header-text-realname">
                                    %/realname/%
                                </div>
                            </div>
                        </div>
                    </div>
                    \`;

                    //base data
                    let username = JSONsearchdata.username;
                    let realname = JSONsearchdata.realname;
                    let profpicextension = JSONsearchdata.extension;

                    let searchBuildStr = requiredbasesearch.replaceAll("%/username/%",username);
                    searchBuildStr = searchBuildStr.replaceAll("%/realname/%",realname);
                    searchBuildStr = searchBuildStr.replaceAll("%/profpicextension/%",profpicextension);

                    let jQueryDOMobj = $.parseHTML(searchBuildStr);

                    return jQueryDOMobj;
                }

                

                function queryUsers(obj){
                    
                    $(".search-container").html("");
                    
                    if(obj.value && obj.value.trim().length > 0){
                        $(obj).attr("onkeyup","");
                        $.ajax({
                            url: "<?= $lochead ?>miscellaneous/phps/services/search.php",
                            data: {
                                requestmode: "fetch",
                                searchquery: obj.value.trim()
                            },
                            method: "POST",
                            success: (data)=>{
                                data.searches.forEach((search)=>{
                                    let searchDOMObj = createSearchObject(search);
                                    $(".search-container").append(searchDOMObj);
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
                setNavActive(pageVar);
            }
        });
    }
    function messagesAction() { //dm navbar
        setNavActive("messages");
        window.location.href = "<?= $lochead ?>messages";
    }
    function profileAction() {
        setNavActive("profile");
        $.dialog({
            title: '',
            columnClass: 'jconfirm-profileprompt col',
            content: `
            <div class="profileprompt-profile" onmouseenter="hoverProfile(true)" onmouseleave="hoverProfile(false)" onclick="window.location.href='<?= $lochead . $_SESSION['username'] ?>'">
                <div class="profileprompt-profile-img" style="border-color: rgba(0,0,0,0);" >
                    <img src="<?= $lochead . "miscellaneous/assets/profiles/" . $_SESSION['username'] . "." . $_SESSION['extension'] ?>"></img>
                </div>
                <div class="profileprompt-profile-text">
                    Your Profile
                </div>
            </div>
            <div class="profileprompt-logout" onmouseenter="hoverLogout(true)" onmouseleave="hoverLogout(false)" onclick="window.location.href='<?= $lochead .
              "miscellaneous/phps/services/logout.php" ?>'">
                <?= file_get_contents($lochead . "miscellaneous/assets/logout_icon.svg") ?>
                <div class="profileprompt-logout-text">
                    Log Out
                </div>
            </div>
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
                setNavActive(pageVar);
            }
        });
    }

    setNavActive(pageVar); //jalanin animasi navbar sesuai page

    //tooltip top navbar
    const topnavnotifbox = document.querySelector('.toplargenav .navnotif');
    const topnotiftooltip = document.querySelector('#topnotiftooltip');
    const topnotifpoper = Popper.createPopper(topnavnotifbox, topnotiftooltip, {
        placement: 'bottom',
        strategy: 'fixed',
            modifiers: [
            {
                name: 'computeStyles',
                options: {
                    gpuAcceleration: false,
                    adaptive: false,
                },
            },
        ],
    });

    //tooltip bottom navbar
    const botnavnotifbox = document.querySelector('.botnav .navnotif');
    const botnotiftooltip = document.querySelector('#botnotiftooltip');
    const botnotifpoper = Popper.createPopper(botnavnotifbox, botnotiftooltip, {
        placement: 'top',
        strategy: 'fixed',
        boundary: '.botnav .navnotif',
        rootBoundary: '.botnav .navnotif',
            modifiers: [
            {
                name: 'computeStyles',
                options: {
                    gpuAcceleration: false,
                    adaptive: false,
                },
            },
        ],
    });

    setTimeout(() => {
        $("#botnotiftooltip").css("opacity","1");
        $("#topnotiftooltip").css("opacity","1");
        $(".navmessage-badge").css("opacity","1");
    }, 100);

    //SSE buat notif dan message
        var lastMessageCount = 0;
        var lastReadCount = 0;
            const evtSource = new EventSource("<?= $lochead ?>miscellaneous/phps/services/sse.php");
            evtSource.addEventListener("notifupdate", function(event) {
                const data = JSON.parse(event.data);
                if(data.likecount != 0 || data.commentcount != 0 || data.followcount != 0){
                    $("#botnotiftooltip").removeClass("d-none");
                    $("#botnotiftooltip").addClass("d-block");
                    $("#botnotiftooltip").addClass("d-sm-none");
                    $("#topnotiftooltip").removeClass("d-none");
                    if(data.likecount != 0){
                        $(".notiflikes").html(data.likecount.toString());
                        $(".tooltiplikecontainer").removeClass("d-none");
                    }else{
                        $(".tooltiplikecontainer").addClass("d-none");
                    }

                    if(data.likecount != 0 && data.commentcount != 0){
                        $(".tooltiplikecommentspacer").removeClass("d-none");
                    }else{
                        $(".tooltiplikecommentspacer").addClass("d-none");
                    }

                    if(data.commentcount != 0){
                        $(".notifcomments").html(data.commentcount.toString());
                        $(".tooltipcommentcontainer").removeClass("d-none");
                    }else{
                        $(".tooltipcommentcontainer").addClass("d-none");
                    }

                    if(data.followcount != 0 && data.commentcount != 0){
                        $(".tooltipcommentfollowspacer").removeClass("d-none");
                    }else{
                        $(".tooltipcommentfollowspacer").addClass("d-none");
                    }

                    if(data.followcount != 0){
                        $(".notiffollowers").html(data.followcount.toString());
                        $(".tooltipfollowcontainer").removeClass("d-none");
                    }else{
                        $(".tooltipfollowcontainer").addClass("d-none");
                    }


                }else{
                    $("#botnotiftooltip").addClass("d-none");
                    $("#botnotiftooltip").removeClass("d-block");
                    $("#botnotiftooltip").removeClass("d-sm-none");
                    $("#topnotiftooltip").addClass("d-none");
                }
                botnotifpoper.update();
                topnotifpoper.update();

                if(data.messagecount != 0){
                    if(lastMessageCount != data.messagecount){
                        $(".navmessage-badge").removeClass("d-none");
                        $(".navmessage-badge").html(data.messagecount.toString());
                        if(pageVar == "messages"){
                            if(openedConversationID != undefined){
                                refreshMessage(openedConversationID);
                            }
                            fetchConversation();
                        }
                    }
                }else{
                    $(".navmessage-badge").addClass("d-none");
                }


                if(lastReadCount != data.readcount){
                    if(pageVar == "messages"){
                        setMessagesRead();
                    }
                }
                

                lastMessageCount = data.messagecount;
                lastReadCount = data.readcount;
            });
        
    
</script>
<?php }
?>
