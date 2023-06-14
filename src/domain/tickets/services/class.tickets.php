<?php

/**
 * updated by
 * @author Regina Sharaeva
 */
namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class tickets
    {

        private $projectRepository;
        private $ticketRepository;
        private $projectService;
        private $timesheetsRepo;
        private $language;

        public function __construct()
        {

            $this->tpl = new core\template();
            $this->projectRepository = new repositories\projects();
            $this->ticketRepository = new repositories\tickets();
            $this->markerRepository = new repositories\markers();
            $this->language = new core\language();
            $this->projectService = new services\projects();
            $this->timesheetsRepo = new repositories\timesheets();
            $this->fileService = new services\files();
            $this-> projectRolesRepo = new repositories\projectroles();
            $this->userRepo = new repositories\users();
            $this->storyPointsService = new services\storyPoints();
        }

        //GET Properties
        public function getStatusLabels() {

            return $this->ticketRepository->getStateLabels();

        }

        public function getTypeIcons() {

            return $this->ticketRepository->typeIcons;

        }

        public function getEffortLabels() {

            return $this->ticketRepository->efforts;

        }

        public function getTicketTypes() {

            return $this->ticketRepository->type;

        }

        public function getPriorityLabels() {
            return $this->ticketRepository->priority;
        }

        public function getMarkers() {
            return $this->markerRepository->getAllMarkers();
        }

        public function prepareTicketSearchArray(array $searchParams)
        {

            $searchCriteria = array(
                "currentProject"=> $_SESSION["currentProject"],
                "users"=>"",
                "status"=>"",
                "term"=> "",
                "type"=> "",
                "sprint"=> $_SESSION['currentSprint'],
                "milestone"=>"",
                "orderBy" => "sortIndex",
                "groupBy" => "",
                "priority" => "",
                "marker" => ""
            );

            if(isset($searchParams["users"]) === true) {
                $searchCriteria["users"] = $searchParams["users"];
            }

            if (isset($searchParams["status"]) === true) {
                $searchCriteria["status"] = $searchParams["status"];
            }

            if(isset($searchParams["term"]) === true) {
                $searchCriteria["term"] =$searchParams["term"];
            }

            if(isset($searchParams["type"]) === true) {
                $searchCriteria["type"] = $searchParams["type"];
            }

            if(isset($searchParams["milestone"]) === true) {
                $searchCriteria["milestone"] =$searchParams["milestone"];
            }

            if(isset($searchParams["groupBy"]) === true) {
                $searchCriteria["groupBy"] =$searchParams["groupBy"];
            }

            if(isset($searchParams["priority"]) === true) {
                $searchCriteria["priority"] =$searchParams["priority"];
            }

            if(isset($searchParams["marker"]) === true) {
                $searchCriteria["marker"] =$searchParams["marker"];
            }

            if(isset($searchParams["sprint"]) === true) {
                $searchCriteria["sprint"] =  $searchParams["sprint"];
                $_SESSION["currentSprint"] = $searchCriteria["sprint"];
            }

            setcookie("searchCriteria", serialize($searchCriteria), time()+3600, "/tickets/");

            return $searchCriteria;
        }

        //GET All BY SearchCriteria
        public function getAll($searchCriteria)
        {
            return $this->ticketRepository->getAllBySearchCriteria($searchCriteria, $searchCriteria['orderBy']);

        }

        /**
         * @return array
         * get all tickets by project id for ticketRoadmap
         */
        public function getAllTicketsForRoadmap()
        {
            return $this->ticketRepository->getAllTicketsWithPercentByProjectId($_SESSION['currentProject']);
        }

        public function getTicket($id)
        {

            $ticket = $this->ticketRepository->getTicket($id);

            //Check if user is allowed to see ticket
            if($ticket && $this->projectService->isUserAssignedToProject($_SESSION['userdata']['id'], $ticket->projectId)) {

                //Fix date conversion
                //Todo: Move to views
                $ticket->date = $this->language->getFormattedDateString($ticket->date);
                $ticket->dateToFinish = $this->language->getFormattedDateString($ticket->dateToFinish);
                $ticket->editFrom = $this->language->getFormattedDateString($ticket->editFrom);
                $ticket->editTo = $this->language->getFormattedDateString($ticket->editTo);

                return $ticket;

            }

            return false;
        }

        public function getOpenUserTicketsThisWeekAndLater ($userId, $projectId) {

            $searchCriteria = $this->prepareTicketSearchArray(array("currentProject" => $projectId, "users" => $userId, "status" => "not_done", "sprint"=>""));
            $allTickets = $this->ticketRepository->getAllBySearchCriteria($searchCriteria, "duedate");

            $tickets = array(
                "thisWeek" => array(),
                "later" => array()
            );

            foreach($allTickets as $row){

                if($row['dateToFinish'] == "0000-00-00 00:00:00" || $row['dateToFinish'] == "1969-12-31 00:00:00") {
                    $tickets["later"][] = $row;
                }else {
                    $date = new \DateTime($row['dateToFinish']);

                    $nextFriday = strtotime('friday this week');
                    $nextFridayDateTime = new \DateTime();
                    $nextFridayDateTime->setTimestamp($nextFriday);
                    if($date <= $nextFridayDateTime){
                        $tickets["thisWeek"][] = $row;
                    }else{
                        $tickets["later"][] = $row;
                    }
                }


            }
            return $tickets;
        }

        public function getAllMilestones($projectId, $includeArchived = false, $sortBy="headline")
        {

            if($projectId > 0) {
                return $this->ticketRepository->getAllMilestones($projectId, $includeArchived, $sortBy);
            }

            return false;
        }

        public function getAllSubtasks($ticketId)
        {
           return $this->ticketRepository->getAllSubtasks($ticketId);
        }

        //Add
        public function quickAddTicket($params)
        {

            $values = array(
                'headline' => $params['headline'],
                'type' => 'Task',
                'description' => isset($params['description']) ? $params['description'] : '',
                'projectId' => $_SESSION['currentProject'],
                'editorId' => $_SESSION['userdata']['id'],
                'userId' => $_SESSION['userdata']['id'],
                'date' => date("Y-m-d H:i:s"),
                'dateToFinish' => isset($params['dateToFinish']) ? strip_tags($params['dateToFinish']) : "",
                'status' => isset($params['status']) ? (int) $params['status'] : 3,
                'storypoints' => '',
                'hourRemaining' => '',
                'planHours' => '',
                'sprint' => isset($params['sprint']) ? (int) $params['sprint'] : "",
                'acceptanceCriteria' => '',
                'priority' => 3,
                'marker' => 2,
                'tags' => '',
                'editFrom' => '',
                'editTo' => '',
                'dependingTicketId' => isset($params['milestone']) ? (int) $params['milestone'] : ""
            );

            if($values['headline'] == "") {
                $error = array("status"=>"error", "message"=>"Headline Missing");
                return $error;
            }

            $result = $this->ticketRepository->addTicket($values);

            if($result > 0) {

                $actual_link = BASE_URL."/tickets/showTicket/" . $result;
                $message = sprintf($this->language->__("email_notifications.new_todo_message"), $_SESSION["userdata"]["name"], $params['headline']);
                $this->projectService->notifyProjectUsers($message, $this->language->__("email_notifications.new_todo_subject"), $_SESSION['currentProject'], array("link" => $actual_link, "text" => $this->language->__("email_notifications.new_todo_cta")));

                return $result;

            }else{

                return false;

            }


        }

        public function quickAddMilestone($params)
        {

            $values = array(
                'headline' => $params['headline'],
                'type' => 'milestone',
                'description' => '',
                'projectId' => $_SESSION['currentProject'],
                'editorId' => $_SESSION['userdata']['id'],
                'userId' => $_SESSION['userdata']['id'],
                'date' => date("Y-m-d H:i:s"),
                'dateToFinish' => "",
                'status' => 3,
                'storypoints' => '',
                'hourRemaining' => '',
                'planHours' => '',
                'sprint' => '',
                'dependingTicketId' =>$params['dependentMilestone'],
                'acceptanceCriteria' => '',
                'tags' => $params['tags'],
                'editFrom' => $this->language->getISODateString($params['editFrom']),
                'editTo' => $this->language->getISODateString($params['editTo'])
            );


            if($values['headline'] == "") {
                $error = array("status"=>"error", "message"=>"Headline Missing");
                return $error;
            }

            //$params is an array of field names. Exclude id
            return $this->ticketRepository->addTicket($values);

        }


        /**
         * @author Regina Sharaeva
         */
        public function addTicket($values, $newMarkers=null, $relatedMarkers=null, $relatedTickets=null)
        {
            $addedTickets = [];
            $markers = $newMarkers ? $newMarkers : $values['markers'];

            if ($markers == null || $markers == "" || count($markers) == 0) {
                $addedTickets[] = $this->addTicketData($values, null);
            } else {
                if ($relatedTickets == null) {
                    $relatedTickets = [];
                }
                if ($relatedMarkers == null) {
                    $relatedMarkers = [];
                }

                if (count($markers) == 1) {

                    $addedTickets[] = $this->addTicketData($values, $markers[0], true);
                } else {
                    foreach ($markers as $markerId) {
                        if (!array_key_exists($markerId, $relatedTickets)) {
                            $marker = $this->markerRepository->getMarker($markerId);
                            $relatedTickets[$markerId] = $this->addTicketData($values, $markerId);
                            $relatedMarkers[$markerId] = $marker->relatedMarkerId;
                            $addedTickets[] = $relatedTickets[$markerId];
                        }
                    }

                    $this->updateRelatedTicket($relatedMarkers, $relatedTickets);
                }
            }
            return $addedTickets;
        }


        /**
         * updated by
         * @author Regina Sharaeva
         */
        public function addTicketData($values, $marker, $flag=false)
        {
            $values = array(
                'id' => '',
                'headline' => $values['headline'],
                'type' => $values['type'],
                'description' => $values['description'],
                'projectId' => $_SESSION['currentProject'],
                'editorId' => $this->assignUser($marker, $values['editorId'], $values['relatedTicketId'], $flag, $values['minProfLevelId']),
                'minProfLevelId' => $values['minProfLevelId'],
                'userId' => $_SESSION['userdata']['id'],
                'date' => date('Y-m-d  H:i:s'),
                'dateToFinish' => $values['dateToFinish'],
                'status' => $values['status'] ? $values['status'] : 3,
                'planHours' => $values['planHours'],
                'tags' => $values['tags'],
                'sprint' => $values['sprint'],
                'storypoints' => $values['storypoints'],
                'hourRemaining' => $values['hourRemaining'],
                'priority' => $values['priority'],
                'markerId' => $marker,
                'acceptanceCriteria' => $values['acceptanceCriteria'],
                'editFrom' => $values['editFrom'],
                'editTo' => $values['editTo'],
                'dependingTicketId' => $values['dependingTicketId'],
                'relatedTicketId' => $values['relatedTicketId'] ? $values['relatedTicketId'] : NULL,
            );

            if(!$this->projectService->isUserAssignedToProject($_SESSION['userdata']['id'], $values['projectId'])) {

                return array("msg" => "notifications.ticket_save_error_no_access", "type" => "error");

            }

            if ($values['headline'] === '') {

                return array("msg" => "notifications.ticket_save_error_no_headline", "type" => "error");

            } else {

                //Prepare dates for db
                if($values['dateToFinish'] != "" && $values['dateToFinish'] != NULL) {
                    $values['dateToFinish'] = $this->language->getISODateString($values['dateToFinish']);
                }

                if($values['editFrom'] != "" && $values['editFrom'] != NULL) {
                    $values['editFrom'] =  $this->language->getISODateString($values['editFrom']);
                }

                if($values['editTo'] != "" && $values['editTo'] != NULL) {
                    $values['editTo'] =  $this->language->getISODateString($values['editTo']);
                }

                //Add Ticket
                return $this->ticketRepository->addTicket($values);
            }
        }


        /**
         * @author Regina Sharaeva
         */
        public function updateRelatedTicket($relatedMarkers, $relatedTickets)
        {
            foreach ($relatedMarkers as $marker => $relatedId) {
                $flag = true;
                if ($relatedId != null && $relatedId != "") {
                    if (array_key_exists($relatedId, $relatedTickets)) {
                        $this->ticketRepository->updateRelatedTicket($relatedTickets[$marker], $relatedTickets[$relatedId]);
                        $flag = false;
                    } else {
                        $relatedMarker = $this->markerRepository->getMarker($relatedId);

                        $relatedId = $relatedMarker->relatedMarkerId;

                        while ($relatedId != null && $relatedId != "") {
                            if (array_key_exists($relatedId, $relatedTickets)) {
                                $this->ticketRepository->updateRelatedTicket($relatedTickets[$marker], $relatedTickets[$relatedId]);
                                $flag = false;
                                break;
                            } else {
                                $relatedMarker = $this->markerRepository->getMarker($relatedId);
                                $relatedId = $relatedMarker->relatedMarkerId;
                            }
                        }
                    }
                }

                if ($flag) {
                    $this->updateAssignee($relatedTickets[$marker]);
                }
            }
            return;
        }

        /**
         * assignUser - assign user by projectrole, depending on userrole
         *
         * @access public
         * @return array
         * @author Regina Sharaeva
         */
        public function assignUser($markerId, $user, $relatedTicket, $flag = null, $minProfLevel = null)
        {
            if ($user != null && $user != "") {
                return $user;
            } else {
                //проверка на блокирующую задачу, возможно убрать
                if ($flag === true && ($relatedTicket == null || $relatedTicket == "")) {
//                    var_dump($flag);

                    $marker = $this->markerRepository->getMarker($markerId);
                    $projectroleId = $marker->projectroleId;
                    if ($projectroleId) {
                        if ($minProfLevel) {
                            $profUsers = $this->userRepo->getUsersByMinProfLevel($minProfLevel, $projectroleId); //TODO сделать через массив, а не запрос в бд
                        } else {
                            $profUsers = $this->userRepo->getByProjectrole($projectroleId);
                        }
                        if (count($profUsers) == 0) {
                            return $this->assignLead($projectroleId);
                        }
                        if (count($profUsers) == 1) {
                            return $profUsers[0]['id'];
                        }

                        $remainingUsers = [];
                        foreach ($profUsers as $user) {
                            $userId = $user['id'];
                            //коэфф занятости
                            $coefOfWork = $this->calculateFreeTime($user);
                            $remainingUsers[] = array(
                                'userId' => $userId,
                                'coef' => $coefOfWork,
                                'critical' => 0,
                                'high' => 0,
                                'medium' => 0,
                                'low' => 0,
                            );
                        }

                        $min = PHP_FLOAT_MAX;
                        foreach ($remainingUsers as $user) {
                            if ($min > $user['coef']) {
                                $min = $user['coef'];
                            }
                        }

                        $lastUsers = [];
                        foreach ($remainingUsers as $user) {
                            if ($user['coef'] == $min) {
                                $lastUsers[] = $user;
                            }
                        }
                        if (count($lastUsers) == 1) {
                            return $lastUsers[0]['userId'];
                        }

                        foreach ($lastUsers as $user) {
                            $tickets = $this->ticketRepository->getUsersActiveTickets($user['userId']);
                            foreach ($tickets as $ticket) {
                                switch ($ticket['priority']) {
                                    case 1:
                                        $user['critical'] += 1;
                                        break;
                                    case 2:
                                        $user['high'] += 1;
                                        break;
                                    case 3:
                                        $user['medium'] += 1;
                                        break;
                                    case 4:
                                        $user['low'] += 1;
                                        break;
                                }
                            }
                        }
                        //critical
                        $remainingUsers = $this->getUsersByCriteria($lastUsers, 'critical');
                        if (count($remainingUsers) == 1) {
                            return $remainingUsers[0]['userId'];
                        }
                        $remainingUsers = $this->getUsersByCriteria($lastUsers, 'high');
                        if (count($remainingUsers) == 1) {
                            return $remainingUsers[0]['userId'];
                        }
                        $remainingUsers = $this->getUsersByCriteria($lastUsers, 'medium');
                        if (count($remainingUsers) == 1) {
                            return $remainingUsers[0]['userId'];
                        }
                        $remainingUsers = $this->getUsersByCriteria($lastUsers, 'low');
                        //равное количество времени, даем первому, кто раньше зарегался
                        return $remainingUsers[0]['userId'];
                    }
                    // если придет null, то никого не назначит
                    return $this->assignLead($projectroleId);
                }
            }
        }


        // Назначение Лида
        public function assignLead($projectroleId) {
            $projectrole = $this->projectRolesRepo->getProjectrole($projectroleId);
            $leadId = $projectrole->leadId;
            $leads = $this->userRepo->getByProjectrole($leadId);
            if (count($leads) == 0) {
                return null;
            } else {
                return $leads[0]['id'];
            }
        }

        /**
         * считаем коэффициент
         */
        public function calculateFreeTime($user)
        {
            $userId = $user['id'];
            $userAssignedTickets = $this->ticketRepository->getUsersActiveTickets($userId);
            $totalRemainingHourSum = 0;
            foreach ($userAssignedTickets as $ticket) {
                $totalRemainingHourSum += $this->getTicketWorkTime($ticket);
            }
            $peopleWorkTime = $user['hourlyRate'];
            //коэффициент свободного времени
            return $totalRemainingHourSum / ($peopleWorkTime * $this->projectRepository->getUserActivityPercentByProjectId($userId, $_SESSION['currentProject'])['activityPercent']);
        }

        public function getTicketWorkTime($ticket)
        {
            $hourRemaining =  $ticket['hourRemaining'];

            if ($hourRemaining) {
                 return $hourRemaining * 60;
            } else {
                $storyPointConversion = $this->storyPointsService->getStoryPointsConversion($ticket['projectId'], $ticket['storypoints']);
                $subtaskTime = 0;

                if ($ticket['totalSubtask'] > 0  &&  $ticket['subtaskDone'] > 0) {
                    $subtaskTime =  $ticket['subtaskDone'] / $ticket['totalSubtask'];
                }

                return $storyPointConversion['0']['effort_value'] * 60 * ( 100 - ($subtaskTime)) / 100;

            }
        }

        public function getUsersByCriteria($lastUsers, $criteriaName): array
        {
            $remainingUsers = [];
            $minHigh = min(array_column($lastUsers, $criteriaName));
            foreach ($lastUsers as $user) {
                if ($user[$criteriaName] == $minHigh) {
                    $remainingUsers[] = $user;
                }
            }

            return $remainingUsers;
        }

        /**
         * @author Regina Sharaeva
         */
        public function updateAssignee($id)
        {
            $ticket = $this->ticketRepository->getTicket($id);
            $this->ticketRepository->updateAssignee($id, $this->assignUser($ticket->markerId, $ticket->editorId, null, true));
        }

        //Update

        /**
         * @author Regina Sharaeva
         */
        public function updateTicket($id, $values)
        {
            //closed_at
            if ($values['status'] <= 0) {
                $now = new \DateTime();
                $now = $now->format('Y-m-d H:i:s');
                $values['closed_at'] = $now;
            } else {
                $values['closed_at'] = null;
            }



            $markers = $values['markers'];

            if (count($markers) == 0) {
                return $this->updateTicketData($id, $values, null);
            } else {
                if (count($markers) == 1) {
                    return $this->updateTicketData($id, $values, $markers[0], true);
                } else {
                    $relatedTickets = [];
                    $relatedMarkers = [];

                    $ticket = $this->ticketRepository->getTicket($id);

                    if (($key = array_search($ticket->markerId, $markers)) !== false) {
                        $relatedTickets[$ticket->markerId] = $this->updateTicketData($id, $values, $ticket->markerId);
                        $marker = $this->markerRepository->getMarker($ticket->markerId);
                        $relatedMarkers[$ticket->markerId] = $marker->relatedMarkerId;
                    } else {
                        $relatedTickets[$markers[0]] = $this->updateTicketData($id, $values, $markers[0]);
                        $marker = $this->markerRepository->getMarker($markers[0]);
                        $relatedMarkers[$markers[0]] = $marker->relatedMarkerId;
                    }

                    return $this->addTicket($values, $markers, $relatedMarkers, $relatedTickets);
                }
            }
        }

        //Update
        /**
         * updated by
         * @author Regina Sharaeva
         */
        public function updateTicketData($id, $values, $marker)
        {

            $values = array(
                'id' => $id,
                'headline' => $values['headline'],
                'type' => $values['type'],
                'description' => $values['description'],
                'projectId' => $_SESSION['currentProject'],
                'editorId' => $this->assignUser($marker, $values['editorId'], $values['relatedTicketId'], true, $values['minProfLevelId']),
                'date' => date('Y-m-d  H:i:s'),
                'dateToFinish' => $values['dateToFinish'],
                'status' => $values['status'],
                'planHours' => $values['planHours'],
                'tags' => $values['tags'],
                'sprint' => $values['sprint'],
                'storypoints' => $values['storypoints'],
                'hourRemaining' => $values['hourRemaining'],
                'priority' => $values['priority'],
                'markerId' => $marker,
                'acceptanceCriteria' => $values['acceptanceCriteria'],
                'editFrom' => $values['editFrom'],
                'editTo' => $values['editTo'],
                'closedAt' => array_key_exists('closed_at', $values) ? $values['closed_at'] : null,
                'dependingTicketId' => $values['dependingTicketId'],
                'result' => array_key_exists('result', $values) ? $values['result'] : NULL,
                'relatedTicketId' => $values['relatedTicketId'] ? $values['relatedTicketId'] : NULL,
                'minProfLevelId' =>  array_key_exists('minProfLevelId',$values)  ? $values['minProfLevelId']: null,
            );

            if(!$this->projectService->isUserAssignedToProject($_SESSION['userdata']['id'], $values['projectId'])) {

                return array("msg" => "notifications.ticket_save_error_no_access", "type" => "error");

            }

            if ($values['headline'] === '') {

                return array("msg" => "notifications.ticket_save_error_no_headline", "type" => "error");

            } else {

                //Prepare dates for db
                if($values['dateToFinish'] != "" && $values['dateToFinish'] != NULL) {
                    $values['dateToFinish'] = $this->language->getISODateString($values['dateToFinish']);

                }

                if($values['editFrom'] != "" && $values['editFrom'] != NULL) {
                    $values['editFrom'] = $this->language->getISODateString($values['editFrom']);
                }

                if($values['editTo'] != "" && $values['editTo'] != NULL) {
                    $values['editTo'] = $this->language->getISODateString($values['editTo']);
                }
                //Update Ticket
                if($this->ticketRepository->updateTicket($values, $id) === true){

                    $subject = sprintf($this->language->__("email_notifications.todo_update_subject"), $id, $values['headline']);
                    $actual_link = BASE_URL."/tickets/showTicket/" . $id;
                    $message = sprintf($this->language->__("email_notifications.todo_update_message"), $_SESSION['userdata']['name'], $values['headline']);

                    $this->projectService->notifyProjectUsers($message, $subject, $_SESSION['currentProject'], array("link"=>$actual_link, "text"=> $this->language->__("email_notifications.todo_update_cta")));

                    return true;
                }

            }
        }

        public function updateTicketResult($id, $result)
        {

            if ($this->ticketRepository->getTicket($id)->status == '' ) {
                return false;
            }

            return $this->ticketRepository->updateTicketResult($id, $result);
        }

        public function patchTicket($id, $params)
        {

            //$params is an array of field names. Exclude id
            unset($params["id"]);

            return $this->ticketRepository->patchTicket($id, $params);

        }

        public function quickUpdateMilestone($params)
        {

            $values = array(
                'headline' => $params['headline'],
                'type' => 'milestone',
                'description' => '',
                'projectId' => $_SESSION['currentProject'],
                'editorId' => $params['editorId'],
                'userId' => $_SESSION['userdata']['id'],
                'date' => date("Y-m-d H:i:s"),
                'dateToFinish' => "",
                'status' => $params['status'],
                'storypoints' => '',
                'hourRemaining' => '',
                'planHours' => '',
                'sprint' => '',
                'acceptanceCriteria' => '',
                'dependingTicketId' => $params['dependentMilestone'],
                'tags' => $params['tags'],
                'editFrom' => $this->language->getISODateString($params['editFrom']),
                'editTo' => $this->language->getISODateString($params['editTo'])
            );

            if($values['headline'] == "") {
                $error = array("status"=>"error", "message"=>"Headline Missing");
                return $error;
            }

            //$params is an array of field names. Exclude id
            return $this->ticketRepository->updateTicket($values, $params["id"]);

        }

        public function upsertSubtask($values, $parentTicket)
        {

            $subtaskId = $values['subtaskId'];

            $values = array(
                'headline' => $values['headline'],
                'type' => 'subtask',
                'description' => $values['description'],
                'projectId' => $parentTicket->projectId,
                'editorId' => $_SESSION['userdata']['id'],
                'userId' => $_SESSION['userdata']['id'],
                'date' => date("Y-m-d H:i:s"),
                'dateToFinish' => "",
                'status' => $values['status'],
                'storypoints' => "",
                'hourRemaining' => $values['hourRemaining'],
                'planHours' => $values['planHours'],
                'sprint' => "",
                'acceptanceCriteria' => "",
                'tags' => "",
                'editFrom' => "",
                'editTo' => "",
                'dependingTicketId' => $parentTicket->id,
            );

            if ($subtaskId == "new" || $subtaskId == "") {

                //New Ticket
                if(!$this->ticketRepository->addTicket($values)){
                    return false;
                }

            } else {

                //Update Ticket

                if(!$this->ticketRepository->updateTicket($values, $subtaskId)){
                    return false;
                }

            }

            return true;

        }

        /**
         * @author Regina Sharaeva
         */
        public function updateAssigneeForRelated($id)
        {

            $tickets = $this->ticketRepository->getTicketsByRelated($id);

            foreach ($tickets as $key => $ticket) {
                if ($ticket["editorId"] == null || $ticket["editorId"] == "") {
                    $this->ticketRepository->updateAssignee($ticket["id"], $this->assignUser($ticket["markerId"], null, null, true));
                }
            }
            return;
        }

        /**
         * updated by
         * @author Regina Sharaeva
         */
        public function updateTicketStatusAndSorting($params, $handler=null)
        {

            //Jquery sortable serializes the array for kanban in format
            //statusKey: ticket[]=X&ticket[]=X2...,
            //statusKey2: ticket[]=X&ticket[]=X2...,
            //This represents status & kanban sorting
            foreach($params as $status=>$ticketList){

                if(is_numeric($status)) {

                    $tickets = explode("&", $ticketList);

                    if (is_array($tickets) === true) {
                        foreach ($tickets as $key => $ticketString) {
                            $id = substr($ticketString, 9);

                            if($this->ticketRepository->updateTicketStatus($id, $status, ($key * 100)) === false) {
                                return false;
                            } else {
                                if ($status == 0) {
                                    $this->updateAssigneeForRelated($id);
                                }
                            }

                        }
                    }
                }
            }

            if($handler) {

                //Assumes format ticket_ID
                $id = substr($handler, 7);

                $ticket = $this->getTicket($id);

                if($ticket) {

                    $subject = sprintf($this->language->__("email_notifications.todo_update_subject"), $id, $ticket->headline);
                    $actual_link = BASE_URL."/tickets/showTicket/" . $id;
                    $message = sprintf($this->language->__("email_notifications.todo_update_message"), $_SESSION['userdata']['name'], $ticket->headline);

                    $this->projectService->notifyProjectUsers($message, $subject, $_SESSION['currentProject'], array("link" => $actual_link, "text" => $this->language->__("email_notifications.todo_update_cta")));
                }
            }



            return true;


        }

        //Delete
        public function deleteTicket($id){

            $ticket = $this->getTicket($id);

            if(!$this->projectService->isUserAssignedToProject($_SESSION['userdata']['id'], $ticket->projectId)) {
                return array("msg" => "notifications.ticket_delete_error", "type" => "error");
            }

            if($this->ticketRepository->delticket($id)){
                return true;
            }

            return false;

        }

        public function deleteMilestone($id){

            $ticket = $this->getTicket($id);

            if(!$this->projectService->isUserAssignedToProject($_SESSION['userdata']['id'], $ticket->projectId)) {
                return array("msg" => "notifications.milestone_delete_error", "type" => "error");
            }

            if($this->ticketRepository->delMilestone($id)){
                return true;
            }

            return false;

        }

        public function getLastTicketViewUrl()
        {
            $url = BASE_URL . "/tickets/showKanban";

            if (isset($_SESSION['lastTicketView']) && $_SESSION['lastTicketView'] != "") {

                if ($_SESSION['lastTicketView'] == "kanban" && isset($_SESSION['lastFilterdTicketKanbanView']) && $_SESSION['lastFilterdTicketKanbanView'] != "") {
                    return $_SESSION['lastFilterdTicketKanbanView'];
                }

                if ($_SESSION['lastTicketView'] == "table" && isset($_SESSION['lastFilterdTicketTableView']) && $_SESSION['lastFilterdTicketTableView'] != "") {
                    return $_SESSION['lastFilterdTicketTableView'];
                }

                return $url;

            } else {
                return $url;
            }
        }

        public function getRelatedTickets($ticketId)
        {
            $result = [];
            $ticket = $this->ticketRepository->getRelatedTicketById($ticketId);
            while ($ticket) {
                $result[] = $ticket;
                /** @var models\tickets $ticket */
                $ticket = $this->ticketRepository->getRelatedTicketById($ticket->id);
            }

            return $result;
        }

        public function getTicketWorkedHours($ticketId)
        {
            return $this->timesheetsRepo->getTicketHours($ticketId);
        }

        public function getResultFiles($id) {
            return $this->fileService->getFilesByModule('ticketResult', $id);
        }

        /**
         * get files from related tickets, return array of files
         * @param $ticketId
         * @return array
         */
        public function getFilesOfRelatedTickets($ticketId) {
            $tickets = $this->getRelatedTickets($ticketId);

            $result = [];
            foreach ($tickets as $ticket) {
                $result[$ticket->id] = $this->fileService->getFilesByModule('ticketResult', $ticket->id);
            }

            return $result;
        }
    }

}
