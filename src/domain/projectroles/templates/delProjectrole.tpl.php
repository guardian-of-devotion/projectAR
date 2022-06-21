<?php

/**
 * @author Regina Sharaeva
 */
defined('RESTRICTED') or die('Restricted access');
$projectrole = $this->get("projectrole");
?>

<h4 class="widgettitle title-light"><?php echo $this->__("subtitles.delete_projectrole") ?></h4>

<form method="post" action="<?=BASE_URL ?>/projectroles/delProjectrole/<?php echo $projectrole->id ?>">
    <p><?php echo $this->__('text.confirm_projectrole_deletion'); ?></p><br />
    <input type="submit" value="<?php echo $this->__('buttons.yes_delete'); ?>" name="del" class="button" />
    <a class="btn btn-secondary" href="<?=BASE_URL ?>/projectroles/showAll/"><?php echo $this->__('buttons.back'); ?></a>
</form>

