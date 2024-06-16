<?php
defined('RESTRICTED') or die('Restricted access');

$matrixStatistics = $this->get('matrixStatistics');
?>

<div class="">
    <h4 class="widgettitle title-light"><span
                class="iconfa iconfa-th-list"></span><?php echo $this->__('label.matrix_statistics'); ?>
    </h4>
    <table class='table table-bordered'>
        <tr>
            <th scope="rowgroup"><?php echo $this->__('label.matrix_statistics_param') ?></th>
            <th scope="row"><?php echo $this->__('label.matrix_statistics_result') ?></th>
        </tr>
        <?php foreach ($matrixStatistics as $statistic): ?>
            <tr>
                <td style="font-weight: 600">
                    <?php $label = "label." . $statistic['task_type']
                    ?>
                    <?php echo $this->__($label) ?>
                </td>
                <td>
                    <?php echo $statistic['total_records'] ?>
                </td>

            </tr>
        <?php endforeach; ?>
    </table>
</div>

<script type="text/javascript">

    leantime.ticketsController.initTicketTabs();
    jQuery(window).load(function () {
        jQuery(window).resize();
    });

</script>
