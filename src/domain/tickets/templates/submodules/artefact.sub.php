<?php
$ticket = $this->get('ticket');
$parents = $this->get('relatedTickets');
$AllTickets = $this->get('AllTicketsOnThisProject');
$filesOfRelatedTickets = $this->get('filesOfRelatedTickets');
?>
<div style="display:block; padding:10px; border-bottom:1px solid #f0f0f0;">
    <?php $flag = false; ?>
    <?php foreach ($parents as $key => $parent) { ?>
        <?php if ($parent->status == 0) { $flag = true; ?>
            <div class="container" style="margin-left:<?php if ($key > 0) { echo (10 + ($key - 1) * 77) . 'px';} else echo -10 . 'px'?>;margin-top:5px">
                <div class="row">
                    <div <?php if ($key) { echo 'class="col-sm-1"';} ?>
                         style="border-bottom:1px solid black; border-left:1px solid black;
                                 width: <?php if ($key) { echo 50 . 'px';} else echo 0;?>; height: <?php if ($key) {
                             echo 75 . 'px';} else echo 0; ?>; display: inline-block;"></div>
                    <div class="col-<?php $n = 12 - $key; if ($n < 0) { echo "1"; } else { echo 12 - $key; } ?>"
                         style="border:1px solid #7e7e7e; display: inline-block;">
                        <p style="margin-left:10px;"><b>Задача: </b><a
                                    href="<?= BASE_URL ?>/tickets/showTicket/<?php echo $parent->id; ?>"> <?php echo $parent->headline; ?> </a>
                        </p>
                        <a id = "<?php echo 'a' . $parent->id; ?>" onclick="showText(<?php echo 'a'.$parent->id; ?>, <?php echo 'd'.$parent->id; ?>)">Показать описание результата</a>
                        <div id = "<?php echo 'd' . $parent->id; ?>" style="margin-left:15px; padding-top:5px; display: none;"> <?php echo html_entity_decode($parent->result); ?> </div>
                        <p style="margin-left:15px;"><b>Автор:</b> <?php if ($parent->editorFirstname) {
                                echo $parent->editorFirstname . ' ' . $parent->editorLastname;
                            } else {
                                printf($this->__('text.ticket_not_assign'));
                            } ?></p>
                        <div class="mediamgr_content">
                            <ul class='listfile'>
                                <?php foreach ($filesOfRelatedTickets[$parent->id] as $file): ?>
                                    <li class="<?php echo $file['moduleId'] ?>">
                                        <div class="inlineDropDownContainer" style="float:right;">

                                            <a href="javascript:void(0);" class="dropdown-toggle ticketDropDown"
                                               data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="nav-header"><?php echo $this->__("subtitles.file"); ?></li>
                                                <li>
                                                    <a href="<?= BASE_URL ?>/download.php?module=<?php echo $file['module'] ?>&encName=<?php echo $file['encName'] ?>&ext=<?php echo $file['extension'] ?>&realName=<?php echo $file['realName'] ?>"><?php echo $this->__("links.download"); ?></a>
                                                </li>
                                            </ul>
                                        </div>

                                        <a class="cboxElement"
                                           href="<?= BASE_URL ?>/download.php?module=<?php echo $file['module'] ?>&encName=<?php echo $file['encName'] ?>&ext=<?php echo $file['extension'] ?>&realName=<?php echo $file['realName'] ?>">
                                            <?php if (in_array(strtolower($file['extension']), $this->get('imgExtensions'))) : ?>
                                                <img style='max-height: 50px; max-width: 70px;'
                                                     src="<?= BASE_URL ?>/download.php?module=<?php echo $file['module'] ?>&encName=<?php echo $file['encName'] ?>&ext=<?php echo $file['extension'] ?>&realName=<?php echo $file['realName'] ?>"
                                                     alt=""/>
                                            <?php else: ?>
                                                <img style='max-height: 50px; max-width: 70px;'
                                                     src='<?= BASE_URL ?>/images/thumbs/doc.png'/>
                                            <?php endif; ?>
                                            <span class="filename"><?php echo $file['realName'] ?></span>
                                        </a>

                                    </li>
                                <?php endforeach; ?>
                            </ul>

                        </div><!--mediamgr_content-->
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        <?php } ?>
        <?php if ($flag == false) { ?>
            <h6>Дождитесь перевода блокирующих задач в статус Готово</h6>
        <?php } ?>
    <?php } ?>
    <div class="clear"></div>
</div>

<script>
    //    id of a tag and after id of <div> with result
    function showText(aId, divId) {
        if (document.getElementById(divId.id).style.display !== "block" ) {
            document.getElementById(divId.id).style.display = "block";
            document.getElementById(aId.id).textContent = "Скрыть";
        } else {
            document.getElementById(divId.id).style.display = "none";
            document.getElementById(aId.id).textContent = "Показать описание результата";
        }
    }

</script>
