<?php

    defined('RESTRICTED') or die('Restricted access');
    $question = $this->get('question');
    $answers = $this->get('answers');
    $noteTemplates = $this->get('noteTemplates');
    $ticketTemplates = $this->get('ticketTemplates');

?>

<div class="pageheader">
    <div class="pull-right padding-top">
        <a href="<?php echo $_SESSION['lastPage'] ?>" class="backBtn"><i class="far fa-arrow-alt-circle-left"></i> <?=$this->__("links.go_back") ?></a>
    </div>
                       
    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headlines.questions'); ?></h1>
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
                            <h4 class="widgettitle"><?php echo $this->__('label.questionDetals'); ?></h4>
                            
                            <div class="widgetcontent">

                                <div class="row">
                                    <div class="col-sm-2 col-md-2">
                                        <label for="headline"><?php echo $this->__('label.title'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="text" name="headline" class="form-control" id="headline" value="<?php echo $question->headline; ?>" style="width:99%;"/><br />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 col-md-2">
                                        <label for="questionText"><?php echo $this->__('label.questionText'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <textarea name="questionText" rows="10" cols="80" class="form-control" style="width:99%;" id="questionText"><?php echo $question->questionText ?></textarea><br/>
                                    </div>
                                </div>
                                <br />
                                <div class="row-fluid">
                                    <?php if (isset($question->id) && $question->id != '') : ?>
                                        <div class="pull-right padding-top">
                                            <?php echo $this->displayLink('questions.delQuestion', '<i class="fa fa-trash"></i> '.$this->__('links.delete_question'), array('id' => $question->id), array('class' => 'delete')) ?>
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

        <h4 class="widgettitle title-light"><span class="iconfa iconfa-leaf"></span><?php echo $this->__('subtitle.answers'); ?></h4>
        <div class="row">
            <div class="col-md-5 col-sm-5">
                <a href="<?=BASE_URL ?>/answers/newAnswer/<?=$question->id ?>" class="btn btn-primary"><?=$this->__("links.add_answer"); ?></a>
            </div>
        </div>

        <div class="clearfix"></div>

        <table id="allAnswers" class="table table-bordered display" style="width:100%">
            <thead>
            <tr>
                <th><?= $this->__("label.answerText"); ?></th>
                <th colspan="2"><?= $this->__("label.description"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($answers as $answer) {?>
                <tr>
                    <td data-order="<?=$this->e($answer->id); ?>"><a href="<?=BASE_URL ?>/answers/editAnswer/<?=$this->e($answer->id); ?>"><?=$this->e($answer->answerText); ?></a></td>
                    <td colspan="2"><b><a href="<?=BASE_URL ?>/ticketTemplates/newTicketTemplate/<?=$answer->id ?>"><?=$this->__("links.add_ticket_template"); ?></a> / <a href="<?=BASE_URL ?>/noteTemplates/newNoteTemplate/<?=$answer->id ?>"><?=$this->__("links.add_note_template"); ?></a></b></td>
                </tr>
                <?php if (isset($ticketTemplates[$answer->id])) { foreach($ticketTemplates[$answer->id] as $ticket) { ?>
                    <tr>
                        <td></td>
                        <td><b><?= $this->__("label.task"); ?></b></td>
                        <td data-order="<?=$this->e($ticket->id); ?>"><a href="<?=BASE_URL ?>/ticketTemplates/editTicketTemplate/<?=$this->e($ticket->id); ?>"><?=$this->e($ticket->headline); ?></a></td>
                    </tr>

                <?php }} if (isset($noteTemplates[$answer->id])) { foreach($noteTemplates[$answer->id] as $note) { ?>
                    <tr>
                        <td></td>
                        <td><b><?= $this->__("label.note"); ?></b></td>
                        <td data-order="<?=$this->e($note->id); ?>"><a href="<?=BASE_URL ?>/noteTemplates/editNoteTemplate/<?=$this->e($note->id); ?>"><?=$this->e($note->headline); ?></a></td>
                    </tr>

            <?php }}} ?>
            </tbody>

        </table>
    </div>
</div>