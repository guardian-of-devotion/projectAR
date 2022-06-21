<?php
  
  /**
   * @author Regina Sharaeva
   */
  $currentMarker = $this->get('marker');
  $markers = $this->get('markers');
?>

<h4 class="widgettitle title-light"><i class="fa fa-rocket"></i> <?=$this->__('label.marker') ?> <?php echo $currentMarker->name?></h4>

<?php echo $this->displayNotification();

$id = "";
if(isset($currentMarker->id)) {$id = $currentMarker->id;
}
?>

<form class="formModal" method="post" action="<?=BASE_URL ?>/markers/editMarker/<?php echo $id;?>">

    <label><?=$this->__('label.marker_name') ?></label>
    <input type="text" name="name" value="<?php echo $currentMarker->name; ?>"/><br />

    <label><?=$this->__("label.projectrole"); ?></label>
        <select data-placeholder="<?php echo $this->__('label.projectrole'); ?>" name="projectroleId" class="user-select span11">
            <option value=""><?=$this->__("dropdown.not_assigned"); ?></option>
            <?php foreach ($this->get('projectroles') as $roleRow) { ?>

                <?php echo "<option value='" . $roleRow->id . "'";

                if ($currentMarker->projectroleId == $roleRow->id) { echo " selected='selected' ";
                }

                echo ">" . $this->escape($roleRow->name) . "</option>"; ?>

            <?php } ?>
        </select>

     <label><?=$this->__("label.relatedMarker"); ?></label>
        <select data-placeholder="<?php echo $this->__('label.relatedMarker'); ?>" name="relatedMarkerId" class="user-select span11">
            <option value=""><?=$this->__("dropdown.not_assigned"); ?></option>
            <?php foreach ($markers as $roleRow) { ?>
                <?php if ($roleRow->id != $currentMarker->id) {

                echo "<option value='" . $roleRow->id . "'";

                if ($currentMarker->relatedMarkerId == $roleRow->id) { echo " selected='selected' ";
                }

                echo ">" . $this->escape($roleRow->name) . "</option>";

            }} ?>
        </select>   

    <br />

    <div class="row">
        <div class="col-md-6">
            <input type="submit" value="<?=$this->__('buttons.save') ?>"/>
        </div>
        <div class="col-md-6 align-right padding-top-sm">
            <?php if (isset($currentMarker->id) && $currentMarker->id != '' && $login::userIsAtLeast("clientManager")) { ?>
                <a href="<?=BASE_URL ?>/markers/delMarker/<?php echo $currentMarker->id; ?>" class="delete formModal sprintModal"><i class="fa fa-trash"></i> <?=$this->__('links.delete_marker') ?></a>
            <?php } ?>
        </div>
    </div>

</form>
