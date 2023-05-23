<?php

namespace leantime\domain\repositories;

use leantime\core;
use pdo;

class profLevel
{
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

    public function getAllProfLevels()
    {
        $sql = "SELECT
                   id,
                   name
                FROM 
                zp_proficiency_level AS prof_level
                ";

        $stmn = $this->db->database->prepare($sql);
        $stmn->execute();
        $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\profLevel');
        $stmn->closeCursor();

        return $values;
    }

    public function deleteAllUserProfLevel($userId)
    {
        $query = <<<SQL
DELETE FROM zp_user_projectrole_proficiency WHERE userId = :userId;
SQL;
        $stmn = $this->db->database->prepare($query);

        $stmn->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmn->execute();

        $stmn->closeCursor();
    }
    public function insertUserProfLevel($userId, $projectRoleId, $profLevelId)
    {
        $query = <<<SQL
INSERT INTO zp_user_projectrole_proficiency (
    userId,
    projectroleId,
    proficiencyLevelId
) VALUES (
  :userId,
  :procetroleId,
  :proficiencyLevelId
);
SQL;
        $stmn = $this->db->database->prepare($query);

        $stmn->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmn->bindValue(':procetroleId', $projectRoleId, PDO::PARAM_STR);
        $stmn->bindValue(':proficiencyLevelId', $profLevelId, PDO::PARAM_STR);

        $stmn->execute();

        $stmn->closeCursor();

        return $this->db->database->lastInsertId();
    }

    public function getUserProfLevel($userId)
    {
        $sql = <<<SQL
SELECT 
    *
FROM zp_user_projectrole_proficiency roleProfciency
WHERE roleProfciency.userId = :userId
SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmn->execute();
        $value = $stmn->fetchAll();
        $stmn->closeCursor();

        return $value;

    }
}