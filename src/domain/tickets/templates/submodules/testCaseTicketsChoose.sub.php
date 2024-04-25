<?php
defined('RESTRICTED') or die('Restricted access');

$ticketsHeaders = $this->get('ticketHeaders');
$currentProjectId = $this->get('currentProjectId');
?>


<div class="">
    <h4 class="widgettitle title-light"><span
                class="iconfa iconfa-th-list"></span><?php echo $this->__('label.tickets_with_testcases'); ?>
    </h4>

    <table class='table table-bordered form-group'>
        <colgroup>
            <col class="con0"/>
            <col class="con0"/>
            <col class="con0"/>
        </colgroup>
        <thead>
        <tr>
            <th width="15%"><?php echo $this->__('label.showInMatrix') ?></th>
            <th><?php echo $this->__('label.id') ?></th>
            <th><?php echo $this->__('label.name') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ticketsHeaders as $ticket): ?>
            <tr>
                <td>
                    <input type="checkbox" name="<?php echo($ticket['id']); ?>" <?php if ($ticket['is_in_matrix']) echo "checked"?>/>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/tickets/showTicket/<?php echo $ticket['id'] ?>"><?php printf($ticket['id']); ?>
                    </a>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/tickets/showTicket/<?php echo $ticket['id'] ?>"> <?php echo $ticket['headline'] ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <input type="submit" name="saveMatrix" value="<?php echo $this->__('buttons.save'); ?>"/>
</div>


