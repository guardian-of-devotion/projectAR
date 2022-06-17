<?php

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
            </ul>

            <div id="ticketTemplateDetails">
                <form action="" method="post" class="stdform">

                    <div class="row-fluid">
                        <div class="span7">
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4 class="widgettitle title-light"><span class="iconfa iconfa-leaf"></span><?php echo $this->__('subtitle.general'); ?></h4>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.ticket_title'); ?>*</label>
                                        <div class="span6">
                                            <input type="text" value="<?php $this->e($ticketTemplate->headline); ?>" name="headline" autocomplete="off"  style="width:99%;"/>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.todo_type'); ?></label>
                                        <div class="span6">
                                            <select id='type' name='type' class="span11">
                                                <?php foreach ($ticketTypes as $types) {

                                                    echo "<option value='" . strtolower($types) . "'>" . $this->__("label.".strtolower($types)) . "</option>";

                                                } ?>
                                            </select><br/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.effort'); ?></label>
                                        <div class="span6">
                                            <select id='storypoints' name='storypoints' class="span11">
                                                <option value=""><?php echo $this->__('label.effort_not_defined'); ?></option>
                                                <?php foreach ($this->get('efforts') as $effortKey=>$effortValue) {
                                                    echo "<option value='" . $effortKey . "'>" . $effortValue . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.planned_hours'); ?></label>
                                        <div class="span6">
                                            <input type="text" value="<?php $this->e($ticketTemplate->planHours); ?>" name="planHours"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.priority'); ?></label>
                                        <div class="span6">
                                            <select id='priority' name='priority' class="span11">
                                                <option value=""><?php echo $this->__('label.priority_not_defined'); ?></option>
                                                <?php foreach ($this->get('priorities') as $priorityKey=>$priorityValue) {
                                                    echo "<option value='" . $priorityKey . "'>" . $priorityValue . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.marker'); ?></label>
                                        <div class="span6">
                                            <select id='marker' name='markers[]' class="span11" multiple="multiple">
                                                <option value=""><?php echo $this->__('label.marker_not_defined'); ?></option>
                                                <?php foreach ($this->get('markers') as $markerValue) {
                                                    echo "<option value='" . $markerValue->id . "'>" . $markerValue->name . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.tags'); ?></label>
                                        <div class="span6">
                                            <input type="text" value="<?php $this->e($ticketTemplate->tags); ?>" name="tags" id="tags"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4 class="widgettitle title-light"><span
                                                class="iconfa iconfa-asterisk"></span><?php echo $this->__('label.description'); ?>
                                    </h4>
                                    <textarea name="description" rows="10" cols="80" id="ticketDescription"
                                              class="tinymce"><?php echo $ticketTemplate->description ?></textarea><br/>
                                    <input type="hidden" name="acceptanceCriteria" value=""/>

                                </div>
                            </div>
                            <div class="row-fluid">
                                <input type="submit" name="saveTicketTemplate" value="<?php echo $this->__('buttons.save'); ?>"/>

                            </div>
                        </div>
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