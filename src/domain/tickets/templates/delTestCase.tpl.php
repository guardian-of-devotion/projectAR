<?php
defined('RESTRICTED') or die('Restricted access');
$ticketId = $this->get("ticketId");
$testCase = $this->get("testCase");
?>

<h4 class="widgettitle title-light"><?php echo $this->__("subtitles.delete_test_case") ?></h4>

<form method="post" action="<?=BASE_URL ?>/tickets/delTestCase/<?php echo $testCase->id ?>">
    <p><?php echo $this->__('text.confirm_test_case_deletion'); ?></p><br />
    <input type="hidden" name="ticketId" value="<?php echo $ticketId ?>"/>
    <input type="submit" value="<?php echo $this->__('buttons.delete'); ?>" name="del" class="button" />
    <a class="btn btn-secondary" href="<?=BASE_URL.'/tickets/showTicket/'.$ticketId ?>"><?php echo $this->__('buttons.back'); ?></a>
</form>

