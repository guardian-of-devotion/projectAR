<?php

/**
 * updated by
 * @author Regina Sharaeva
 */ 
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class newTicket
    {

        private $projectService;
        private $ticketService;
        private $tpl;
        private $sprintService;
        private $fileService;
        private $commentService;
        private $timesheetService;
        private $userService;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();

            $this->language = new core\language();

            $this->projectService = new services\projects();
            $this->ticketService = new services\tickets();
            $this->sprintService = new services\sprints();
            $this->fileService = new services\files();
            $this->commentService = new services\comments();
            $this->timesheetService = new services\timesheets();
            $this->userService = new services\users();

            if(!isset($_SESSION['lastPage'])) {
                $_SESSION['lastPage'] = BASE_URL."/tickets/showKanban/";
            }
        }

        public function get () {

            $ticket = new models\tickets(
                array(
                    "userLastname"=>$_SESSION['userdata']["name"],
                    "status"=>3,
                    "projectId"=>$_SESSION['currentProject']
                )
            );

            $ticket->date =  $this->language->getFormattedDateString(date("Y-m-d H:i:s"));

            $this->tpl->assign('ticket', $ticket);
            $this->tpl->assign('statusLabels', $this->ticketService->getStatusLabels());
            $this->tpl->assign('ticketTypes', $this->ticketService->getTicketTypes());
            $this->tpl->assign('efforts', $this->ticketService->getEffortLabels());
            $this->tpl->assign('priorities', $this->ticketService->getPriorityLabels());
            $this->tpl->assign('markers', $this->ticketService->getMarkers());
            $this->tpl->assign('milestones', $this->ticketService->getAllMilestones($_SESSION["currentProject"]));
            $this->tpl->assign('sprints', $this->sprintService->getAllSprints($_SESSION["currentProject"]));

            $this->tpl->assign('kind', $this->timesheetService->getLoggableHourTypes());
            $this->tpl->assign('ticketHours', 0);
            $this->tpl->assign('userHours', 0);

            $this->tpl->assign('timesheetsAllHours', 0);
            $this->tpl->assign('remainingHours', 0);

            $this->tpl->assign('userInfo', $this->userService->getUser($_SESSION['userdata']['id']));
            $this->tpl->assign('users', $this->projectService->getUsersAssignedToProject($_SESSION["currentProject"]));
            $this->tpl->assign("allTicketsOnThisProject", $this->ticketService->getAllTicketsForRoadmap());

            $this->tpl->display('tickets.newTicket');


        }

        public function post ($params) {

            if (isset($params['saveTicket'])) {

                $result = $this->ticketService->addTicket($params);

                if($result == true) {

                    $this->tpl->setNotification($this->language->__("notifications.ticket_saved"), "success");

                    if(isset($params["saveAndCloseTicket"]) === true) {

                        $this->tpl->redirect($_SESSION['lastPage']);

                    }else {

                        $this->tpl->redirect(BASE_URL."/tickets/showKanban/");
                    }

                }else {

                    $this->tpl->setNotification($this->language->__($result["msg"]), "error");

                    $ticket = new models\tickets($params);
                    $ticket->userLastname = $_SESSION['userdata']["name"];

                    $this->tpl->assign('ticket',$ticket);
                    $this->tpl->assign('statusLabels', $this->ticketService->getStatusLabels());
                    $this->tpl->assign('ticketTypes', $this->ticketService->getTicketTypes());
                    $this->tpl->assign('efforts', $this->ticketService->getEffortLabels());
                    $this->tpl->assign('priorities', $this->ticketService->getPriorityLabels());
                    $this->tpl->assign('markers', $this->ticketService->getMarkers());
                    $this->tpl->assign('milestones', $this->ticketService->getAllMilestones($_SESSION["currentProject"]));
                    $this->tpl->assign('sprints', $this->sprintService->getAllSprints($_SESSION["currentProject"]));

                    $this->tpl->assign('kind', $this->timesheetService->getLoggableHourTypes());
                    $this->tpl->assign('ticketHours', 0);
                    $this->tpl->assign('userHours', 0);

                    $this->tpl->assign('timesheetsAllHours', 0);
                    $this->tpl->assign('remainingHours', 0);

                    $this->tpl->assign('userInfo', $this->userService->getUser($_SESSION['userdata']['id']));
                    $this->tpl->assign('users', $this->projectService->getUsersAssignedToProject($_SESSION["currentProject"]));
                    $this->tpl->assign("allTicketsOnThisProject", $this->ticketService->getAllTicketsForRoadmap());

                    $this->tpl->display('tickets.newTicket');

                }

            }

        }

    }

}
