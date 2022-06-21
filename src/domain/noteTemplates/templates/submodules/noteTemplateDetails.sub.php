<?php

/**
 * @author Regina Sharaeva
 */
$noteTemplate = $this->get('noteTemplate');

?>

<div class="row-fluid">
    <div class="span7">
        <div class="row-fluid">
            <div class="span12">
                <h4 class="widgettitle title-light"><span class="iconfa iconfa-leaf"></span><?php echo $this->__('subtitle.general'); ?></h4>
                <div class="form-group">
                    <label class="span4 control-label"><?php echo $this->__('label.ticket_title'); ?>*</label>
                    <div class="span6">
                        <input type="text" value="<?php $this->e($noteTemplate->headline); ?>" name="headline" autocomplete="off"  style="width:99%;"/>

                    </div>
                </div>

                <div class="form-group">
                    <label class="span4 control-label"><?php echo $this->__('label.tags'); ?></label>
                    <div class="span6">
                        <input type="text" value="<?php $this->e($noteTemplate->tags); ?>" name="tags" id="tags"/>
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
                          class="tinymce"><?php echo $noteTemplate->description ?></textarea><br/>
                <input type="hidden" name="acceptanceCriteria" value=""/>

            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <?php if (isset($noteTemplate->id) && $noteTemplate->id != '') : ?>
        <div class="pull-right padding-top">
            <?php echo $this->displayLink('notes.delNote', '<i class="fa fa-trash"></i> '.$this->__('links.delete_note'), array('id' => $noteTemplate->id), array('class' => 'delete')) ?>
        </div>
    <?php endif; ?>

    <input type="submit" name="saveNote" value="<?php echo $this->__('buttons.save'); ?>"/>

</div>
