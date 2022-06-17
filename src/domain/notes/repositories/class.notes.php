<?php


namespace leantime\domain\repositories;

use leantime\core;
use pdo;

class notes
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
    public $notes = null;

    /**
     * @access public
     * @var    array
     */
    public $statusClasses = array('8' => "label-default", '-1' =>  "label-default");

    /**
     * @access public
     * @var    array
     */
    public $statusNumByKey = array('DEFAULT' => 8, 'archive' => -1 );

    /**
     * @access public
     * @var    array
     * на случай расширения или добавления архива
     */
    public $statusList = array(
        '8' => 'default',
        '-1' => 'archive',
    );

    /**
     * @access public
     * @var    string
     */
    public $sortBy = 'date';

    /**
     * __construct - get db connection
     *
     * @access public
     */
    public function __construct()
    {
        $this->db = core\db::getInstance();
        $this->language = new core\language();
    }

    public function getStateLabels()
    {

        if(isset($_SESSION["projectsettings"]["notelabels"])) {

            return $_SESSION["projectsettings"]["notelabels"];

        }else{

            $sql = "SELECT
						value
				FROM zp_settings WHERE `key` = :key
				LIMIT 1";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindvalue(':key', "projectsettings.".$_SESSION['currentProject'].".notelabels", PDO::PARAM_STR);

            $stmn->execute();
            $values = $stmn->fetch();
            $stmn->closeCursor();

            $labels = array();

            //preseed state labels with default values
            foreach($this->statusList as $key=>$label) {
                $labels[$key] = array(
                    "name" => $this->language->__($label),
                    "class" => $this->statusClasses[$key]
                );
            }

            //Override the state values that are in the db
            if($values !== false) {

                foreach(unserialize($values['value']) as $key => $label) {

                    //Custom key in the database represents the string value. Needs to be translated to numeric status value
                    if(!is_int($key)) {
                        $numericKey = $this->statusNumByKey[$key];
                    }else{
                        $numericKey = $key;
                    }

                    $labels[$numericKey] = array(
                        "name" => $label,
                        "class" => $this->statusClasses[$numericKey]
                    );
                }

            }

            $_SESSION["projectsettings"]["notelabels"] = $labels;

            return $labels;
        }
    }

    public function getStatusList() {
        return $this->statusList;
    }

    /**
     * getAll - get all Note, depending on userrole
     *
     * @access public
     * @return array
     */
    public function getAllNotesForProjects($projectId)
    {

        $sql = "SELECT
                    note.id,
                    note.headline,
                    note.type, 
                    note.description,
                    note.date,
                    note.projectId,
                    note.status,
                    project.name as projectName,
                    client.name as clientName,
                    t1.firstname AS authorFirstname, 
                    t1.lastname AS authorLastname,
                    t2.firstname AS editorFirstname,
                    t2.lastname AS editorLastname
                FROM 
                zp_note AS note
                LEFT JOIN zp_relationuserproject ON note.projectId = zp_relationuserproject.projectId
                LEFT JOIN zp_projects as project ON note.projectId = project.id  
                LEFT JOIN zp_clients as client ON project.clientId = client.id
                LEFT JOIN zp_user AS t1 ON note.userId = t1.id
                LEFT JOIN zp_user AS t2 ON note.editorId = t2.id
                                
                WHERE note.projectId = :id
                GROUP BY note.id
                ORDER BY note.id DESC";

        $stmn = $this->db->database->prepare($sql);
        $stmn->bindValue(':id', $projectId, PDO::PARAM_STR);
        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();

        return $values;
    }

    public function getNote($id) {
        $query = "SELECT
						note.id,
						note.headline,
						note.type, 
						note.description,
						note.date,
                        note.tags,
						note.projectId,
						note.status,
                        note.editTo,
                        note.editFrom,
						project.name as projectName,
						client.name as clientName,
						t1.firstname AS authorFirstname, 
						t1.lastname AS authorLastname,
						t2.firstname AS editorFirstname,
						t2.lastname AS editorLastname
				FROM 
				zp_note AS note
				LEFT JOIN zp_relationuserproject ON note.projectId = zp_relationuserproject.projectId
				LEFT JOIN zp_projects as project ON note.projectId = project.id  
				LEFT JOIN zp_clients as client ON project.clientId = client.id
				LEFT JOIN zp_user AS t1 ON note.userId = t1.id
				LEFT JOIN zp_user AS t2 ON note.editorId = t2.id
				WHERE note.id = :noteId
				GROUP BY note.id
				ORDER BY note.id DESC";

        $stmn = $this->db->database->prepare($query);
        $stmn->bindValue(':noteId', $id, PDO::PARAM_INT);

        $stmn->execute();
        $values = $stmn->fetchObject('\leantime\domain\models\notes');
        $stmn->closeCursor();

        return $values;
    }

    public function getUsersNotes($id,$limit)
    {

        $sql = "SELECT
						note.id,
						note.headline,
						note.type, 
						note.description,
						note.date,
						note.projectId,
						note.status,
						project.name as projectName,
						client.name as clientName,
						t1.firstname AS authorFirstname, 
						t1.lastname AS authorLastname,
						t2.firstname AS editorFirstname,
						t2.lastname AS editorLastname
				FROM 
				zp_note AS note
				LEFT JOIN zp_relationuserproject ON note.projectId = zp_relationuserproject.projectId
				LEFT JOIN zp_projects as project ON note.projectId = project.id  
				LEFT JOIN zp_clients as client ON project.clientId = client.id
				LEFT JOIN zp_user AS t1 ON note.userId = t1.id
				LEFT JOIN zp_user AS t2 ON note.editorId = t2.id
								
				WHERE zp_relationuserproject.userId = :id
				GROUP BY note.id
				ORDER BY note.id DESC";

        if($limit > -1) {
            $sql .= " LIMIT :limit";
        }

        $stmn = $this->db->database->prepare($sql);
        $stmn->bindValue(':id', $id, PDO::PARAM_STR);
        if($limit > -1) {
            $stmn->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();

        return $values;
    }

    /**
     *
     * @access public
     * @param  array $values
     * @return boolean|int
     */
    public function addNote(array $values)
    {
        $query = "INSERT INTO zp_note (
						headline, 
						description, 
						date, 
						projectId, 
						status, 
						userId, 
						tags, 
						editFrom, 
						editTo, 
						editorId,
						sortindex,
						kanbanSortIndex
                    ) VALUES (
						:headline,
						:description,
						:date,
						:projectId,
						:status,
						:userId,
						:tags,
						:editFrom,
						:editTo,
						:editorId,
						0,
						0
				)";

        $stmn = $this->db->database->prepare($query);

        $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
        $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
        $stmn->bindValue(':date', $values['date'], PDO::PARAM_STR);
        $stmn->bindValue(':projectId', $values['projectId'], PDO::PARAM_STR);
        $stmn->bindValue(':status', $values['status'], PDO::PARAM_STR);
        $stmn->bindValue(':userId', $values['userId'], PDO::PARAM_STR);
        $stmn->bindValue(':tags', $values['tags'], PDO::PARAM_STR);

        $stmn->bindValue(':editFrom', $values['editFrom'], PDO::PARAM_STR);
        $stmn->bindValue(':editTo', $values['editTo'], PDO::PARAM_STR);
        $stmn->bindValue(':editorId', $values['editorId'], PDO::PARAM_STR);

        $stmn->execute();

        $stmn->closeCursor();

        return $this->db->database->lastInsertId();
    }

    public function patchNote($id,$params)
    {

//        $this->addTicketChange($_SESSION['userdata']['id'], $id, $params);

        $sql = "UPDATE zp_notes SET ";

        foreach($params as $key=>$value){
            $sql .= "".core\db::sanitizeToColumnString($key)."=:".core\db::sanitizeToColumnString($key).", ";
        }

        $sql .= "id=:id WHERE id=:id LIMIT 1";

        $stmn = $this->db->database->prepare($sql);
        $stmn->bindValue(':id', $id, PDO::PARAM_STR);

        foreach($params as $key=>$value){
            $stmn->bindValue(':'.core\db::sanitizeToColumnString($key), $value, PDO::PARAM_STR);
        }

        $return = $stmn->execute();
        $stmn->closeCursor();

        return $return;
    }

    public function updateNote(array $values, $id)
    {

//        $this->addTicketChange($_SESSION['userdata']['id'], $id, $values);

        $query = "UPDATE zp_note
			SET 
				headline = :headline,
				description=:description,
				projectId=:projectId, 
				tags = :tags
			WHERE id = :id LIMIT 1";

        $stmn = $this->db->database->prepare($query);

        $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
        $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
        $stmn->bindValue(':projectId', $values['projectId'], PDO::PARAM_STR);
        $stmn->bindValue(':tags', $values['tags'], PDO::PARAM_STR);
        $stmn->bindValue(':id', $id, PDO::PARAM_STR);


        $result = $stmn->execute();

        $stmn->closeCursor();

        return $result;
    }

    /**
     * deleteNote - delete a Note and all dependencies
     *
     * @access public
     * @param  $id
     */
    public function deleteNote($id)
    {

        $query = "DELETE FROM zp_note WHERE id = :id";

        $stmn = $this->db->database->prepare($query);
        $stmn->bindValue(':id', $id, PDO::PARAM_STR);
        $result = $stmn->execute();
        $stmn->closeCursor();

        return $result;
    }
}