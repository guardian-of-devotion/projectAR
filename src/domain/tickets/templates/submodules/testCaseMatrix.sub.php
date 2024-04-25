<?php
defined('RESTRICTED') or die('Restricted access');

$matrixElements = $this->get('matrixElements');
?>

<div class="">
    <h4 class="widgettitle title-light"><span
                class="iconfa iconfa-th-list"></span><?php echo $this->__('label.tickets_with_testcases'); ?>
    </h4>

    <table class='table table-bordered'>
        <tr>
            <th scope="rowgroup"><?php echo $this->__('label.ticket_id') ?></th>
            <th scope="row"><?php echo $this->__('label.test_case_id') ?></th>
            <th scope="row"><?php echo $this->__('label.name') ?></th>
            <th scope="row"><?php echo $this->__('label.description') ?></th>
            <th scope="row"><?php echo $this->__('label.precondition') ?></th>
            <th scope="row"><?php echo $this->__('label.postcondition') ?></th>
            <th scope="row"><?php echo $this->__('label.steps') ?></th>
        </tr>
        <?php foreach ($matrixElements as $id => $element): ?>
            <?php foreach ($element as $key => $testCase): ?>
                <tr>
                    <?php if ($key == 0):?>
                    <td rowspan="<?php echo count($element); ?>">
                        <a href="<?= BASE_URL ?>/tickets/showTicket/<?php echo $id ?>"><?php echo $id ?></a>
                    </td>
                    <?php endif;?>
                    <td>
                        <?php echo html_entity_decode($testCase['testcase_id']) ?>
                    </td>
                    <td >
                        <?php echo html_entity_decode($testCase['headline']) ?>
                    </td>
                    <td>
                        <?php echo html_entity_decode($testCase['description']) ?>
                    </td>
                    <td>
                        <?php echo html_entity_decode($testCase['precondition']) ?>
                    </td>
                    <td>
                        <?= html_entity_decode($testCase['postcondition']) ?>
                    </td>
                    <td>
                        <?= html_entity_decode($testCase['steps']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
</div>

<script type="text/javascript">

    leantime.ticketsController.initTicketTabs();
       jQuery(window).load(function () {
        jQuery(window).resize();
    });

</script>
