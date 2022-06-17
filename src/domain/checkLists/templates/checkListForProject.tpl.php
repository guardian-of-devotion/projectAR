<?php

    defined('RESTRICTED') or die('Restricted access');
    $checkList = $this->get('checkList');
    $questions = $this->get('questions');
    $answers = $this->get('answers');

?>

<div class="pageheader">            
    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $checkList->headline; ?></h1>
    </div>
</div><!--pageheader-->
        
<div class="maincontent">
    <div class="maincontentinner">
        <p><?=$this->e($checkList->description); ?></p><br/>
        <div class="tabbedwidget tab-primary ticketTabs" style="visibility:hidden;">

            <ul>
                <li><a href="#projectDetails"><?php echo $this->__("tabs.projectdetails") ?></a></li>
            </ul>

            <div id="projectDetails">
                <form action="" method="post" class="stdform">

                    <?php foreach ($questions as $key => $question) { if (isset($answers[$question->id])) { ?>

                    <div class="row-fluid">
                        <div class="span7">
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4 class="widgettitle title-light"><span class="iconfa iconfa-leaf"></span><?=$this->e($question->headline); ?></h4>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?=$this->e($question->questionText); ?></label>
                                        <div class="span6">
                                            <select name='<?=$this->e($question->id); ?>[]' class="span11" multiple="multiple">
                                                <option value=""><?php echo $this->__('label.answer_not_defined'); ?></option>
                                                <?php foreach($answers[$question->id] as $answer) { 
                                                    echo "<option value='" . $answer->id . "'>" . $answer->answerText . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }} ?>
                    <div class="row-fluid">
                        <input type="submit" value="<?php echo $this->__('buttons.save'); ?>"/>

                    </div>
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
