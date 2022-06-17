<!DOCTYPE html>
<html dir="<?php echo $language->__("language.direction"); ?>" lang="<?php echo $language->__("language.code"); ?>">
<head>
    <title><?php echo $_SESSION["companysettings.sitename"]; ?></title>

    <?php echo $frontController->includeAction('general.header'); ?>

    <link rel="stylesheet" href="<?=BASE_URL?>/css/main.css?v=<?php echo $settings->appVersion; ?>"/>
    <link rel="stylesheet" href="<?=BASE_URL?>/css/style.default.css?v=<?php echo $settings->appVersion; ?>" type="text/css" />
    <link rel="stylesheet" href="<?=BASE_URL?>/css/style.custom.php?color=<?php echo htmlentities($_SESSION["companysettings.mainColor"]); ?>&v=<?php echo $settings->appVersion; ?>" type="text/css" />

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
        
        if(jQuery('.login-alert .alert-error').text() != ''){
            jQuery('.login-error').fadeIn();
        }
        
        if(jQuery('.login-alert .alert-success').text() != ''){
            jQuery('.login-success').fadeIn();
        }

    });
</script>
</head>

<body style="height:100%;">

<div class="header z-depth-1" style="background-color: #ffffff;">

    <div class="logo" style="margin-left:0px; background-color: #ffffff">
        <a href="<?=BASE_URL ?>/" style="background-image:url(<?php echo htmlentities($_SESSION["companysettings.logoPath"]);?>)">&nbsp;</a>
    </div>

</div>

<div style="margin-left:0px; height:100%; width: 100%; background-color: #eeeeee; text-align: center;">
    <div class="row" style="margin: auto;">
        
        <div class="col-md col-sm"></div>

        <div class="col-md col-sm z-depth-3" style="margin-top: 5%; padding: 50px; background-color: #ffffff;">
            <h1><b><?php echo $language->__("headlines.reset_password"); ?></b></h1>
            <form id="resetPassword" action="" method="post">        
                <div class="inputwrapper login-alert login-error">
                    <div class="alert alert-error"><?php echo $login->error;?></div>
                </div>
                <div class="inputwrapper login-alert login-success">
                    <div class="alert alert-success"><?php echo $login->success;?></div>
                </div>

                <?php
                    if((isset($_GET["hash"]) === true && $login->validateResetLink()) || $login->resetInProgress === true) { ?>
                        <p><?php echo $language->__("text.enter_new_password"); ?><br /><br /></p>
                        <div class="">
                            <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $language->__("input.placeholders.enter_new_password"); ?>" />
                        </div>
                        <div class=" ">
                             <input type="password" name="password2" id="password2" class="form-control" placeholder="<?php echo $language->__("input.placeholders.confirm_password"); ?>" />
                        </div>

                        <div class="">
                            <input type="submit" name="resetPassword" value="<?php echo $language->__("buttons.reset_password"); ?>" />
                        </div>
                        <div class="">
                            <a href="<?=BASE_URL ?>/" style="margin-top:10px;"><?php echo $language->__("links.back_to_login"); ?></a>
                        </div>
                <?php
                     }else{
                ?>
                        <p><?php echo $language->__("text.enter_email_address_to_reset"); ?><br /><br /></p>
                        <div class="">
                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo $language->__("input.placeholders.enter_email"); ?>" />
                        </div>

                        <div class="">
                            <button type="submit" name="resetPassword" class="btn btn-default"><?php echo $language->__("buttons.reset_password"); ?></button>
                        </div><br/><br/>
                        <div class="">
                            <a href="<?=BASE_URL ?>/" style="margin-top:10px;"><?php echo $language->__("links.back_to_login"); ?></a>
                        </div>
                <?php } ?>
            </form>        
        </div>
        <div class="col-md col-sm"></div>
    </div>
</div>

</body>
</html>
