<?php

/**
 * updated by
 * @author Regina Sharaeva
 */

namespace leantime\domain\repositories {

    use leantime\core;
    use pdo;

    class tickets
    {

        /**
         * @access public
         * @var    object
         */
        public $result = null;

        /**
         * @access public
         * @var    object
         */
        public $tickets = null;

        /**
         * @access private
         * @var    object
         */
        private $db = '';

        /**
         * @access public
         * @var    array
         */
        public $statusClasses = array('3' => 'label-info', '1' => 'label-important', '4' => 'label-warning', '2' => 'label-warning', '0' => 'label-success', "-1" => "label-default", "-2" => "label-default", "-3" => "label-default");

        /**
         * @access public
         * @var    array
         */
        public $statusNumByKey = array(
            'NEW' => 3,
            'ERROR' => 1,
            'INPROGRESS' => 4,
            'APPROVAL' => 2,
            'FINISHED' => 0,
            "ARCHIVED" => -1,
            "SUCCESSFUL" => -2,
            "NOT_SUCCESSFUL" => -3
        );


        /**
         * @access public
         * @var    array
         */
        public $statusList = array(
            '3' => 'status.new', //New
            '1' => 'status.blocked', //In Progress
            '4' => 'status.in_progress', //In Progress
            '2' => 'status.waiting_for_approval', //In Progress
            '0' => 'status.done', //Done
            '-1' => 'status.archived', //Done
            '-2' => 'status.successful',
            '-3' => 'status.not_successful',
        );

        /**
         * @access public
         * @var    array
         */
        public $priority = array('1' => 'Critical', '2' => 'High', '3' => 'Medium', '4' => 'Low', '5' => 'Lowest');


        /**
         * @access public
         * @var    array
         */
        public $efforts = array('1' => 'XXS', '2' => 'XS', 3 => 'S', '5' => 'M', 8 => 'L', 13 => 'XL', 21 => 'XXL');

        /**
         * @access public
         * @var    array
         */
        public $type = array('task', 'story', 'bug');

        /**
         * @access public
         * @var    array
         */
        public $typeIcons = array('story' => 'fa-book', 'task' => 'fa-check-square', 'bug' => 'fa-bug');

        /**
         * @access private
         * @var    integer
         */
        private $page = 0;

        /**
         * @access public
         * @var    integer
         */
        public $rowsPerPage = 10;

        /**
         * @access private
         * @var    string
         */
        private $limitSelect = "";

        /**
         * @access private
         * @var    unknown_type
         */
        public $numPages = '';

        /**
         * @access public
         * @var    string
         */
        public $sortBy = 'date';

        private $language = "";

        /**
         * __construct - get db connection
         *
         * @access public
         * @return unknown_type
         */
        public function __construct()
        {

            $this->db = core\db::getInstance();
            $this->language = new core\language();

        }

        public function getStateLabels()
        {

            if (isset($_SESSION["projectsettings"]["ticketlabels"])) {

                return $_SESSION["projectsettings"]["ticketlabels"];

            } else {

                $sql = "SELECT
						value
				FROM zp_settings WHERE `key` = :key
				LIMIT 1";

                $stmn = $this->db->database->prepare($sql);
                $stmn->bindvalue(':key', "projectsettings." . $_SESSION['currentProject'] . ".ticketlabels", PDO::PARAM_STR);

                $stmn->execute();
                $values = $stmn->fetch();
                $stmn->closeCursor();

                $labels = array();

                //preseed state labels with default values
                foreach ($this->statusList as $key => $label) {
                    $labels[$key] = array(
                        "name" => $this->language->__($label),
                        "class" => $this->statusClasses[$key]
                    );
                }

                //Override the state values that are in the db
                if ($values !== false) {

                    foreach (unserialize($values['value']) as $key => $label) {

                        //Custom key in the database represents the string value. Needs to be translated to numeric status value
                        if (!is_int($key)) {
                            $numericKey = $this->statusNumByKey[$key];
                        } else {
                            $numericKey = $key;
                        }

                        $labels[$numericKey] = array(
                            "name" => $label,
                            "class" => $this->statusClasses[$numericKey]
                        );
                    }

                }

                $_SESSION["projectsettings"]["ticketlabels"] = $labels;

                return $labels;

            }
        }

        public function getStatusList()
        {
            return $this->statusList;
        }

        /**
         * getAll - get all Tickets, depending on userrole
         *
         * @access public
         * @return array
         */
        public function getAll($limit = 9999)
        {

            $id = $_SESSION['userdata']['id'];

            $values = $this->getUsersTickets($id, $limit);

            return $values;
        }

        public function getUsersTickets($id, $limit)
        {

            $sql = "SELECT
						ticket.id,
						ticket.headline,
						ticket.type, 
						ticket.description,
						ticket.date,
						ticket.dateToFinish,
						ticket.projectId,
						ticket.priority,
                        ticket.markerId,
						ticket.status,
						project.name as projectName,
						client.name as clientName,
						client.name as clientName,
                        marker.name as markerName,
						t1.firstname AS authorFirstname, 
						t1.lastname AS authorLastname,
						t2.firstname AS editorFirstname,
						t2.lastname AS editorLastname
				FROM 
				zp_tickets AS ticket
				LEFT JOIN zp_relationuserproject ON ticket.projectId = zp_relationuserproject.projectId
				LEFT JOIN zp_projects as project ON ticket.projectId = project.id  
				LEFT JOIN zp_clients as client ON project.clientId = client.id
				LEFT JOIN zp_user AS t1 ON ticket.userId = t1.id
				LEFT JOIN zp_user AS t2 ON ticket.editorId = t2.id
                LEFT JOIN zp_marker as marker ON ticket.markerId = marker.id 
								
				WHERE zp_relationuserproject.userId = :id AND ticket.type <> 'Milestone' AND ticket.type <> 'Subtask'
				GROUP BY ticket.id
				ORDER BY ticket.id DESC";

            if ($limit > -1) {
                $sql .= " LIMIT :limit";
            }

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            if ($limit > -1) {
                $stmn->bindValue(':limit', $limit, PDO::PARAM_INT);
            }
            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }

        public function getUsersActiveTickets($userId)
        {
            $sql = "
SELECT
    ticket.id as ticketId,
    ticket.headline,
    ticket.type,
    ticket.description,
    ticket.date,
    ticket.dateToFinish,
    ticket.projectId,
    ticket.priority,
    ticket.markerId,
    ticket.status,
    ticket.hourRemaining,
    ticket.storypoints,
    (
        SELECT
            count(*)
        FROM zp_tickets
        WHERE zp_tickets.dependingTicketId = ticketId
    ) as totalSubtask,
    (
        SELECT
            count(*)
        FROM zp_tickets
        WHERE zp_tickets.dependingTicketId = ticketId and zp_tickets.status = 0
    ) as subtaskDone
FROM
    zp_tickets AS ticket
        LEFT JOIN zp_relationuserproject ON ticket.projectId = zp_relationuserproject.projectId
        LEFT JOIN zp_projects as project ON ticket.projectId = project.id
        LEFT JOIN zp_clients as client ON project.clientId = client.id
        LEFT JOIN zp_user AS t1 ON ticket.userId = t1.id
        LEFT JOIN zp_user AS t2 ON ticket.editorId = t2.id
        LEFT JOIN zp_marker as marker ON ticket.markerId = marker.id
WHERE ticket.editorId = :userId 
  AND ticket.type <> 'Milestone'
  AND ticket.type <> 'Subtask'
  AND ticket.status > 0
GROUP BY ticket.id
ORDER BY ticket.id DESC";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':userId', $userId, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }

        public function getAvailableUsersForTicket()
        {

            //Get the projects the current user is assigned to

            $sql = "SELECT 
					DISTINCT user.username, 
					user.firstname, 
					user.lastname, 
					user.id 
				FROM zp_user as user 
				JOIN zp_relationuserproject ON user.id = zp_relationuserproject.userId
				
				WHERE zp_relationuserproject.projectId IN 
				(
					SELECT 
						zp_relationuserproject.projectId 
					FROM zp_relationuserproject WHERE userId = " . $_SESSION['userdata']["id"] . "
				)";

            $stmn = $this->db->database->prepare($sql);


            $stmn->execute();
            $admin = $stmn->fetchAll();
            $stmn->closeCursor();

            return $admin;
        }

        /**
         * getAllBySearchCriteria - get Tickets by a serach term and/or a filter
         *
         * @access public
         * @param  $searchCriteria array
         * @param  $sort
         * @return array | bool
         */
        public function getAllBySearchCriteria($searchCriteria, $sort = 'standard')
        {

            $query = "SELECT
							zp_tickets.id,
							zp_tickets.headline, 
							zp_tickets.description,
							zp_tickets.date,
							zp_tickets.sprint,
							zp_sprints.name as sprintName,
							zp_tickets.storypoints,
							zp_tickets.sortindex,
							zp_tickets.dateToFinish,
							zp_tickets.projectId,
							zp_tickets.priority,
                            zp_tickets.markerId,
							zp_tickets.type,
							zp_tickets.status,
							zp_tickets.tags,
							zp_tickets.editorId,
							zp_tickets.minProfLevelId,
							zp_tickets.dependingTicketId,
							zp_tickets.planHours,
							zp_tickets.hourRemaining,
							(SELECT ROUND(SUM(hours), 2) FROM zp_timesheets WHERE zp_tickets.id = zp_timesheets.ticketId) AS bookedHours,
							zp_projects.name AS projectName,
							zp_clients.name AS clientName,
							zp_clients.id AS clientId,
                            marker.name as markerName,
							t1.lastname AS authorLastname,
							t1.firstname AS authorFirstname, 
							t1.profileId AS authorProfileId,
							t2.firstname AS editorFirstname,
							t2.lastname AS editorLastname,
							t2.profileId AS editorProfileId,
							milestone.headline AS milestoneHeadline,
							IF((milestone.tags IS NULL OR milestone.tags = ''), '#1b75bb', milestone.tags) AS milestoneColor,
							COUNT(DISTINCT zp_comment.id) AS commentCount,
							COUNT(DISTINCT zp_file.id) AS fileCount
						FROM 
							zp_tickets 
						LEFT JOIN zp_relationuserproject USING (projectId)
						LEFT JOIN zp_projects ON zp_tickets.projectId = zp_projects.id
                        LEFT JOIN zp_marker as marker ON zp_tickets.markerId = marker.id
						LEFT JOIN zp_clients ON zp_projects.clientId = zp_clients.id
						LEFT JOIN zp_user AS t1 ON zp_tickets.userId = t1.id
						LEFT JOIN zp_user AS t2 ON zp_tickets.editorId = t2.id
						LEFT JOIN zp_comment ON zp_tickets.id = zp_comment.moduleId and zp_comment.module = 'ticket'
						LEFT JOIN zp_file ON zp_tickets.id = zp_file.moduleId and zp_file.module = 'ticket'
						LEFT JOIN zp_sprints ON zp_tickets.sprint = zp_sprints.id
						LEFT JOIN zp_tickets AS milestone ON zp_tickets.dependingTicketId = milestone.id AND zp_tickets.dependingTicketId > 0 AND milestone.type = 'milestone'
						LEFT JOIN zp_timesheets AS timesheets ON zp_tickets.id = timesheets.ticketId
						WHERE zp_relationuserproject.userId = :userId AND zp_tickets.type <> 'subtask' AND zp_tickets.type <> 'milestone'
						AND zp_tickets.type <> 'testcase'";

            if ($_SESSION['currentProject'] != "") {
                $query .= " AND zp_tickets.projectId = :projectId";
            }


            if ($searchCriteria["users"] != "") {
                $editorIdIn = core\db::arrayToPdoBindingString("users", count(explode(",", $searchCriteria["users"])));
                $query .= " AND zp_tickets.editorId IN(" . $editorIdIn . ")";
            }

            if ($searchCriteria["milestone"] != "") {
                $query .= " AND zp_tickets.dependingTicketId = :milestoneId";
            }


            if ($searchCriteria["status"] != "") {

                $statusArray = explode(",", $searchCriteria['status']);

                if (array_search("not_done", $statusArray) !== false) {
                    $query .= " AND zp_tickets.status > 0";
                } else {
                    $statusIn = core\db::arrayToPdoBindingString("status", count(explode(",", $searchCriteria["status"])));
                    $query .= " AND zp_tickets.status IN(" . $statusIn . ")";
                }

            } else {

                $query .= " AND zp_tickets.status <> -1";

            }

            if ($searchCriteria["type"] != "") {
                $query .= " AND LOWER(zp_tickets.type) = LOWER(:searchType) ";
            }

            if ($searchCriteria["priority"] != "") {
                $query .= " AND LOWER(zp_tickets.priority) = LOWER(:searchPriority) ";
            }

            if ($searchCriteria["marker"] != "") {
                $query .= " AND zp_tickets.markerId = :markerId";
            }

            if ($searchCriteria["term"] != "") {
                $query .= " AND (FIND_IN_SET(:termStandard, zp_tickets.tags) OR zp_tickets.headline LIKE :termWild OR zp_tickets.description LIKE :termWild OR zp_tickets.id LIKE :termWild)";
            }

            if ($searchCriteria["sprint"] > 0 && $searchCriteria["sprint"] != "all") {
                $sprintIn = core\db::arrayToPdoBindingString("sprint", count(explode(",", $searchCriteria["sprint"])));
                $query .= " AND zp_tickets.sprint IN(" . $sprintIn . ")";
            }

            if ($searchCriteria["sprint"] == "backlog") {
                $query .= " AND (zp_tickets.sprint IS NULL OR zp_tickets.sprint = '' OR zp_tickets.sprint = -1)";
            }

            $query .= " GROUP BY zp_tickets.id ";

            if ($sort == "standard") {
                $query .= " ORDER BY zp_tickets.sortindex ASC, zp_tickets.id DESC";
            } else if ($sort == "kanbansort") {
                $query .= " ORDER BY zp_tickets.kanbanSortIndex ASC, zp_tickets.id DESC";
            } else if ($sort == "duedate") {
                $query .= " ORDER BY zp_tickets.dateToFinish ASC, zp_tickets.sortindex ASC, zp_tickets.id DESC";
            }

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':userId', $_SESSION['userdata']['id'], PDO::PARAM_INT);

            if ($_SESSION['currentProject'] != "") {

                $stmn->bindValue(':projectId', $_SESSION['currentProject'], PDO::PARAM_INT);
            }

            if ($searchCriteria["milestone"] != "") {
                $stmn->bindValue(':milestoneId', $searchCriteria["milestone"], PDO::PARAM_INT);
            }

            if ($searchCriteria["type"] != "") {
                $stmn->bindValue(':searchType', $searchCriteria["type"], PDO::PARAM_STR);
            }
            if ($searchCriteria["priority"] != "") {
                $stmn->bindValue(':searchPriority', $searchCriteria["priority"], PDO::PARAM_STR);
            }

            if ($searchCriteria["marker"] != "") {
                $stmn->bindValue(':markerId', $searchCriteria["marker"], PDO::PARAM_STR);
            }

            if ($searchCriteria["users"] != "") {
                foreach (explode(",", $searchCriteria["users"]) as $key => $user) {
                    $stmn->bindValue(":users" . $key, $user, PDO::PARAM_STR);
                }
            }

            $statusArray = explode(",", $searchCriteria['status']);
            if ($searchCriteria["status"] != "" && array_search("not_done", $statusArray) === false) {
                foreach (explode(",", $searchCriteria["status"]) as $key => $status) {
                    $stmn->bindValue(":status" . $key, $status, PDO::PARAM_STR);
                }
            }

            if ($searchCriteria["sprint"] > 0 && $searchCriteria["sprint"] != "all") {
                foreach (explode(",", $searchCriteria["sprint"]) as $key => $sprint) {
                    $stmn->bindValue(":sprint" . $key, $sprint, PDO::PARAM_STR);
                }
            }

            if ($searchCriteria["term"] != "") {
                $termWild = "%" . $searchCriteria["term"] . "%";
                $stmn->bindValue(':termWild', $termWild, PDO::PARAM_STR);
                $stmn->bindValue(':termStandard', $searchCriteria["term"], PDO::PARAM_STR);
            }

            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;

        }

        public function getAllByProjectId($projectId)
        {

            $query = "SELECT
						zp_tickets.id,
						zp_tickets.headline, 
						zp_tickets.type,
						zp_tickets.description,
						zp_tickets.date,
						zp_tickets.dateToFinish,
						zp_tickets.projectId,
						zp_tickets.priority,
                        zp_tickets.markerId,
						zp_tickets.status,
						zp_tickets.sprint,
						zp_tickets.storypoints,
						zp_tickets.hourRemaining,
						zp_tickets.acceptanceCriteria,
						zp_tickets.userId,
						zp_tickets.editorId,
						zp_tickets.minProfLevelId,
						zp_tickets.planHours,
						zp_tickets.tags,
						zp_tickets.url,
						zp_tickets.editFrom,
						zp_tickets.editTo,
						zp_tickets.dependingTicketId,					
						zp_projects.name AS projectName,
						zp_clients.name AS clientName,
						zp_user.firstname AS userFirstname,
						zp_user.lastname AS userLastname,
						t3.firstname AS editorFirstname,
						t3.lastname AS editorLastname,
                        marker.name AS markerName
					FROM 
						zp_tickets LEFT JOIN zp_projects ON zp_tickets.projectId = zp_projects.id
						LEFT JOIN zp_clients ON zp_projects.clientId = zp_clients.id
						LEFT JOIN zp_user ON zp_tickets.userId = zp_user.id
						LEFT JOIN zp_user AS t3 ON zp_tickets.editorId = t3.id
                        LEFT JOIN zp_marker AS marker ON zp_tickets.markerId = marker.id
					WHERE 
						zp_tickets.projectId = :projectId
					GROUP BY
						zp_tickets.id";


            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, '\leantime\domain\models\tickets');
            $stmn->closeCursor();

            return $values;

        }

        public function getAllTicketsWithPercentByProjectId($projectId)
        {
            $query = "SELECT
						zp_tickets.id,
						zp_tickets.headline, 
						zp_tickets.type,
						zp_tickets.description,
						zp_tickets.date,
						zp_tickets.dateToFinish,
						zp_tickets.projectId,
						zp_tickets.priority,
                        zp_tickets.markerId,
						zp_tickets.status,
						zp_tickets.sprint,
						zp_tickets.storypoints,
						zp_tickets.hourRemaining,
						zp_tickets.acceptanceCriteria,
						zp_tickets.userId,
						zp_tickets.editorId,
						zp_tickets.minProfLevelId,
						zp_tickets.planHours,
						zp_tickets.tags,
						zp_tickets.url,
						zp_tickets.editFrom,
						zp_tickets.editTo,
						zp_tickets.dependingTicketId,					
						zp_projects.name AS projectName,
						zp_clients.name AS clientName,
						zp_user.firstname AS userFirstname,
						zp_user.lastname AS userLastname,
						t3.firstname AS editorFirstname,
						t3.lastname AS editorLastname,
                        marker.name AS markerName,
                        zp_tickets.relatedTicketId,
                        (SELECT (
                            CASE 
                                WHEN COUNT(DISTINCT progressSub.id) > 0 
                            THEN 
                              ROUND(
                                (
                                  SUM(CASE WHEN progressSub.status < 1 THEN IF(progressSub.storypoints = 0, 3, progressSub.storypoints) ELSE 0 END) / 
                                  SUM(IF(progressSub.storypoints = 0, 3, progressSub.storypoints))
                                ) *100) 
                            ELSE 
                              0 
                            END) AS percentDone
                        FROM zp_tickets AS progressSub
						WHERE progressSub.dependingTicketId = zp_tickets.id AND progressSub.type <> 'milestone') AS percentDone
					FROM 
						zp_tickets LEFT JOIN zp_projects ON zp_tickets.projectId = zp_projects.id
						LEFT JOIN zp_clients ON zp_projects.clientId = zp_clients.id
						LEFT JOIN zp_user ON zp_tickets.userId = zp_user.id
						LEFT JOIN zp_user AS t3 ON zp_tickets.editorId = t3.id
                        LEFT JOIN zp_marker AS marker ON zp_tickets.markerId = marker.id
					WHERE 
						zp_tickets.projectId = :projectId and zp_tickets.type <> 'milestone' and zp_tickets.type <> 'subtask'
					    and zp_tickets.type <> 'testcase'
					GROUP BY
						zp_tickets.id";


            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, '\leantime\domain\models\tickets');
            $stmn->closeCursor();

            return $values;

        }

        /**
         * getTicket - get a specific Ticket depending on the role
         *
         * @access public
         * @param  $id
         * @return \leantime\domain\models\tickets|bool
         */
        public function getTicket($id)
        {

            $query = "SELECT
						zp_tickets.id,
						zp_tickets.headline, 
						zp_tickets.type,
						zp_tickets.description,
						zp_tickets.date,
						zp_tickets.dateToFinish,
						zp_tickets.projectId,
						zp_tickets.priority,
                        zp_tickets.markerId,
						zp_tickets.status,
						zp_tickets.sprint,
						zp_tickets.storypoints,
						zp_tickets.hourRemaining,
						zp_tickets.acceptanceCriteria,
						zp_tickets.userId,
						zp_tickets.editorId,
						zp_tickets.minProfLevelId,
						zp_tickets.planHours,
						zp_tickets.tags,
						zp_tickets.url,
						zp_tickets.editFrom,
						zp_tickets.editTo,
						zp_tickets.dependingTicketId,					
						zp_projects.name AS projectName,
						zp_clients.name AS clientName,
						zp_user.firstname AS userFirstname,
						zp_user.lastname AS userLastname,
						t3.firstname AS editorFirstname,
						t3.lastname AS editorLastname,
                        marker.name AS markerName,
                        zp_tickets.relatedTicketId AS relatedTicketId,
                        zp_tickets.result AS result
					FROM 
						zp_tickets LEFT JOIN zp_projects ON zp_tickets.projectId = zp_projects.id
						LEFT JOIN zp_clients ON zp_projects.clientId = zp_clients.id
						LEFT JOIN zp_user ON zp_tickets.userId = zp_user.id
						LEFT JOIN zp_user AS t3 ON zp_tickets.editorId = t3.id
                        LEFT JOIN zp_marker AS marker ON zp_tickets.markerId = marker.id
					WHERE 
						zp_tickets.id = :ticketId
					GROUP BY
						zp_tickets.id						
					LIMIT 1";


            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':ticketId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\tickets');
            $stmn->closeCursor();

            return $values;

        }

        public function getAllSubtasks($id)
        {

            $query = "SELECT
						zp_tickets.id,
						zp_tickets.headline, 
						zp_tickets.type,
						zp_tickets.description,
						zp_tickets.date,
						DATE_FORMAT(zp_tickets.date, '%Y,%m,%e') AS timelineDate, 
						DATE_FORMAT(zp_tickets.dateToFinish, '%Y,%m,%e') AS timelineDateToFinish, 
						zp_tickets.dateToFinish,
						zp_tickets.projectId,
						zp_tickets.priority,
                        zp_tickets.markerId,
						zp_tickets.status,
						zp_tickets.sprint,
						zp_tickets.storypoints,
						zp_tickets.hourRemaining,
						zp_tickets.acceptanceCriteria,
						zp_tickets.userId,
						zp_tickets.editorId,
                        zp_tickets.minProfLevelId,
						zp_tickets.planHours,
						zp_tickets.tags,
						zp_tickets.url,
						zp_tickets.editFrom,
						zp_tickets.editTo,
						zp_tickets.dependingTicketId,					
						zp_projects.name AS projectName,
						zp_clients.name AS clientName,
						zp_user.firstname AS userFirstname,
						zp_user.lastname AS userLastname,
						t3.firstname AS editorFirstname,
						t3.lastname AS editorLastname,
                        marker.name AS markerName
					FROM 
						zp_tickets LEFT JOIN zp_projects ON zp_tickets.projectId = zp_projects.id
						LEFT JOIN zp_clients ON zp_projects.clientId = zp_clients.id
						LEFT JOIN zp_user ON zp_tickets.userId = zp_user.id
						LEFT JOIN zp_user AS t3 ON zp_tickets.editorId = t3.id
                        LEFT JOIN zp_marker AS marker ON zp_tickets.markerId = marker.id
					WHERE 
						zp_tickets.dependingTicketId = :ticketId AND zp_tickets.type = 'subtask'
					GROUP BY
						zp_tickets.id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':ticketId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;

        }

        public function getAllMilestones($projectId, $includeArchived = false, $sortBy = "headline")
        {

            $query = "SELECT
						zp_tickets.id,
						zp_tickets.headline, 
						zp_tickets.type,
						zp_tickets.description,
						zp_tickets.date,
						DATE_FORMAT(zp_tickets.date, '%Y,%m,%e') AS timelineDate, 
						DATE_FORMAT(zp_tickets.dateToFinish, '%Y,%m,%e') AS timelineDateToFinish, 
						zp_tickets.dateToFinish,
						zp_tickets.projectId,
						zp_tickets.priority,
                        zp_tickets.markerId,
						zp_tickets.status,
						zp_tickets.sprint,
						zp_tickets.storypoints,
						zp_tickets.hourRemaining,
						zp_tickets.acceptanceCriteria,
						depMilestone.headline AS milestoneHeadline,
						IF((depMilestone.tags IS NULL OR depMilestone.tags = ''), '#1b75bb', depMilestone.tags) AS milestoneColor,
						zp_tickets.userId,
						zp_tickets.editorId,
						zp_tickets.minProfLevelId,
						zp_tickets.planHours,
						IF((zp_tickets.tags IS NULL OR zp_tickets.tags = ''), '#1b75bb', zp_tickets.tags) AS tags,
						zp_tickets.url,
						zp_tickets.editFrom,
						zp_tickets.editTo,
						zp_tickets.dependingTicketId,					
						zp_projects.name AS projectName,
						zp_clients.name AS clientName,
						zp_user.firstname AS userFirstname,
						zp_user.lastname AS userLastname,
						t3.firstname AS editorFirstname,
						t3.lastname AS editorLastname,
						t3.profileId AS editorProfileId,
                        marker.name AS markerName,
						(SELECT SUM(progressSub.planHours) FROM zp_tickets as progressSub WHERE progressSub.dependingTicketId = zp_tickets.id) AS planHours,
						(SELECT SUM(progressSub.hourRemaining) FROM zp_tickets as progressSub WHERE progressSub.dependingTicketId = zp_tickets.id) AS hourRemaining,
						SUM(ROUND(timesheets.hours, 2)) AS bookedHours,						
						
						COUNT(DISTINCT progressTickets.id) AS allTickets,
						
						(SELECT (
                            CASE WHEN 
                              COUNT(DISTINCT progressSub.id) > 0 
                            THEN 
                              ROUND(
                                (
                                  SUM(CASE WHEN progressSub.status < 1 THEN IF(progressSub.storypoints = 0, 3, progressSub.storypoints) ELSE 0 END) / 
                                  SUM(IF(progressSub.storypoints = 0, 3, progressSub.storypoints))
                                ) *100) 
                            ELSE 
                              0 
                            END) AS percentDone
                        FROM zp_tickets AS progressSub
						WHERE progressSub.dependingTicketId = zp_tickets.id AND progressSub.type <> 'milestone') AS percentDone
					FROM 
						zp_tickets 
						LEFT JOIN zp_projects ON zp_tickets.projectId = zp_projects.id
						LEFT JOIN zp_tickets AS depMilestone ON zp_tickets.dependingTicketId = depMilestone.id 
						LEFT JOIN zp_clients ON zp_projects.clientId = zp_clients.id
						LEFT JOIN zp_user ON zp_tickets.userId = zp_user.id
						LEFT JOIN zp_user AS t3 ON zp_tickets.editorId = t3.id
                        LEFT JOIN zp_marker AS marker ON zp_tickets.markerId = marker.id
						LEFT JOIN zp_tickets AS progressTickets ON progressTickets.dependingTicketId = zp_tickets.id AND progressTickets.type <> 'Milestone' AND progressTickets.type <> 'Subtask'
						LEFT JOIN zp_timesheets AS timesheets ON progressTickets.id = timesheets.ticketId
					WHERE 
						zp_tickets.type = 'milestone' AND zp_tickets.projectId = :projectId";

            if ($includeArchived === false) {
                $query .= " AND zp_tickets.status > -1 ";
            }

            $query .= "	GROUP BY
						zp_tickets.id, progressTickets.dependingTicketId";

            if ($sortBy == "date") {
                $query .= "	ORDER BY zp_tickets.editFrom ASC";
            } else if ($sortBy == "headline") {
                $query .= "	ORDER BY zp_tickets.headline ASC";
            }


            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\tickets');
            $stmn->closeCursor();

            return $values;

        }

        /**
         * getType - get the Type from the type array
         *
         * @access public
         * @param  $type
         * @return string
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * getPriority - get the priority from the priority array
         *
         * @access public
         * @param  $priority
         * @return string
         */
        public function getPriority($priority)
        {

            if ($priority !== null && $priority !== '') {

                return $this->priority[$priority];

            } else {

                return $this->priority[1];

            }
        }

        /**
         * Checks whether a user has access to a ticket or not
         */
        public function getAccessRights($id)
        {

            $sql = "SELECT 
				
				zp_relationuserproject.userId
				
			FROM zp_tickets
			
			LEFT JOIN zp_relationuserproject ON zp_tickets.projectId = zp_relationuserproject.projectId
			
			WHERE zp_tickets.id=:id AND zp_relationuserproject.userId = :user";

            $stmn = $this->db->database->prepare($sql);

            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $stmn->bindValue(':user', $_SESSION['userdata']['id'], PDO::PARAM_STR);


            $stmn->execute();
            $result = $stmn->fetchAll();
            $stmn->closeCursor();

            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function getFirstTicket($projectId)
        {

            $query = "SELECT
						zp_tickets.id,
						zp_tickets.headline, 
						zp_tickets.type,
						zp_tickets.description,
						zp_tickets.date,
						DATE_FORMAT(zp_tickets.date, '%Y,%m,%e') AS timelineDate, 
						DATE_FORMAT(zp_tickets.dateToFinish, '%Y,%m,%e') AS timelineDateToFinish, 
						zp_tickets.dateToFinish,
						zp_tickets.projectId,
						zp_tickets.priority,
                        zp_tickets.markerId,
						zp_tickets.status,
						zp_tickets.sprint,
						zp_tickets.storypoints,
						zp_tickets.hourRemaining,
						zp_tickets.acceptanceCriteria,
						zp_tickets.userId,
						zp_tickets.editorId,
						zp_tickets.minProfLevelId,
						zp_tickets.planHours,
						zp_tickets.tags,
						zp_tickets.url,
						zp_tickets.editFrom,
						zp_tickets.editTo,
						zp_tickets.dependingTicketId,
                        marker.name as markerName
					FROM zp_tickets
                    LEFT JOIN zp_marker as marker on zp_tickets.markerId = marker.id
					WHERE 
						zp_tickets.type <> 'milestone' AND zp_tickets.type <> 'subtask' AND zp_tickets.projectId = :projectId
					and zp_tickets.type <> 'testcase'
                    ORDER BY
					    zp_tickets.date ASC
					LIMIT 1";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

            $stmn->execute();
            $stmn->setFetchMode(PDO::FETCH_CLASS, 'leantime\domain\models\tickets');
            $values = $stmn->fetch();
            $stmn->closeCursor();

            return $values;

        }

        public function getNumberOfAllTickets($projectId)
        {

            $query = "SELECT
						COUNT(zp_tickets.id) AS allTickets
					FROM 
						zp_tickets
					WHERE 
						zp_tickets.type <> 'milestone' AND zp_tickets.type <> 'subtask' AND zp_tickets.type <> 'testcase'
					  AND zp_tickets.projectId = :projectId
                    ORDER BY
					    zp_tickets.date ASC
					LIMIT 1";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

            $stmn->execute();

            $values = $stmn->fetch();
            $stmn->closeCursor();

            return $values['allTickets'];

        }

        public function getNumberOfClosedTickets($projectId)
        {

            $query = "SELECT
						COUNT(zp_tickets.id) AS allTickets
					FROM 
						zp_tickets
					WHERE 
						zp_tickets.type <> 'milestone' AND zp_tickets.type <> 'subtask' AND zp_tickets.projectId = :projectId
					    AND zp_tickets.type <> 'testcase'
						AND zp_tickets.status < 1
                    ORDER BY
					    zp_tickets.date ASC
					LIMIT 1";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

            $stmn->execute();

            $values = $stmn->fetch();
            $stmn->closeCursor();

            return $values['allTickets'];

        }

        public function getEffortOfClosedTickets($projectId, $averageStorySize)
        {

            $query = "SELECT
						SUM(CASE when zp_tickets.storypoints <> '' then zp_tickets.storypoints else :avgStorySize end) AS allEffort
					FROM 
						zp_tickets
					WHERE 
						zp_tickets.type <> 'milestone' AND zp_tickets.type <> 'subtask' AND zp_tickets.type <> 'testcase' 
					  AND zp_tickets.projectId = :projectId
						AND zp_tickets.status < 1
                    ORDER BY
					    zp_tickets.date ASC
					LIMIT 1";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmn->bindValue(':avgStorySize', $averageStorySize, PDO::PARAM_INT);


            $stmn->execute();

            $values = $stmn->fetch();
            $stmn->closeCursor();

            return $values['allEffort'];

        }

        public function getEffortOfAllTickets($projectId, $averageStorySize)
        {

            $query = "SELECT
						SUM(CASE when zp_tickets.storypoints <> '' then zp_tickets.storypoints else :avgStorySize end) AS allEffort
					FROM 
						zp_tickets
					WHERE 
						zp_tickets.type <> 'milestone' AND zp_tickets.type <> 'subtask' AND zp_tickets.projectId = :projectId
						AND zp_tickets.type <> 'testcase'
                    ORDER BY
					    zp_tickets.date ASC
					LIMIT 1";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmn->bindValue(':avgStorySize', $averageStorySize, PDO::PARAM_INT);

            $stmn->execute();

            $values = $stmn->fetch();
            $stmn->closeCursor();

            return $values['allEffort'];

        }

        public function getAverageTodoSize($projectId)
        {
            $query = "SELECT
						AVG(zp_tickets.storypoints) as avgSize
					FROM 
						zp_tickets
					WHERE 
						zp_tickets.type <> 'milestone' AND zp_tickets.type <> 'subtask' AND 
						(zp_tickets.storypoints <> '' AND zp_tickets.storypoints IS NOT NULL) AND zp_tickets.projectId = :projectId
                    ORDER BY
					    zp_tickets.date ASC
					LIMIT 1";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

            $stmn->execute();

            $values = $stmn->fetch();
            $stmn->closeCursor();

            return $values['avgSize'];
        }

        /**
         * addTicket - add a Ticket with postback test
         *
         * @access public
         * @param array $values
         * @return boolean|int
         */
        public function addTicket(array $values)
        {


            $query = "INSERT INTO zp_tickets (
						headline, 
						type, 
						description, 
						date, 
						dateToFinish, 
						projectId, 
						status, 
						userId, 
						tags, 
						sprint,
						storypoints,
						priority,
                        markerId,
						hourRemaining,
						planHours,
						acceptanceCriteria,
						editFrom, 
						editTo, 
						editorId,
                        minProfLevelId,
						dependingTicketId,
                        relatedTicketId,
						sortindex,
						kanbanSortIndex
                    ) VALUES (
						:headline,
						:type,
						:description,
						:date,
						:dateToFinish,
						:projectId,
						:status,
						:userId,
						:tags,
						:sprint,
						:storypoints,
						:priority,
                        :markerId,
						:hourRemaining,
						:planHours,
						:acceptanceCriteria,
						:editFrom,
						:editTo,
						:editorId,
						:minProfLevelId,
						:dependingTicketId,
                        :relatedTicketId,  
						0,
						0
				)";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':type', $values['type'], PDO::PARAM_STR);
            $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
            $stmn->bindValue(':date', $values['date'], PDO::PARAM_STR);
            $stmn->bindValue(':dateToFinish', $values['dateToFinish'], PDO::PARAM_STR);
            $stmn->bindValue(':projectId', $values['projectId'], PDO::PARAM_STR);
            $stmn->bindValue(':status', $values['status'], PDO::PARAM_STR);
            $stmn->bindValue(':userId', $values['userId'], PDO::PARAM_STR);
            $stmn->bindValue(':tags', $values['tags'], PDO::PARAM_STR);
            $stmn->bindValue(':relatedTicketId', $values['relatedTicketId'], PDO::PARAM_STR);


            $stmn->bindValue(':sprint', $values['sprint'], PDO::PARAM_STR);
            $stmn->bindValue(':storypoints', $values['storypoints'], PDO::PARAM_STR);
            $stmn->bindValue(':priority', $values['priority'], PDO::PARAM_STR);
            $stmn->bindValue(':markerId', $values['markerId'], PDO::PARAM_STR);
            $stmn->bindValue(':hourRemaining', $values['hourRemaining'], PDO::PARAM_STR);
            $stmn->bindValue(':planHours', $values['planHours'], PDO::PARAM_STR);
            $stmn->bindValue(':acceptanceCriteria', $values['acceptanceCriteria'], PDO::PARAM_STR);

            $stmn->bindValue(':editFrom', $values['editFrom'], PDO::PARAM_STR);
            $stmn->bindValue(':editTo', $values['editTo'], PDO::PARAM_STR);
            $stmn->bindValue(':editorId', $values['editorId'], PDO::PARAM_STR);
            $stmn->bindValue(':minProfLevelId', $values['minProfLevelId'], PDO::PARAM_STR);

            if (isset($values['dependingTicketId'])) {
                $depending = $values['dependingTicketId'];
            } else {
                $depending = "";
            }

            $stmn->bindValue(':dependingTicketId', $depending, PDO::PARAM_STR);

            $stmn->execute();

            $stmn->closeCursor();

            return $this->db->database->lastInsertId();

        }


        public function patchTicket($id, $params)
        {

            $this->addTicketChange($_SESSION['userdata']['id'], $id, $params);

            $sql = "UPDATE zp_tickets SET ";

            foreach ($params as $key => $value) {
                $sql .= "" . core\db::sanitizeToColumnString($key) . "=:" . core\db::sanitizeToColumnString($key) . ", ";
            }

            $sql .= "id=:id WHERE id=:id LIMIT 1";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);

            foreach ($params as $key => $value) {
                $stmn->bindValue(':' . core\db::sanitizeToColumnString($key), $value, PDO::PARAM_STR);
            }

            $return = $stmn->execute();
            $stmn->closeCursor();

            return $return;
        }

        /**
         * updateTicket - Update Ticketinformation
         *
         * @access public
         * @param array $values
         * @param  $id
         */
        public function updateTicket(array $values, $id)
        {

            $this->addTicketChange($_SESSION['userdata']['id'], $id, $values);

            $query = "UPDATE zp_tickets
			SET 
				headline = :headline,
				type = :type,
				description=:description,
				projectId=:projectId, 
				status = :status,			
				dateToFinish = :dateToFinish,
				sprint = :sprint,
				storypoints = :storypoints,
				priority = :priority,
                markerId = :markerId,
				hourRemaining = :hourRemaining,
				planHours = :planHours,
				tags = :tags,
				editorId = :editorId,
				editFrom = :editFrom,
				editTo = :editTo,
				closed_at = :closedAt,
				acceptanceCriteria = :acceptanceCriteria,
				dependingTicketId = :dependingTicketId,
			    relatedTicketId = :relatedTicketId,
			    result = :result,
			    minProfLevelId = :minProfLevelId
			WHERE id = :id LIMIT 1";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':type', $values['type'], PDO::PARAM_STR);
            $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
            $stmn->bindValue(':projectId', $values['projectId'], PDO::PARAM_STR);
            $stmn->bindValue(':status', $values['status'], PDO::PARAM_STR);
            $stmn->bindValue(':dateToFinish', $values['dateToFinish'], PDO::PARAM_STR);
            $stmn->bindValue(':sprint', $values['sprint'], PDO::PARAM_STR);
            $stmn->bindValue(':storypoints', $values['storypoints'], PDO::PARAM_STR);
            $stmn->bindValue(':priority', $values['priority'], PDO::PARAM_STR);
            $stmn->bindValue(':markerId', $values['markerId'], PDO::PARAM_STR);
            $stmn->bindValue(':hourRemaining', $values['hourRemaining'], PDO::PARAM_STR);
            $stmn->bindValue(':acceptanceCriteria', $values['acceptanceCriteria'], PDO::PARAM_STR);
            $stmn->bindValue(':planHours', $values['planHours'], PDO::PARAM_STR);
            $stmn->bindValue(':tags', $values['tags'], PDO::PARAM_STR);
            $stmn->bindValue(':editorId', $values['editorId'], PDO::PARAM_STR);
            $stmn->bindValue(':editFrom', $values['editFrom'], PDO::PARAM_STR);
            $stmn->bindValue(':editTo', $values['editTo'], PDO::PARAM_STR);
            $stmn->bindValue(':closedAt', $values['closedAt'], PDO::PARAM_STR);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $stmn->bindValue(':dependingTicketId', $values['dependingTicketId'], PDO::PARAM_STR);
            $stmn->bindValue(':result', $values['result'], PDO::PARAM_STR);
            $stmn->bindValue(':relatedTicketId', $values['relatedTicketId'], PDO::PARAM_STR);
            $stmn->bindValue(':minProfLevelId', $values['minProfLevelId'], PDO::PARAM_STR);

            $result = $stmn->execute();

            $stmn->closeCursor();

            return $result;
        }

        public function getTicketsByRelated($id)
        {

            $sql = "SELECT
                        ticket.id,
                        ticket.headline,
                        ticket.type, 
                        ticket.description,
                        ticket.date,
                        ticket.dateToFinish,
                        ticket.projectId,
                        ticket.priority,
                        ticket.markerId,
                        ticket.status,
                        ticket.relatedTicketId
                FROM 
                zp_tickets AS ticket                                
                WHERE ticket.relatedTicketId = :id
                GROUP BY ticket.id";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }

        /**
         * @author Regina Sharaeva
         */
        public function updateAssignee($id, $editorId)
        {
            $query = "UPDATE zp_tickets
            SET 
                editorId = :editorId
            WHERE id = :id LIMIT 1";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':editorId', $editorId, PDO::PARAM_STR);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);

            $result = $stmn->execute();

            $stmn->closeCursor();

            return $result;
        }

        public function updateTicketStatus($ticketId, $status, $ticketSorting = -1)
        {

            $this->addTicketChange($_SESSION['userdata']['id'], $ticketId, array('status' => $status));

            if ($ticketSorting > -1) {

                $query = "UPDATE zp_tickets
					SET 
						kanbanSortIndex = :sortIndex,
						status = :status
					WHERE id = :ticketId
					LIMIT 1";


                $stmn = $this->db->database->prepare($query);
                $stmn->bindValue(':status', $status, PDO::PARAM_INT);
                $stmn->bindValue(':sortIndex', $ticketSorting, PDO::PARAM_INT);
                $stmn->bindValue(':ticketId', $ticketId, PDO::PARAM_INT);
                return $stmn->execute();

            } else {

                $query = "UPDATE zp_tickets
					SET 
						status = :status
					WHERE id = :ticketId
					LIMIT 1";


                $stmn = $this->db->database->prepare($query);
                $stmn->bindValue(':status', $status, PDO::PARAM_INT);
                $stmn->bindValue(':ticketId', $ticketId, PDO::PARAM_INT);
                return $stmn->execute();

            }

            $stmn->closeCursor();

        }

        /**
         * updated by
         * @author Regina Sharaeva
         */
        public function deleteTicketMarkers($marker)
        {
            $query = "UPDATE zp_tickets
                SET
                    markerId = null
                WHERE markerId = :markerId";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':markerId', $marker, PDO::PARAM_INT);

            $result = $stmn->execute();

            $stmn->closeCursor();

            return $result;

        }

        public function addTicketChange($userId, $ticketId, $values)
        {

            $fields = array(
                'headline' => 'headline',
                'type' => 'type',
                'description' => 'description',
                'project' => 'projectId',
                'priority' => 'priority',
                'markerId' => 'markerId',
                'deadline' => 'dateToFinish',
                'editors' => 'editorId',
                'fromDate' => 'editFrom',
                'toDate' => 'editTo',
                'staging' => 'staging',
                'production' => 'production',
                'planHours' => 'planHours',
                'status' => 'status');

            $changedFields = array();

            $sql = "SELECT * FROM zp_tickets WHERE id=:ticketId LIMIT 1";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':ticketId', $ticketId, PDO::PARAM_INT);

            $stmn->execute();
            $oldValues = $stmn->fetch();
            $stmn->closeCursor();

            // compare table
            foreach ($fields as $enum => $dbTable) {

                if (isset($values[$dbTable]) === true && ($oldValues[$dbTable] != $values[$dbTable]) && ($values[$dbTable] != "")) {
                    $changedFields[$enum] = $values[$dbTable];
                }

            }

            $sql = "INSERT INTO zp_tickethistory (
					userId, ticketId, changeType, changeValue, dateModified
				) VALUES (
					:userId, :ticketId, :changeType, :changeValue, NOW()
				)";

            $stmn = $this->db->database->prepare($sql);

            foreach ($changedFields as $field => $value) {

                $stmn->bindValue(':userId', $userId, PDO::PARAM_INT);
                $stmn->bindValue(':ticketId', $ticketId, PDO::PARAM_INT);
                $stmn->bindValue(':changeType', $field, PDO::PARAM_STR);
                $stmn->bindValue(':changeValue', $value, PDO::PARAM_STR);
                $stmn->execute();
            }

            $stmn->closeCursor();

        }

        /**
         * delTicket - delete a Ticket and all dependencies
         *
         * @access public
         * @param  $id
         */
        public function delticket($id)
        {

            $query = "DELETE FROM zp_tickets WHERE id = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;

        }

        public function delMilestone($id)
        {

            $query = "UPDATE zp_tickets
                SET 
                    dependingTicketId = ''
                WHERE dependingTicketId = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $stmn->execute();


            $query = "UPDATE zp_canvas_items
                SET 
                    milestoneId = ''
                WHERE milestoneId = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $stmn->execute();


            $query = "DELETE FROM zp_tickets WHERE id = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $stmn->execute();

            return true;

        }

        /**
         * get Parent ( Related ) of Ticket
         * @author Regina Sharaeva
         */
        public function getRelatedTicketById($ticketId)
        {
            $sql = "
            SELECT
                parent.*,
                marker.name AS markerName,
                t1.firstname AS authorFirstname, 
                t1.lastname AS authorLastname,
                t2.firstname AS editorFirstname,
                t2.lastname AS editorLastname
            FROM zp_tickets t
                LEFT JOIN zp_tickets parent ON parent.id = t.relatedTicketId
                LEFT JOIN zp_user AS t1 ON parent.userId = t1.id
				LEFT JOIN zp_user AS t2 ON parent.editorId = t2.id
                LEFT JOIN zp_marker as marker ON parent.markerId = marker.id
            WHERE t.id = :ticketId
                AND parent.id IS NOT NULL
            ";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':ticketId', $ticketId, PDO::PARAM_STR);
            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\tickets');
            $stmn->closeCursor();

            return $values;
        }

        /**
         * @author Regina Sharaeva
         */
        public function updateTicketResult($id, $result)
        {
            $sql = "UPDATE zp_tickets t
                SET
                    result = :result
                WHERE id = :id";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $stmn->bindValue(':result', $result, PDO::PARAM_STR);

            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }

        /**
         * @author Regina Sharaeva
         */
        public function updateRelatedTicket($id, $relatedTicketId)
        {
            $sql = "UPDATE zp_tickets t
                SET
                    relatedTicketId = :relatedTicketId
                WHERE id = :id";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $stmn->bindValue(':relatedTicketId', $relatedTicketId, PDO::PARAM_STR);

            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }

        public function getTicketsByProject($projectId)
        {
            $currentDate = new \DateTime();

            $sql = "SELECT
						ticket.id,
						ticket.headline,
						ticket.type, 
						ticket.description,
						ticket.date,
						ticket.dateToFinish,
						ticket.projectId,
						ticket.priority,
                        ticket.markerId,
						ticket.status,
						ticket.storypoints,
						ticket.editorId,
						ticket.closed_at,
						ticket.hourRemaining,
						t2.firstname AS editorFirstname,
						t2.lastname AS editorLastname,
						r_userproject.activity_percent as activityPercent
				FROM 
				zp_tickets AS ticket
				LEFT JOIN zp_relationuserproject ON ticket.projectId = zp_relationuserproject.projectId
				LEFT JOIN zp_projects as project ON ticket.projectId = project.id  
				LEFT JOIN zp_clients as client ON project.clientId = client.id
				LEFT JOIN zp_user AS t2 ON ticket.editorId = t2.id
				LEFT JOIN zp_relationuserproject AS r_userproject
				    ON r_userproject.projectId = t2.id AND r_userproject.projectId = ticket.projectId
                LEFT JOIN zp_marker as marker ON ticket.markerId = marker.id 
								
				WHERE ticket.projectId = :projectId
				GROUP BY ticket.id
				ORDER BY ticket.id ASC";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_STR);

            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }

        public function addTestCaseInfo($values)
        {
            $sql = <<<SQL
INSERT INTO zp_testcase_information (testcase_id, precondition, postcondition, steps)
VALUES (:testcaseId, :precondition, :postcondition, :steps)

SQL;
            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':testcaseId', $values['ticket_id']);
            $stmn->bindValue(':precondition', $values['precondition']);
            $stmn->bindValue(':postcondition', $values['postcondition']);
            $stmn->bindValue(':steps', $values['steps']);

            $stmn->execute();
            $stmn->closeCursor();
        }

        public function createRelationTicketTestcase($ticketId, $testcaseId)
        {
            $sql = <<<SQL
INSERT INTO zp_ticket_testcase_relation (ticket_id, testcase_id)
VALUES (:ticketId, :testcaseId)
SQL;
            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':ticketId', $ticketId);
            $stmn->bindValue(':testcaseId', $testcaseId);
            $result = $stmn->execute();
            $stmn->closeCursor();
            return $result;
        }

        public function getTestCaseData($testCaseId)
        {
            $sql = <<<SQL
SELECT
    zp_tickets.id,
    zp_tickets.headline, 
    zp_tickets.type,
    zp_tickets.description,
    zp_tickets.date,
    zp_tickets.dateToFinish,
    zp_tickets.projectId,
    zp_tickets.priority,
    zp_tickets.markerId,
    zp_tickets.status,
    zp_tickets.sprint,
    zp_tickets.storypoints,
    zp_tickets.hourRemaining,
    zp_tickets.acceptanceCriteria,
    zp_tickets.userId,
    zp_tickets.editorId,
    zp_tickets.minProfLevelId,
    zp_tickets.planHours,
    zp_tickets.tags,
    zp_tickets.url,
    zp_tickets.editFrom,
    zp_tickets.editTo,
    zp_tickets.dependingTicketId,					
    zp_projects.name AS projectName,
    zp_clients.name AS clientName,
    zp_user.firstname AS userFirstname,
    zp_user.lastname AS userLastname,
    t3.firstname AS editorFirstname,
    t3.lastname AS editorLastname,
    marker.name AS markerName,
    zp_tickets.relatedTicketId AS relatedTicketId,
    zp_tickets.result AS result,
    zti.precondition AS precondition,
    zti.postcondition AS postcondition,
    zti.steps AS steps
FROM 
    zp_tickets LEFT JOIN zp_projects ON zp_tickets.projectId = zp_projects.id
    LEFT JOIN zp_clients ON zp_projects.clientId = zp_clients.id
    LEFT JOIN zp_user ON zp_tickets.userId = zp_user.id
    LEFT JOIN zp_user AS t3 ON zp_tickets.editorId = t3.id
    LEFT JOIN zp_marker AS marker ON zp_tickets.markerId = marker.id
    LEFT JOIN zp_testcase_information AS zti ON zti.testcase_id = zp_tickets.id
WHERE 
    zp_tickets.id = :ticketId			
    AND zp_tickets.type = 'testcase'
LIMIT 1
SQL;

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':ticketId', $testCaseId, PDO::PARAM_INT);
            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\tickets');
            $stmn->closeCursor();

            return $values;
        }


        public function getTestCasesByTicket($ticketId, $projectId)
        {
            $sql = <<<SQL
SELECT
    testcase_id,
    ticket_id,
    headline,
    description,
    status
FROM zp_ticket_testcase_relation AS testcase_relation
         LEFT JOIN zp_tickets AS ticket ON testcase_relation.testcase_id = ticket.id
WHERE testcase_relation.ticket_id = :ticketId
  AND ticket.projectId = :projectId
ORDER BY testcase_id;
SQL;
            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':ticketId', $ticketId, PDO::PARAM_INT);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }

        public function editTestCaseTicket($id, $values)
        {
            $sql = <<<SQL
UPDATE zp_tickets
SET 
    headline = :headline,
    description=:description,
    status = :status,
    editorId = :editorId
WHERE id = :id;
SQL;
            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);
            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':status', $values['status'], PDO::PARAM_STR);
            $stmn->bindValue(':editorId', $values['editorId'], PDO::PARAM_INT);
            $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
            $result = $stmn->execute();

            $stmn->closeCursor();

            return $result;
        }

        public function updateTestCaseInfo($id, $values)
        {
            $sql = <<<SQL
UPDATE zp_testcase_information
SET 
    precondition = :precondition,
    postcondition = :postcondition,
    steps = :steps
WHERE testcase_id = :id;
SQL;
            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);
            $stmn->bindValue(':precondition', $values['precondition'], PDO::PARAM_STR);
            $stmn->bindValue(':postcondition', $values['postcondition'], PDO::PARAM_STR);
            $stmn->bindValue(':steps', $values['steps'], PDO::PARAM_STR);

            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }

        public function delTestCaseInfo($id)
        {
            $query = "DELETE FROM zp_testcase_information WHERE testcase_id = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }

        public function delAllTestCaseRelation($id)
        {
            $query = "DELETE FROM zp_ticket_testcase_relation WHERE testcase_id = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;

        }

        public function delTestCaseRelation($ticketId, $testCaseId)
        {
            $query = "DELETE FROM zp_ticket_testcase_relation WHERE testcase_id = :testcase_id 
                                          AND ticket_id = :ticket_id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':ticket_id', $ticketId, PDO::PARAM_INT);
            $stmn->bindValue(':testcase_id', $testCaseId, PDO::PARAM_INT);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;

        }

        public function getTestCasesNotRelated($ticketId, $projectId)
        {
            $sql = <<<SQL
SELECT
t.id,
t.headline,
t.description
FROM zp_tickets t
        LEFT JOIN zp_ticket_testcase_relation trel ON trel.testcase_id = t.id
WHERE trel.ticket_id <> :ticketId  AND t.projectId = :projectId AND type = 'testCase'
;
SQL;
            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmn->bindValue(':ticketId', $ticketId, PDO::PARAM_INT);
            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }

        public function getAllTicketsHadTestCases($projectId)
        {
            $sql = <<<SQL
SELECT DISTINCT
    ticket.id,
    ticket.headline,
    ticket.is_in_matrix
FROM zp_tickets ticket 
WHERE ticket.projectId = :projectId and ticket.type <> 'testcase'
SQL;
            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }

        public function getTestCaseMatrix($projectId)
        {
            $sql = <<<SQL
SELECT
    t.id,
    t.status,
    testCase.headline,
    testCase.description,
    testCase.status AS tcstatus,
    trel.testcase_id,
    trel.ticket_id,
    tinf.postcondition,
    tinf.precondition,
    tinf.steps
FROM zp_tickets t
         LEFT JOIN zp_ticket_testcase_relation trel ON trel.ticket_id = t.id
         LEFT JOIN zp_tickets testCase ON testCase.id = trel.testcase_id
         LEFT JOIN zp_testcase_information tinf ON tinf.testcase_id = trel.testcase_id
WHERE t.projectId = :projectId AND t.is_in_matrix is TRUE;
SQL;

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }

        public function getMatrixStatistics($projectId)
        {
            $sql = <<<SQL
SELECT 'tickets_in_matrix_total'  AS task_type,
       COUNT(*) AS total_records
FROM zp_tickets
WHERE projectId = :projectId
  AND is_in_matrix IS TRUE
  AND type <> 'testcase'

UNION ALL

SELECT 'tickets_in_matrix_successful'                  AS task_type,
       COUNT(DISTINCT successful.id) AS successful_tasks
FROM (SELECT t.id
      FROM zp_tickets t
               LEFT JOIN zp_ticket_testcase_relation trel ON trel.ticket_id = t.id AND t.projectId = :projectId
               LEFT JOIN zp_tickets testCase ON testCase.id = trel.testcase_id
      GROUP BY t.id
      HAVING COUNT(testCase.status) > 0
         AND MIN(testCase.status) != -3
         AND MAX(testCase.status) = -2) AS successful

UNION ALL

SELECT 'tickets_in_matrix_not_successful'                  AS task_type,
       COUNT(DISTINCT not_successful.id) AS not_successful_tasks
FROM (SELECT t.id
      FROM zp_tickets t
               LEFT JOIN zp_ticket_testcase_relation trel ON trel.ticket_id = t.id AND t.projectId = :projectId
               LEFT JOIN zp_tickets testCase ON testCase.id = trel.testcase_id
      GROUP BY t.id
      HAVING COUNT(testCase.status) > 0
         AND MIN(testCase.status) = -3
         AND MAX(testCase.status) != 4) AS not_successful

UNION ALL

SELECT 'tickets_in_matrix_processed'                  AS task_type,
       COUNT(DISTINCT processed.id) AS processed_tasks
FROM (SELECT t.id
      FROM zp_tickets t
               LEFT JOIN zp_ticket_testcase_relation trel ON trel.ticket_id = t.id AND t.projectId = :projectId
               LEFT JOIN zp_tickets testCase ON testCase.id = trel.testcase_id
      GROUP BY t.id
      HAVING COUNT(testCase.status) > 0
         AND MAX(testCase.status) = 4) AS processed

UNION ALL

SELECT 'tickets_in_matrix_total_with_testcases'                  AS task_type,
       COUNT(DISTINCT total_with_testcases.id) AS total_with_testcases
FROM (SELECT t.id
      FROM zp_tickets t
               LEFT JOIN zp_ticket_testcase_relation trel ON trel.ticket_id = t.id AND t.projectId = :projectId
               LEFT JOIN zp_tickets testCase ON testCase.id = trel.testcase_id
      GROUP BY t.id
      HAVING COUNT(testCase.status) > 0) as total_with_testcases

UNION ALL

SELECT 'tickets_in_matrix_total_without_testcases' AS task_type,
       COUNT(*)                  AS total_records
FROM zp_tickets t
         LEFT JOIN zp_ticket_testcase_relation trel ON trel.ticket_id = t.id
         LEFT JOIN zp_tickets testCase ON testCase.id = trel.testcase_id
WHERE t.projectId = :projectId
  AND t.is_in_matrix IS TRUE
  AND t.type <> 'testcase'
  AND testCase.id IS NULL
GROUP BY t.id
SQL;
            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;
        }
    }
}
