<?php
defined('RESTRICTED') or die('Restricted access');

$matrixElements = $this->get('matrixElements');
$statusLabels = $this->get('statusLabels');
$statusClass = null;
?>

<div class="">
    <h4 class="widgettitle title-light"><span
                class="iconfa iconfa-th-list"></span><?php echo $this->__('label.tickets_with_testcases'); ?>
    </h4>

    <table class='table table-bordered'>
        <tr>
            <th scope="rowgroup"><?php echo $this->__('label.ticket_id') ?></th>
            <th scope="rowgroup"><?php echo $this->__('label.ticket_status') ?></th>
            <th scope="row"><?php echo $this->__('label.test_case_id') ?></th>
            <th scope="row"><?php echo $this->__('label.test_case_status') ?></th>
            <th scope="row"><?php echo $this->__('label.name') ?></th>
            <th scope="row"><?php echo $this->__('label.description') ?></th>
            <th scope="row"><?php echo $this->__('label.precondition') ?></th>
            <th scope="row"><?php echo $this->__('label.postcondition') ?></th>
            <th scope="row"><?php echo $this->__('label.steps') ?></th>
        </tr>
        <?php foreach ($matrixElements as $id => $element): ?>
            <?php foreach ($element as $key => $testCase): ?>
                <tr>
                    <?php if ($key == 0): ?>
                        <td rowspan="<?php echo count($element); ?>">
                            <a href="<?= BASE_URL ?>/tickets/showTicket/<?php echo $id ?>"><?php echo $id ?></a>
                        </td>

                    <td style="font-weight: 600" rowspan="<?php echo count($element); ?>">
                        <?php echo html_entity_decode($statusLabels[$testCase['status']]["name"]) ?>
                    </td>
                    <?php endif; ?>
                    <td>
                        <?php echo html_entity_decode(array_key_exists('testcase_id', $testCase) ? $testCase['testcase_id'] : '') ?>
                    </td>

                    <?php if ($testCase['tcstatus'] == -3) {
                        $statusClass = 'red';
                    } elseif ($testCase['tcstatus'] == -2) {
                        $statusClass = 'green';
                    } else {
                        $statusClass = null;
                    }
                    ?>
                    <td class="<?php echo $statusClass ?: ""; ?>" style="font-weight: 600">
                        <?php if ($testCase['tcstatus']) {
                            echo html_entity_decode($statusLabels[$testCase['tcstatus']]["name"]);
                        } else echo ''
                        ?>

                    </td>
                    <td>
                        <?php echo html_entity_decode(array_key_exists('headline', $testCase) ? $testCase['headline'] : '') ?>
                    </td>
                    <td>
                        <?php echo html_entity_decode(array_key_exists('description', $testCase) ? $testCase['description'] : '') ?>
                    </td>
                    <td>
                        <?php echo html_entity_decode(array_key_exists('precondition', $testCase) ? $testCase['precondition'] : '') ?>
                    </td>
                    <td>
                        <?= html_entity_decode(array_key_exists('postcondition', $testCase) ? $testCase['postcondition'] : '') ?>
                    </td>
                    <td>
                        <?= html_entity_decode(array_key_exists('steps', $testCase) ? $testCase['steps'] : '') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
</div>

<style>
    .red {
        color: red;
    }

    .green {
        color: lime; /* Зеленый цвет */
    }
</style>
<script type="text/javascript">

    leantime.ticketsController.initTicketTabs();
    jQuery(window).load(function () {
        jQuery(window).resize();
    });

</script>
