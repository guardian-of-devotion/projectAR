<?php

namespace leantime\domain\repositories;

use leantime\core;
use pdo;

class storyPoints
{

    /**
     * @access private
     * @var    object
     */
    private $db='';
    public function __construct()
    {

        $this->db = core\db::getInstance();
        $this->language = new core\language();

    }

    private $efforts = [
        1 => 'XXS',
        2 => 'XS',
        3 => 'S',
        5 => 'M',
        8 => 'L',
        13 => 'L',
        21 => 'XXL'
    ];

    public function getStoryPointsByProject($projectId)
    {
        $sql = <<<SQL
SELECT
    id,
    effort_hours,
    effort_value
FROM zp_efforts_hours
WHERE project_id = :projectId;
SQL;
        $stmn = $this->db->database->prepare($sql);
        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();
        return $values;
    }

    public function getStoryPointByEffortValue($projectId, $effortValue)
    {
        $sql = <<<SQL
SELECT
    id,
    effort_hours,
    effort_value
FROM zp_efforts_hours
WHERE project_id = :projectId
    AND effort_value = :effortValue 
SQL;
        $stmn = $this->db->database->prepare($sql);
        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmn->bindValue(':effortValue', $effortValue, PDO::PARAM_INT);

        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();
        return $values;
    }
    public function updateStoryPointsCostInTime($id, $effortHours)
    {
        $sql = <<<SQL
UPDATE zp_efforts_hours SET
    effort_hours = :effortHours
WHERE id = :id
SQL;
        $stmn = $this->db->database->prepare($sql);
        $stmn->bindValue(':effortHours', $effortHours, PDO::PARAM_STR);
        $stmn->bindValue(':id', $id, PDO::PARAM_INT);

        $stmn->execute();
        $stmn->closeCursor();
    }

    public function insertStoryPointsCostInTime($projectId, $effort_value, $effort_hours)
    {
        $sql = <<<SQL
INSERT INTO zp_efforts_hours(effort_value, effort_hours, project_id) 
VALUES (:effort_value, :effort_hours, :projectId);
SQL;
        $stmn = $this->db->database->prepare($sql);
        $stmn->bindValue(':effort_hours', $effort_hours, PDO::PARAM_STR);
        $stmn->bindValue(':effort_value', $effort_value, PDO::PARAM_INT);
        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

        $stmn->execute();
        $stmn->closeCursor();
    }
}