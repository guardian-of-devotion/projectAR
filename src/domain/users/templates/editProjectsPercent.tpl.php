<?php
defined('RESTRICTED') or die('Restricted access');
$roles = $this->get('roles');
$userProjects = $this->get('userProjects');
?>

<div class="pageheader">

    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headlines.activity_percent'); ?></h1>
    </div>
</div><!--pageheader-->

<div class="maincontent">
    <div class="maincontentinner">
        <form action="" method="post" class="stdform">
            <?php echo $this->displayNotification() ?>

            <table class="table table-bordered" cellpadding="0" cellspacing="0" border="0" id="ProjectPercentActivity">
                <colgroup>
                    <col class="con1">
                    <col class="con0">
                    <col class="con1">
                </colgroup>
                <thead>
                <tr>
                    <th class='head1'><?php echo $this->__('label.project_name'); ?></th>
                    <th class='head0'><?php echo $this->__('label.ticketsAtUser'); ?></th>
                    <th class='head1'><?php echo $this->__('label.activityPercent'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($userProjects as $row): ?>
                    <tr>
                        <input type="hidden" name="<?=$_SESSION['formTokenName']?>" value="<?=$_SESSION['formTokenValue']?>" />
                        <td><?php echo $row['name']; ?></td>
                        <td><?= $row['ticketsAtUser']; ?></td>
                        <td>
                            <input type="text" value="<?php echo $row['activityPercent']; ?>" name="activityPercent[]"
                                   style="width:99%"/>
                        </td>
                        <input type="hidden" name="projectId[]" value="<?=$row['projectId']?>"  />
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="stdformbutton">
                <input type="submit" name="save" id="save"
                       value="<?php echo $this->__('buttons.save'); ?>" class="button"/>
            </div>
        </form>
    </div>
</div>

<!--<script type="text/javascript">-->
<!--    jQuery(document).ready(function() {-->
<!--            leantime.usersController.initUserTable();-->
<!--        }-->
<!--    );-->
<!---->
<!--</script>-->
