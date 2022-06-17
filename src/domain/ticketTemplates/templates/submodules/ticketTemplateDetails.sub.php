<?php
    $ticketTemplate = $this->get('ticketTemplate');
    $ticketTypes = $this->get('ticketTypes');
    $efforts = $this->get('efforts');
    $priorities = $this->get('priorities');
    $markers = $this->get('markers');
?>

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

                                echo "<option value='" . strtolower($types) . "' ";
                                 if(strtolower($types) == strtolower($ticketTemplate->type)) echo "selected='selected'";

                                echo ">" . $this->__("label.".strtolower($types)) . "</option>";

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
                                echo "<option value='" . $effortKey . "' ";
                                if ($effortKey == $ticketTemplate->storypoints) {
                                    echo "selected='selected'";
                                }
                                echo ">" . $effortValue . "</option>";
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
                                echo "<option value='" . $priorityKey . "' ";
                                if ($priorityKey == $ticketTemplate->priority) {
                                    echo "selected='selected'";
                                }
                                echo ">" . $priorityValue . "</option>";
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
                                echo "<option value='" . $markerValue->id . "' ";
                                if (in_array($markerValue->id, json_decode($ticketTemplate->markers))) {
                                    echo "selected='selected'";
                                }
                                echo ">" . $markerValue->name . "</option>";
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
    </div>
</div>
<div class="row-fluid">
    <?php if (isset($ticketTemplate->id) && $ticketTemplate->id != '') : ?>
        <div class="pull-right padding-top">
            <?php echo $this->displayLink('ticketTemplates.delTicketTemplate', '<i class="fa fa-trash"></i> '.$this->__('links.delete_todo'), array('id' => $ticketTemplate->id), array('class' => 'delete')) ?>
        </div>
    <?php endif; ?>

    <input type="submit" name="saveTicket" value="<?php echo $this->__('buttons.save'); ?>"/>

</div>