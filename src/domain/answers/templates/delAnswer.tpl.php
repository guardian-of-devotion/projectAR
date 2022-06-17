<?php
    defined('RESTRICTED') or die('Restricted access');
    $answer = $this->get("answer");
?>

<div class="pageheader">

    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headline.delete_answer'); ?><?= $this->e($answer->answerText);?></h1>
    </div>
</div><!--pageheader-->
        
<div class="maincontent">
    <div class="maincontentinner">

        <h4 class="widget widgettitle"><?php echo $this->__("subtitles.delete") ?></h4>
        <div class="widgetcontent">
            <form method="post">
                <p><?php echo $this->__('text.confirm_answer_deletion'); ?></p><br />
                <input type="submit" value="<?php echo $this->__('buttons.yes_delete'); ?>" name="del" class="button" />
                <a class="btn btn-primary" href="<?=BASE_URL ?>/answers/editAnswer/<?php echo $answer->id ?>"><?php echo $this->__('buttons.back'); ?></a>
            </form>
        </div>
    </div>
</div>