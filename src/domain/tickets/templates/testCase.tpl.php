<?php
$ticketId = $this->get('ticketId');
$testCase = $this->get('testCase');
$statusLabels = $this->get('statusLabels');
$currentTestCaseId = $this->get('testCase');

?>
<?php if ($testCase->id) { ?>
    <h4 class="widgettitle title-light"><?= $this->__("headlines.edit_test_case"); ?> </h4>
<?php } else { ?>
    <h4 class="widgettitle title-light"><?= $this->__("headlines.new_test_case"); ?> </h4>
<?php } ?>

<form class="formModal" method="post" action="<?= BASE_URL ?>/tickets/testCase" style="min-width: 250px;">

    <label class="control-label"><?php echo $this->__('label.ticket_title'); ?>*</label>
    <input type="text" value="<?php $this->e($testCase->headline); ?>" name="headline"
           autocomplete="off"   style="width:99%;"/>

    <label><?php echo $this->__('label.todo_status'); ?></label>
    <select id="status-select" class="span11" name="status"
            data-placeholder="<?php echo $statusLabels[$this->e($testCase->status)]["name"]; ?>">

        <?php foreach ($statusLabels as $key => $label) { ?>
            <option value="<?php echo $key; ?>"
                <?php if ($testCase->status == $key) {
                    echo "selected='selected'";
                } ?>
            ><?php echo $this->escape($label["name"]); ?></option>
        <?php } ?>
    </select>

    <label class="control-label"><?php echo $this->__('label.editor'); ?></label>
    <select data-placeholder="<?php echo $this->__('label.filter_by_user'); ?>"
            name="editorId" class="user-select span11">
        <option value=""><?php echo $this->__('label.not_assigned_to_user'); ?></option>
        <?php foreach ($this->get('users') as $userRow) { ?>

            <?php echo "<option value='" . $userRow["id"] . "'";

            if ($testCase->editorId == $userRow["id"]) {
                echo " selected='selected' ";
            }

            echo ">" . $this->escape($userRow["firstname"] . " " . $userRow["lastname"]) . "</option>"; ?>

        <?php } ?>
    </select>
    <a href="javascript:void(0);"
       onclick="jQuery('select[name=editorId]').val('<?php echo $_SESSION['userdata']['id']; ?>')"><?php echo $this->__('label.assign_to_me'); ?></a>


    <div class="">
        <h4 class="widgettitle title-light"><span
                    class="iconfa iconfa-asterisk"></span><?php echo $this->__('label.description'); ?>
        </h4>
        <textarea name="description" rows="5" cols="80"
                  class="tinymce"><?php echo $testCase->description ?></textarea><br/>
        <input type="hidden" name="acceptanceCriteria" value=""/>
    </div>
    <div class="">
        <h4 class="widgettitle title-light"><span
                    class="iconfa iconfa-asterisk"></span><?php echo $this->__('label.precondition'); ?>
        </h4>
        <textarea name="precondition" rows="5" cols="80"
                  class="tinymce"><?php echo $testCase->precondition ?></textarea><br/>
        <input type="hidden" name="acceptanceCriteria" value=""/>
    </div>
    <div class="">
        <h4 class="widgettitle title-light"><span
                    class="iconfa iconfa-asterisk"></span><?php echo $this->__('label.postcondition'); ?>
        </h4>
        <textarea name="postcondition" rows="5" cols="80"
                  class="tinymce"><?php echo $testCase->postcondition ?></textarea><br/>
        <input type="hidden" name="acceptanceCriteria1" value=""/>
    </div>
    <div class="">
        <h4 class="widgettitle title-light"><span
                    class="iconfa iconfa-asterisk"></span><?php echo $this->__('label.steps'); ?>
        </h4>
        <textarea name="steps" rows="5" cols="80"
                  class="tinymce"><?php echo $testCase->steps ?></textarea><br/>
        <input type="hidden" name="acceptanceCriteria2" value=""/>
    </div>
    <div class="inline">
        <?php if (isset($ticketId)): ?>
            <input type="hidden" name="addTestCase" value="addTestCase"/>
        <?php else: ?>
            <input type="hidden" name="editTestCase" value="editTestCase"/>
            <input type="hidden" name="id" value="<?php echo $currentTestCaseId->id ?>"/>
        <?php endif; ?>
        <input type="hidden" name="ticketId" value="<?php echo $ticketId ?>"/>

        <input type="submit" name="save" value="<?= $this->__("buttons.save") ?>" class="btn btn-primary"
               id="saveTicket"/>
    </div>
    <!--bottom-->
    <div class="row-fluid col-md-6 align-right padding-top-sm" style="float: right">

            <?php if (isset($currentTestCaseId->id) && $currentTestCaseId->id != ''


            ) { ?>
                <a href="<?= BASE_URL ?>/tickets/delTestCase/<?php echo $currentTestCaseId->id; ?>"
                   class="delete formModal milestoneModal"><i
                            class="fa fa-trash"></i> <?= $this->__("buttons.delete"); ?></a>

            <?php } ?>
    </div>

</form>

<script type="text/javascript">
    jQuery(document).ready(function () {
        leantime.ticketsController.initModals();
        leantime.ticketsController.initTicketTabs();
        leantime.ticketsController.initTicketEditor();
        leantime.ticketsController.initTagsInput();
    })
    jQuery(window).load(function () {
        jQuery(window).resize();
    });
</script>
<div class="showDialogOnLoad" id="TestCaseModal" style="display:none;">

