<?php

/**
 * @author Regina Sharaeva
 */
$note = $this->get('note');

?>

<div class="row-fluid">
    <div class="span7">
        <div class="row-fluid">
            <div class="span12">
                <h4 class="widgettitle title-light"><span class="iconfa iconfa-leaf"></span><?php echo $this->__('subtitle.general'); ?></h4>
                <div class="form-group">
                    <label class="span4 control-label"><?php echo $this->__('label.headline'); ?>*</label>
                    <div class="span6">
                        <input type="text" value="<?php $this->e($note->headline); ?>" name="headline" autocomplete="off"  style="width:99%;"/>

                    </div>
                </div>

                <div class="form-group">
                    <label class="span4 control-label"><?php echo $this->__('label.tags'); ?></label>
                    <div class="span6">
                        <input type="text" value="<?php $this->e($note->tags); ?>" name="tags" id="tags"/>
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
                          class="tinymce"><?php echo $note->description ?></textarea><br/>
                <input type="hidden" name="acceptanceCriteria" value=""/>

            </div>
        </div>
    </div>
    <div class="span5">
        <div class="row-fluid">
            <div class="span12">
                <h4 class="widgettitle title-light"><span
                        class="iconfa iconfa-group"></span><?php echo $this->__('subtitle.people'); ?></h4>

                <div class="form-group">
                    <label class="span4 control-label"><?php echo $this->__('label.author'); ?></label>
                    <div class="span6">
                        <input type="text" disabled="disabled"
                               value="<?php $this->e($note->authorFirstname); ?> <?php $this->e($note->authorLastname); ?>"/>
                    </div>
                </div>

            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <h4 class="widgettitle title-light"><span
                        class="iconfa iconfa-calendar"></span><?php echo $this->__('subtitles.dates'); ?></h4>
                <div class="form-group">
                    <label class="span4 control-label"><?php echo $this->__('label.create_date'); ?></label>
                    <div class="span6">

                        <input type="text" class="dates" id="submittedDate" disabled="disabled"
                               value="<?php echo $note->date; ?>" name="date"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row-fluid">
    <?php if (isset($note->id) && $note->id != '') : ?>
        <div class="pull-right padding-top">
            <?php echo $this->displayLink('notes.delNote', '<i class="fa fa-trash"></i> '.$this->__('links.delete_note'), array('id' => $note->id), array('class' => 'delete')) ?>
        </div>
    <?php endif; ?>

    <input type="submit" name="saveNote" value="<?php echo $this->__('buttons.save'); ?>"/>
    <input type="submit" name="saveAndCloseNote" value="<?php echo $this->__('buttons.save_and_close'); ?>"/>

</div>
