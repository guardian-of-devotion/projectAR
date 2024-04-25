<?php

defined('RESTRICTED') or die('Restricted access');
$ticketsHeaders = $this->get('ticketHeaders');
$currentProjectId = $this->get('currentProjectId');
$matrixElements = $this->get('matrixElements');

?>

<div class="pageheader">

    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php $this->e($_SESSION['currentProjectClient']." // ". $_SESSION['currentProjectName']); ?></h5>
        <h1><?=$this->__("headlines.testCaseMatrix") ?></h1>
    </div>

</div><!--pageheader-->

<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification(); ?>

        <div class="tabbedwidget tab-primary ticketTabs" style="visibility:hidden;">

            <ul>
                <li><a href="#testCaseTicketsChoose"><?php echo $this->__("tabs.testCaseTicketsChoose") ?></a></li>
                <li><a href="#testCaseMatrix"><?php echo $this->__('tabs.testCaseMatrix') ?></a></li>
            </ul>

            <div id="testCaseTicketsChoose">
                <form class="ticketModal" action="<?=BASE_URL ?>/tickets/showMatrix/<?php echo $currentProjectId ?>" method="post">
                    <?php $this->displaySubmodule('tickets-testCaseTicketsChoose') ?>
                </form>
            </div>

            <div id="testCaseMatrix">
                    <?php $this->displaySubmodule('tickets-testCaseMatrix') ?>
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
