<?php

    defined('RESTRICTED') or die('Restricted access');
    $question = $this->get('question');

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
                                    <input type="submit" name="save" id="save" value="<?php echo $this->__('buttons.save'); ?>" class="button" />
                                </div>
                            </div>
                        </div>
                    </span>
                </form>

            </div>
        </div>
    </div>
</div>