<?php

/**
 * @author Regina Sharaeva
 */
defined('RESTRICTED') or die('Restricted access');
$checkLists = $this->get("allCheckLists");
?>
<div class="pageheader">

    <div class="pageicon"><span class="fa fa-address-book"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headline.check_lists') ?></h1>
    </div>
</div><!--pageheader-->


<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification(); ?>

        <div class="row">
            <div class="col-md-5">
                <a href="<?=BASE_URL ?>/checkLists/newCheckList" class="btn btn-primary"><?=$this->__("links.add_check_list"); ?></a>
            </div>

            <div class="col-md-2 center">

            </div>
            <div class="col-md-5">
                <div class="pull-right">

                    <div id="tableButtons" style="display:inline-block"></div>

                </div>
            </div>

        </div>

        <div class="clearfix"></div>

        <table id="allCheckLists" class="table table-bordered display" style="width:100%">
            <thead>
            <tr>
                <th><?= $this->__("label.title"); ?></th>
                <th><?= $this->__("label.description"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($checkLists as $checkList){?>
                <tr>
                    <td data-order="<?=$this->e($checkList->id); ?>"><a href="<?=BASE_URL ?>/checkLists/editCheckList/<?=$this->e($checkList->id); ?>"><?=$this->e($checkList->headline); ?></a></td>
                    <td><?=$this->e($checkList->description); ?></td>
                </tr>

            <?php } ?>
            </tbody>

        </table>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
            leantime.checkListController.initAllCheckListsTable();
        }
    );

</script>