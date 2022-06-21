<?php
    
    /**
     * @author Regina Sharaeva
     */
    defined('RESTRICTED') or die('Restricted access');
    $ticketTemplate = $this->get('ticketTemplate');
    $ticketTypes = $this->get('ticketTypes');
    $efforts = $this->get('efforts');
    $priorities = $this->get('priorities');
    $markers = $this->get('markers');
?>

<div class="pageheader">
    <div class="pull-right padding-top">
        <a href="<?php echo $_SESSION['lastPage'] ?>" class="backBtn"><i class="far fa-arrow-alt-circle-left"></i> <?=$this->__("links.go_back") ?></a>
    </div>
                       
    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headlines.ticket_templates'); ?></h1>
    </div>
</div><!--pageheader-->
        
<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification() ?>

        <div class="tabbedwidget tab-primary ticketTabs" style="visibility:hidden;">

            <ul>
                <li><a href="#ticketTemplateDetails"><?php echo $this->__("tabs.ticketTemplateDetails") ?></a></li>
                <li><a href="#subtasks"><?php echo $this->__('tabs.subtasks') ?> (<?php echo $this->get('numSubTasks'); ?>)</a></li>
                <li><a href="#files"><?php echo $this->__("tabs.files") ?> (<?php echo $this->get('numFiles'); ?>)</a></li>
            </ul>

            <div id="ticketTemplateDetails">
                <form class="ticketModal" action="<?=BASE_URL ?>/ticketTemplates/editTicketTemplate/<?php echo $ticketTemplate->id ?>" method="post">
                    <?php $this->displaySubmodule('ticketTemplates-ticketTemplateDetails') ?>
                </form>
            </div>

            <div id="subtasks">
                <form method="post" action="#subtasks" class="ticketModal">
                    <?php $this->displaySubmodule('ticketTemplates-subTasks') ?>
                </form>
            </div>

            <div id="files">
                <form action='#files' method='POST' enctype="multipart/form-data" class="ticketModal">
                    <?php $this->displaySubmodule('ticketTemplates-attachments') ?>
                </form>
            </div>


                <form action="" method="post" class="stdform">

                    
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