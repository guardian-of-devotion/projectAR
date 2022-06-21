<?php

/**
 * @author Regina Sharaeva
 */
defined('RESTRICTED') or die('Restricted access');
$marker = $this->get("marker");
?>

<h4 class="widgettitle title-light"><?php echo $this->__("subtitles.delete_marker") ?></h4>

<form method="post" action="<?=BASE_URL ?>/markers/delMarker/<?php echo $marker->id ?>">
    <p><?php echo $this->__('text.confirm_marker_deletion'); ?></p><br />
    <input type="submit" value="<?php echo $this->__('buttons.yes_delete'); ?>" name="del" class="button" />
    <a class="btn btn-secondary" href="<?=BASE_URL ?>/markers/showAll/"><?php echo $this->__('buttons.back'); ?></a>
</form>

