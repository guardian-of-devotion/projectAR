<?php
    
    /**
     * @author Regina Sharaeva
     */
    defined( 'RESTRICTED' ) or die( 'Restricted access' );
    $allMarkers = $this->get("AllMarkers");

?>

<<div class="pageheader">
    <div class="pageicon"><span class="fa fa-address-book"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headline.markers') ?></h1>
    </div>
</div><!--pageheader-->

<div class="maincontent">
	<div class="maincontentinner">

        <?php echo $this->displayNotification(); ?>

        
            <div class="row">
                <div class="col-md-5">
                    <a href="<?=BASE_URL ?>/markers/editMarker" class="milestoneModal btn btn-primary"><?=$this->__("links.add_marker"); ?></a>
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

            <table id="allMarkersTable" class="table table-bordered display" style="width:100%">
                <thead>
                <tr>
                    <th><?= $this->__("label.title"); ?></th>
                    <th><?= $this->__("label.projectrole"); ?></th>
                    <th><?= $this->__("label.relatedMarker"); ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($this->get('allMarkers') as $row) {?>
                        <tr>
                            <td data-order="<?=$this->e($row->id); ?>"><a href="<?=BASE_URL ?>/markers/editMarker/<?=$this->e($row->id); ?>" class="milestoneModal"><?=$this->e($row->name); ?></a></td>
                            <td><?=$this->e($row->projectrole); ?></td>
                            <td><?=$this->e($row->relatedMarker); ?></td>
                        </tr>

                    <?php } ?>
                </tbody>

            </table>
	</div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
            leantime.markersController.initMarkersTable();
        }
    );

</script>