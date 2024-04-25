<?php
$testCases = $this->get('allTestCases');
$statusLabels = $this->get('statusLabels');
?>

<h4 class="widgettitle title-light"><span
            class="iconfa iconfa-book"></span><?php echo $this->__('subtitle.test_cases'); ?>
</h4>
<!--<p>--><?php //= $this->__('text.what_are_testcase') ?><!--<br/><br/></p>-->

<table cellpadding="0" cellspacing="0" border="0" class="allTickets table table-bordered"
       id="allTickets">

    <thead>
    <tr>
        <th width="20%"><?php echo $this->__('label.headline'); ?></th>
        <th width="64%"><?php echo $this->__('label.description'); ?></th>
        <th width="8%"><?php echo $this->__('label.todo_status'); ?></th>
        <th width="8%"><?php echo $this->__('label.actions'); ?></th>

    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($testCases as $testCase) {
        ?>
        <tr>
            <td><a href="<?= BASE_URL . '/tickets/testCase/' . $testCase['testcase_id'] ?>"
                   class="milestoneModal"><?php $this->e($testCase['headline']); ?> </a></td>
            <td><?php echo html_entity_decode($testCase['description']) ?></td>
            <td style="width:150px;"><select disabled class="span11 status-select" name="status" style="width:150px;"
                                             data-placeholder="">
                    <?php foreach ($statusLabels as $key => $label) { ?>
                        <option value="<?php echo $key; ?>"
                            <?php if ($testCase['status'] == $key) {
                                echo "selected='selected'";
                            } ?>
                        ><?php echo $this->escape($label["name"]); ?></option>
                    <?php } ?>
                </select>
            </td>
            <td><input type="hidden" value="<?php echo $testCase['testcase_id']; ?>" name="testCaseId" />
                <input type="submit" value="<?php echo $this->__('buttons.unrelate'); ?>" class="delete" name="testCaseUnrelate"/></td>
        </tr>
    <?php } ?>
    <?php if (count($testCases) === 0) : ?>
        <tr>
            <td colspan="6"><?php echo $this->__('text.no_test_cases'); ?></td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<form method="post" action="#testCases">
    <div>
        <label for="relatedTestCase"> <?php echo $this->__('text.relate_new_test_case'); ?></label><select
                name="relatedTestCase" id="relatedTestCase">
            <option value=""><?php echo $this->__('label.not_related'); ?></option>
            <?php foreach ($this->get('notRelatedTestCases') as $oneOftestCases) {
                echo "<option value='" . $oneOftestCases['id'] . "'";
                echo "> " . $oneOftestCases['id'] . ': ' . $oneOftestCases['headline'] . "</option>";
            } ?>
        </select>
        <input type="submit"
               value="<?php echo $this->__('buttons.relate_test_case'); ?>"
               name="testCaseRelate"/>
    </div>


</form>