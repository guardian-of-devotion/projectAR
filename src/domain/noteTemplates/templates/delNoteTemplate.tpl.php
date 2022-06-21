<?php

/**
 * @author Regina Sharaeva
 */
defined('RESTRICTED') or die('Restricted access');
$noteTemplate = $this->get("noteTemplate");
?>

<div class="pageheader">

    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headline.delete_note_template'); ?><?= $this->e($note->headline);?></h1>
    </div>
</div><!--pageheader-->

<div class="maincontent">
    <div class="maincontentinner">

        <h4 class="widget widgettitle"><?php echo $this->__("subtitles.delete") ?></h4>
        <div class="widgetcontent">
            <form method="post">
                <p><?php echo $this->__('text.confirm_note_template_deletion'); ?></p><br />
                <input type="submit" value="<?php echo $this->__('buttons.yes_delete'); ?>" name="del" class="button" />
                <a class="btn btn-primary" href="<?=BASE_URL ?>/noteTemplate/editNoteTemplate/<?php echo $noteTemplate->id ?>"><?php echo $this->__('buttons.back'); ?></a>
            </form>
        </div>
    </div>
</div>