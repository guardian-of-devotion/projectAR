<?php

    defined('RESTRICTED') or die('Restricted access');
    $answer = $this->get('answer');
    $ticketTemplates = $this->get('ticketTemplates');
    $noteTemplates = $this->get('noteTemplates');
    $statusLabels = $this->get('statusLabels');
    $ticketTypes = $this->get('ticketTypes');
    $efforts = $this->get('efforts');
    $priorities = $this->get('priorities');
    $markers = $this->get('markers');
?>

<div class="pageheader">
    <div class="pull-right padding-top">
        <a href="<?php echo $_SESSION['lastPage'] ?>" class="backBtn"><i class="far fa-arrow-alt-circle-left"></i> <?=$this->__("links.go_back") ?></a>
    </div>
                       
    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headlines.answers'); ?></h1>
    </div>
</div><!--pageheader-->
        
<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification() ?>

        <div class="tabbedwidget tab-primary projectTabs">

            <div id="projectdetails">

                <form action="" method="post" class="stdform">

                    <div class="row">
                    <span class="col-sm col-md">
                        <div class="widget">
                            <h4 class="widgettitle"><?php echo $this->__('label.answerDetals'); ?></h4>
                            
                            <div class="widgetcontent">

                                <div class="row">
                                    <div class="col-sm-2 col-md-2">
                                        <label for="answerText"><?php echo $this->__('label.title'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="text" name="answerText" class="form-control" id="answerText" value="<?php echo $answer->answerText; ?>" style="width:99%;"/><br />
                                    </div>
                                </div>
                                <br />
                                <div class="row-fluid">
                                    <?php if (isset($answer->id) && $answer->id != '') : ?>
                                        <div class="pull-right padding-top">
                                            <?php echo $this->displayLink('answers.delAnswer', '<i class="fa fa-trash"></i> '.$this->__('links.delete_answer'), array('id' => $answer->id), array('class' => 'delete')) ?>
                                        </div>
                                    <?php endif; ?> 
                                    <input type="submit" name="save" id="save" value="<?php echo $this->__('buttons.save'); ?>" class="button" />
                                </div>
                            </div>
                        </div>
                    </span>
                </form>

            </div>
        </div>

        <h4 class="widgettitle title-light"><span class="iconfa iconfa-leaf"></span><?php echo $this->__('subtitle.ticketTemplates'); ?></h4>
        <div class="row">
            <div class="col-md-5 col-sm-5">
                <a href="<?=BASE_URL ?>/ticketTemplates/newTicketTemplate/<?=$answer->id ?>" class="btn btn-primary"><?=$this->__("links.add_ticket_template"); ?></a>
            </div>
        </div>

        <div class="clearfix"></div>

        <table id="allTicketTemplates" class="table table-bordered display" style="width:100%">
            <thead>
            <tr>
                <th><?= $this->__("label.title"); ?></th>
                <th><?= $this->__("label.todo_type"); ?></th>
                <th><?= $this->__("label.effort"); ?></th>
                <th><?= $this->__("label.priority"); ?></th>
                <th><?= $this->__("label.marker"); ?></th>
                <th class="planned-hours-col"><?= $this->__("label.planned_hours"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($ticketTemplates as $ticketTemplate) { if ($ticketTemplate->dependingTicketId == null) { ?>
                <tr>
                    <td data-order="<?=$this->e($ticketTemplate->id); ?>"><a href="<?=BASE_URL ?>/ticketTemplates/editTicketTemplate/<?=$this->e($ticketTemplate->id); ?>"><?=$this->e($ticketTemplate->headline); ?></a></td>
                    <td><?=$this->__("label.".strtolower($ticketTemplate->type)); ?></td>
                    <td><?=$efforts[$ticketTemplate->storypoints]; ?></td>
                    <td><?=$priorities[$ticketTemplate->priority]; ?></td>
                    <td><?php foreach ($markers as $marker) { if (in_array($marker->id, json_decode($ticketTemplate->markers))) { echo $marker->name . "</br>"; }} ?></td>
                    <td><?=$this->e($ticketTemplate->planHours); ?></td>
                 </tr>

            <?php }} ?>
            </tbody>

        </table>
        <h4 class="widgettitle title-light"><span class="iconfa iconfa-leaf"></span><?php echo $this->__('subtitle.noteTemplates'); ?></h4>
        <div class="row">
            <div class="col-md-5 col-sm-5">
                <a href="<?=BASE_URL ?>/noteTemplates/newNoteTemplate/<?=$answer->id ?>" class="btn btn-primary"><?=$this->__("links.add_note_template"); ?></a>
            </div>
        </div>

        <div class="clearfix"></div>

        <table id="allNoteTemplates" class="table table-bordered display" style="width:100%">
            <thead>
            <tr>
                <th><?= $this->__("label.title"); ?></th>
                <th><?= $this->__("label.description"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($noteTemplates as $noteTemplate) { ?>
                <tr>
                    <td data-order="<?=$this->e($noteTemplate->id); ?>"><a href="<?=BASE_URL ?>/noteTemplates/editNoteTemplate/<?=$this->e($noteTemplate->id); ?>"><?=$this->e($noteTemplate->headline); ?></a></td>
                    <td><?=html_entity_decode($noteTemplate->description); ?></td>
                 </tr>

            <?php } ?>
            </tbody>

        </table>
    </div>
</div>