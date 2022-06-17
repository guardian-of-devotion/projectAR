<!DOCTYPE html>
<html dir="<?php echo $language->__("language.direction"); ?>" lang="<?php echo $language->__("language.code"); ?>">
<head>
    <?php echo $frontController->includeAction('general.header'); ?>

    <link rel="stylesheet" href="<?=BASE_URL ?>/css/main.css?v=<?php echo $settings->appVersion; ?>"/>
    <link rel="stylesheet" href="<?=BASE_URL ?>/css/style.default.css?v=<?php echo $settings->appVersion; ?>" type="text/css" />
    <link rel="stylesheet" href="<?=BASE_URL ?>/css/style.custom.php?color=<?php echo htmlentities($_SESSION["companysettings.mainColor"]); ?>&v=<?php echo $settings->appVersion; ?>" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="<?=BASE_URL ?>/css/mdb/bootstrap.min.css">
    <link rel="stylesheet" href="<?=BASE_URL ?>/css/mdb/mdb.min.css">
    <link rel="stylesheet" href="<?=BASE_URL ?>/css/mdb/style.css">

    <script src="<?=BASE_URL?>/js/compiled-base-libs.min.js?v=<?php echo $settings->appVersion; ?>"></script>

    <style type="text/css">
        a:link {
          color: black;
        }
        a:visited {
          color: black;
        }
        a:hover {
          color: black;
        }
        a:active {
          color: black;
        }
    </style>

</head>

<script type="text/javascript">
    jQuery(document).ready(function(){
        
        if(jQuery('.login-alert .alert').text() != ''){
            jQuery('.login-alert').fadeIn();
        }

    });
</script>
</head>

<?php

    $redirectUrl = BASE_URL."/dashboard/show";

    if($_SERVER['REQUEST_URI'] != '' && isset($_GET['logout']) === false) {
        $redirectUrl = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    }

?>

<body style="height:100%; background-color: #eeeeee">

<div class="header z-depth-1" style="background-color: #ffffff;">

    <div class="logo" style="margin-left:0px; background-color: #ffffff">
        <a href="<?=BASE_URL ?>/" style="background-image:url(<?php echo htmlentities($_SESSION["companysettings.logoPath"]);?>)">&nbsp;</a>
    </div>

</div>


<div style="margin-left:0px; height:100%; width: 100%; background-color: #eeeeee; text-align: center;">
    <div class="row" style="margin: auto;">
        
        <div class="col-md col-sm"></div>

        <div class="col-md col-sm z-depth-3" style="margin-top: 5%; padding: 50px; background-color: #ffffff;">
            <h1><b><?php echo $language->__("headlines.login"); ?></b></h1>
            <form id="login" action="<?php echo $redirectUrl?>" method="post" style="margin-top: 50px">
                <input type="hidden" name="redirectUrl" value="<?php echo $redirectUrl; ?>" />
                <div class="inputwrapper login-alert">
                    <div class="alert alert-error"><?php echo $login->error;?></div>
                </div>
                <div class="">
                     <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo $language->__("input.placeholders.enter_email"); ?>" value=""/>
                </div>
                <div class="">
                    <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $language->__("input.placeholders.enter_password"); ?>" value=""/>
                </div>
                <div class="">
                    <button type="submit" name="login" class="btn btn-default"><?php echo $language->__("buttons.login"); ?></button>
                </div><br/><br/>
                <div class="">
                    <a href="<?=BASE_URL ?>/resetPassword" style="margin-top: 5%;"><?php echo $language->__("links.forgot_password"); ?></a>
                </div>

            </form>        
        </div>
        <div class="col-md col-sm"></div>
    </div>
</div>

</body>
</html>
