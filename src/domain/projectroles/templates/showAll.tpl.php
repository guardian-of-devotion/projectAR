<?php

    defined( 'RESTRICTED' ) or die( 'Restricted access' );
    $allProjectroles        = $this->get("AllProjectroles");

?>

<div class="pageheader">
    <div class="pageicon"><span class="fa fa-address-book"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headline.projectroles') ?></h1>
    </div>
</div><!--pageheader-->

<div class="maincontent">
	<div class="maincontentinner">

        <?php echo $this->displayNotification(); ?>

        
            <div class="row">
                <div class="col-md-5">
                    <a href="<?=BASE_URL ?>/projectroles/editProjectrole" class="milestoneModal btn btn-primary"><?=$this->__("links.add_projectrole"); ?></a>
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

            <table id="allProjectRolesTable" class="table table-bordered display" style="width:100%">
                <thead>
                <tr>
                    <th><?= $this->__("label.title"); ?></th>
                    <th><?= $this->__("label.lead"); ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($this->get('allProjectroles') as $row){?>
                        <tr>
                            <td data-order="<?=$this->e($row->id); ?>"><a href="<?=BASE_URL ?>/projectroles/editProjectrole/<?=$this->e($row->id); ?>" class="milestoneModal"><?=$this->e($row->name); ?></a></td>
                            <td class="milestoneModal"><?=$this->e($row->leadName); ?></td>
                        </tr>

                    <?php } ?>
                </tbody>

            </table>
	</div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
            leantime.projectRolesController.initProjectRolesTable();
        }
    );

</script>