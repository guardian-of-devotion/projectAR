<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\repositories {

    use leantime\core;
    use pdo;

    class checkLists
    {
        public function __construct()
        {
            $this->db = core\db::getInstance();
            $this->language = new core\language();
        }

        public function getAllCheckLists()
        {
            $sql = "SELECT
                        id,
                        headline,
                        description
                    FROM zp_check_lists
                    ORDER BY id DESC";

            $stmn = $this->db->database->prepare($sql);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\checkLists');
            $stmn->closeCursor();

            return $values;

        }

        public function getCheckList($id)
        {
            $sql = "SELECT
                        id,
                        headline,
                        description
                    FROM zp_check_lists
                    WHERE id = :checkListId";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':checkListId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\checkLists');
            $stmn->closeCursor();

            return $values;
        }

        public function addCheckList(array $values)
        {
            $query = "INSERT INTO zp_check_lists (
						headline,
						description
                    ) VALUES (
						:headline,
						:description
				    )";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);

            $stmn->execute();
            $stmn->closeCursor();

            return $this->db->database->lastInsertId();
        }

        public function updateCheckList(array $values, $id)
        {
            $query = "UPDATE zp_check_lists
                        SET 
                            headline = :headline,
                            description = :description
                        WHERE
                            id = :id";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);

            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }


        public function deleteCheckList($id)
        {
            $sql = "DELETE FROM zp_check_lists WHERE id = :id";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }
    }
}