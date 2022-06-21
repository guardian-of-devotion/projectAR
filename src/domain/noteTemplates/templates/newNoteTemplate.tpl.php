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
        <h1><?=$this->__("headlines.add_note_template") ?></h1>
    </div>

</div>

<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification(); ?>

        <div class="tabbedwidget tab-primary ticketTabs" style="visibility:hidden;">

            <ul>
                <li><a href="#noteTemplateDetails"><?php echo $this->__("tabs.noteTemplateDetails") ?></a></li>
            </ul>

            <div id="noteTemplateDetails">
                <form action="" method="post" class="stdform">
                    <div class="row-fluid">
                        <div class="span7">
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4 class="widgettitle title-light"><span class="iconfa iconfa-leaf"></span><?php echo $this->__('subtitle.general'); ?></h4>
                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.ticket_title'); ?>*</label>
                                        <div class="span6">
                                            <input type="text" value="" name="headline" autocomplete="off"  style="width:99%;"/>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="span4 control-label"><?php echo $this->__('label.tags'); ?></label>
                                        <div class="span6">
                                            <input type="text" value="" name="tags" id="tags"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4 class="widgettitle title-light"><span
                                            class="iconfa iconfa-asterisk"></span><?php echo $this->__('label.description'); ?>
                                    </h4>
                                    <textarea name="description" rows="10" cols="80" id="ticketDescription"
                                              class="tinymce"></textarea><br/>
                                    <input type="hidden" name="acceptanceCriteria" value=""/>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <input type="submit" name="saveNoteTemplate" value="<?php echo $this->__('buttons.save'); ?>"/>
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
