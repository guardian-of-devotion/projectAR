<?php
/**
 * диаграмма ганта для задач типа ticket
 */
defined('RESTRICTED') or die('Restricted access');
$tickets = $this->get("tickets");
?>

<div class="pageheader">

    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php $this->e($_SESSION['currentProjectClient']." // ". $_SESSION['currentProjectName']); ?></h5>
        <h1><?=$this->__("headlines.todos"); ?></h1>
    </div>

</div><!--pageheader-->

<?php
if (isset($_SESSION['userdata']['settings']['views']['roadmap'])) {
    $roadmapView = $_SESSION['userdata']['settings']['views']['roadmap'];
} else {
    $roadmapView = "Month";
}
?>

<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification(); ?>

        <div class="row">
            <div class="col-md-6">
                <a href="<?= BASE_URL ?>/tickets/newTicket"
                   class="milestoneModal btn btn-primary"><?= $this->__("links.newTicket"); ?></a>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    <div class="btn-group dropRight">

                        <?php
                        $currentView = "";
                        if ($roadmapView == 'Day') {
                            $currentView = $this->__("buttons.day");
                        } elseif ($roadmapView == 'Week') {
                            $currentView = $this->__("buttons.week");
                        } elseif ($roadmapView == 'Month') {
                            $currentView = $this->__("buttons.month");
                        }
                        ?>
                        <button class="btn dropdown-toggle"
                                data-toggle="dropdown"><?= $this->__("buttons.timeframe"); ?>: <span
                                    class="viewText"><?= $currentView; ?></span><span class="caret"></span></button>
                        <ul class="dropdown-menu" id="ganttTimeControl">
                            <li><a href="javascript:void(0);" data-value="Day"
                                   class="<?php if ($roadmapView == 'Day') echo "active"; ?>"> <?= $this->__("buttons.day"); ?></a>
                            </li>
                            <li><a href="javascript:void(0);" data-value="Week"
                                   class="<?php if ($roadmapView == 'Week') echo "active"; ?>"><?= $this->__("buttons.week"); ?></a>
                            </li>
                            <li><a href="javascript:void(0);" data-value="Month"
                                   class="<?php if ($roadmapView == 'Month') echo "active"; ?>"><?= $this->__("buttons.month"); ?></a>
                            </li>
                        </ul>
                    </div>

                    <div class="btn-group viewDropDown">
                        <button class="btn dropdown-toggle"
                                data-toggle="dropdown"><?= $this->__("links.gantt_view") ?> <?= $this->__("links.view") ?></button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php if (isset($_SESSION['lastFilterdTicketKanbanView']) && $_SESSION['lastFilterdTicketKanbanView'] != "") {
                                    echo $_SESSION['lastFilterdTicketKanbanView'];
                                } else {
                                    echo BASE_URL . "/tickets/showKanban";
                                } ?>" class="active"><?= $this->__("links.kanban") ?></a></li>
                            <li>
                                <a href="<?php if (isset($_SESSION['lastFilterdTicketTableView']) && $_SESSION['lastFilterdTicketTableView'] != "") {
                                    echo $_SESSION['lastFilterdTicketTableView'];
                                } else {
                                    echo BASE_URL . "/tickets/showAll";
                                } ?>"><?= $this->__("links.table") ?></a></li>
                            <li>
                                <a href="<?= BASE_URL ?>/tickets/ticketRoadmap"><i
                                            class="fa fa-edit"></i> <?php echo $this->__("links.note_edit"); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <?php
        if (count($tickets) == 0) {
            echo "<div class='empty' id='emptySprint' style='text-align:center;'>";
            echo "<div style='width:50%' class='svgContainer'>";
            echo file_get_contents(ROOT . "/images/svg/undraw_adjustments_p22m.svg");
            echo "</div>";
            echo "
            <h4>" . $this->__("headlines.no_tickets") . "<br/>
            
            <br />
            <a href=\"" . BASE_URL . "/tickets/newTicket\" class=\"addCanvasLink btn btn-primary\">" . $this->__("links.add_ticket") . "</a></h4></div>";

        }
        ?>
        <div class="gantt-container" style="height:100%; overflow: auto;">
            <svg id="gantt"></svg>
        </div>

    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        <?php if(isset($_SESSION['userdata']['settings']["modals"]["roadmap"]) === false || $_SESSION['userdata']['settings']["modals"]["roadmap"] == 0){     ?>
        leantime.helperController.showHelperModal("roadmap");
        <?php
        //Only show once per session
        $_SESSION['userdata']['settings']["modals"]["roadmap"] = 1;
        } ?>

        <?php if(isset($_GET['showMilestoneModal'])) {

        if ($_GET['showMilestoneModal'] == "") {
            $modalUrl = "";
        } else {
            $modalUrl = "/" . (int)$_GET['showMilestoneModal'];
        }
        ?>

        leantime.ticketsController.openMilestoneModalManually("<?=BASE_URL ?>/tickets/editMilestone<?php echo $modalUrl; ?>");
        window.history.pushState({}, document.title, '<?=BASE_URL ?>/tickets/roadmap');

        <?php } ?>


    });

    <?php if(count($tickets) > 0) {?>
    var tasks = [

        <?php foreach ($tickets as $ticket) {
        $percentDone = $ticket->status <= 0 ? '100' : $ticket->percentDone;
        $ticketWorkedHours = $this->getTicketWorkedHours($ticket->id);

        echo "{
                    id :'" . $ticket->id . "',
                    name :" . json_encode("" . $ticket->headline . " (" . $percentDone . "% Done)") . ",
                    start :'" . (($ticket->editFrom != '0000-00-00 00:00:00' && substr($ticket->editFrom, 0, 10) != '1969-12-31') ? $ticket->editFrom : date('Y-m-d')) . "',
                    end :'" . (($ticket->editTo != '0000-00-00 00:00:00' && substr($ticket->editTo, 0, 10) != '1969-12-31') ? $ticket->editTo : date('Y-m-d', strtotime("+1 day", time()))) . "',
                    progress :'" . ($percentDone) . "',
                    dependencies :'" . ($ticket->relatedTicketId ? $ticket->relatedTicketId : '') . "',
                    custom_class :'',
                    color: '" . $ticket->tags . "',
                    bgColor: '" . $ticket->tags . "',
                    workedHours : '" . ($ticketWorkedHours == null ? 0 : $ticketWorkedHours) . "',
                    planHours : '" . ($ticket->planHours == null ? 0 : $ticket->planHours) . "',
                    type : '" . $ticket->type . "',
                },";
    }
        ?>
    ];

    leantime.ticketsController.initGanttChart(tasks, '<?=$roadmapView; ?>');
    <?php } ?>

</script>