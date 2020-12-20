<!DOCTYPE html>
<html lang="en">
<head>
<?php $page = "404"; ?>
    <?php require_once '../miscellaneous/phps/addons/libraries.php'; ?>
    <?php require_once '../miscellaneous/phps/addons/metatags.php'; ?>
    <title>Vibin</title>

    <style>
        body{
            display: grid;
            height: 100vh;
            font-size: 15vmax;
            place-items: center;
            font-family: "Roboto"
        }
        svg{
            width: 10vmax;
        }
        .btn {
            font-size: 1.1vmax!important;
            padding: 2px 5px!important;
            background: none;
            color: grey;
            border-color: grey!important;
            border-width: 2px!important;
            border-radius: 1vmax!important;
        }
        .btn:hover{
            background: grey!important;
            color: white;
        }

        .btn:focus{
            background: grey!important;
            border-color: grey!important;
            color: white;
        }
        
        
        
    </style>

</head>

<body>
    404 :(
    <span style="font-size: 20px;">The page that you looking for does not exist</span>
    <button class="btn" onclick="window.location.href='../'">Where's mommy?</button>
    <?= file_get_contents($lochead . "miscellaneous/assets/vibin_logo.svg") ?>
</body>
</html>