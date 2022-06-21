<?php

/**
 * @author Regina Sharaeva
 */
defined('RESTRICTED') or die('Restricted access');
$noteTemplate = $this->get('noteTemplate');

?>
<div class="pageheader">

    <div class="pull-right padding-top">
        <a href="<?php echo $_SESSION['lastPage'] ?>" class="backBtn"><i class="far fa-arrow-alt-circle-left"></i> <?=$this->__("links.go_back") ?></a>
    </div>

    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?=$this->__("headlines.edit_note_template") ?></h1>
    </div>

</div>

<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification(); ?>

        <div class="tabbedwidget tab-primary ticketTabs" style="visibility:hidden;">

            <ul>
                <li><a href="#noteTemplateDetails"><?php echo $this->__("tabs.noteTemplateDetails") ?></a></li>
                <li><a href="#files"><?php echo $this->__("tabs.files") ?> (<?php echo $this->get('numFiles'); ?>)</a></li>
            </ul>

            <div id="noteTemplateDetails">
                <form class="ticketModal" action="<?=BASE_URL ?>/noteTemplates/editNoteTemplate/<?=$noteTemplate->id; ?>" method="post">
                    <?php $this->displaySubmodule('noteTemplates-noteTemplateDetails') ?>
                </form>
            </div>

            <div id="files">
                <form action='#files' method='POST' enctype="multipart/form-data" class="ticketModal">
                    <?php $this->displaySubmodule('noteTemplates-noteTemplateFiles') ?>
                </form>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">

    leantime.ticketsController.initTicketTabs();
    leantime.ticketsController.initTicketEditor();
    leantime.ticketsController.initTagsInput();

    jQuery(window).load(function () {
        jQuery(window).resize();
    });

</script>
