
<?php
    
    /**
     * @author Regina Sharaeva
     */
    $ticketTemplate = $this->get('ticketTemplate');
?>

<h4 class="widgettitle title-light"><span class="fa fa-list-ul"></span><?php echo $this->__('subtitles.subtasks'); ?></h4>
<p><?=$this->__('text.what_are_subtasks') ?><br /><br /></p>

<table cellpadding="0" cellspacing="0" border="0" class="allTickets table table-bordered"
    id="allTickets">
    
    <thead>
        <tr>
            <th><?php echo $this->__('label.headline'); ?></th>
            <th><?php echo $this->__('label.description'); ?></th>
            <th><?php echo $this->__('label.planned_hours'); ?></th>
            <th><?php echo $this->__('label.actions'); ?></th>
        </tr>
    </thead>
    <tbody>

    <?php
    $sumPlanHours = 0;
    $sumEstHours = 0;
    foreach($this->get('allSubTasks') as $subticket) {
        $sumPlanHours = $sumPlanHours + $subticket['planHours'];
        $sumEstHours = $sumEstHours + $subticket['hourRemaining'];
        ?>
        <tr>
            <form method="post" action="#subtasks">
                <td><input type="text" value="<?php $this->e($subticket['headline']); ?>" name="headline"/></td>
                <td><textarea  name="description" style="width:80%"><?php $this->e($subticket['description']) ?></textarea></td>
                <td><input type="text" value="<?php echo $this->e($subticket['planHours']); ?>" name="planHours" class="small-input"/></td>
                <td><input type="hidden" value="<?php echo $subticket['id']; ?>" name="subtaskId" />
                    <input type="submit" value="<?php echo $this->__('buttons.save'); ?>" name="subtaskSave"/>
                    <input type="submit" value="<?php echo $this->__('buttons.delete'); ?>" class="delete" name="subtaskDelete"/></td>
            </form>
            
        </tr>
    <?php } ?>
    <?php if(count($this->get('allSubTasks')) === 0) : ?>
        <tr>
            <td colspan="4"><?php echo $this->__('text.no_subtasks'); ?></td>
        </tr>
    <?php endif; ?>
    <tr><td colspan="4" style="background:#ccc;"><strong><?php echo $this->__('text.create_new_subtask'); ?></strong></td></tr>
    <tr>
        <form method="post" action="#subtasks">
            <td><input type="text" value="" name="headline"/></td>
            <td><textarea  name="description" style="width:80%"></textarea></td>
            <td><input type="text" value="" name="planHours" style="width:100px;"/></td>
            <td><input type="hidden" value="new" name="subtaskId" /><input type="submit" value="<?php echo $this->__('buttons.save'); ?>" name="subtaskSave"/></td>
        </form>
    </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><strong><?php echo $this->__('label.total_hours') ?></strong></td>
            <td><strong><?php echo $sumPlanHours; ?></strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
