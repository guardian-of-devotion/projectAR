<?php
  $currentProjectrole = $this->get('projectrole');
  $leads = $this->get('leads');
?>

<h4 class="widgettitle title-light"><i class="fa fa-rocket"></i> <?=$this->__('label.projectrole') ?> <?php echo $currentProjectrole->name?></h4>

<?php echo $this->displayNotification();

$id = "";
if(isset($currentProjectrole->id)) {$id = $currentProjectrole->id;
}
?>

<form class="formModal" method="post" action="<?=BASE_URL ?>/projectroles/editProjectrole/<?php echo $id;?>">

    <label><?=$this->__('label.projectrole_name') ?></label>
    <input type="text" name="name" value="<?php echo $currentProjectrole->name?>"/><br />

    <label><?=$this->__("label.lead"); ?></label>
        <select data-placeholder="<?php echo $this->__('label.lead'); ?>" name="leadId" class="user-select span11">
            <option value=""><?=$this->__("dropdown.not_assigned"); ?></option>
            <?php foreach ($this->get('leads') as $roleRow) { ?>

                <?php echo "<option value='" . $roleRow->id . "'";

                if ($currentProjectrole->leadId == $roleRow->id) { echo " selected='selected' ";
                }

                echo ">" . $this->escape($roleRow->name) . "</option>"; ?>

            <?php } ?>
        </select>

    <br />

    <div class="row">
        <div class="col-md-6">
            <input type="submit" value="<?=$this->__('buttons.save') ?>"/>
        </div>
        <div class="col-md-6 align-right padding-top-sm">
            <?php if (isset($currentProjectrole->id) && $currentProjectrole->id != '' && $login::userIsAtLeast("clientManager")) { ?>
                <a href="<?=BASE_URL ?>/projectroles/delProjectrole/<?php echo $currentProjectrole->id; ?>" class="delete formModal sprintModal"><i class="fa fa-trash"></i> <?=$this->__('links.delete_projectrole') ?></a>
            <?php } ?>
        </div>
    </div>

</form>
