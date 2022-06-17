<?php

namespace leantime\domain\repositories {

    use leantime\core;
    use pdo;

    class questions
    {
        public function __construct()
        {
            $this->db = core\db::getInstance();
            $this->language = new core\language();
        }

        public function getAllQuestions($checkListId)
        {
            $sql = "SELECT
                        id,
                        headline,
                        questionText,
                        checkListId
                    FROM zp_question
                    WHERE checkListId = :checkListId
                    ORDER BY id ASC";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':checkListId', $checkListId, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\questions');
            $stmn->closeCursor();

            return $values;

        }

        public function getQuestion($id)
        {
            $sql = "SELECT
                        id,
                        headline,
                        questionText,
                        checkListId
                    FROM zp_question
                    WHERE id = :checkListId";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':checkListId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\questions');
            $stmn->closeCursor();

            return $values;
        }

        public function addQuestion(array $values, $checkListId)
        {
            $query = "INSERT INTO zp_question (
						headline,
						questionText,
                        checkListId
                    ) VALUES (
						:headline,
						:questionText,
                        :checkListId
				    )";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':questionText', $values['questionText'], PDO::PARAM_STR);
            $stmn->bindValue(':checkListId', $checkListId, PDO::PARAM_INT);

            $stmn->execute();
            $stmn->closeCursor();

            return $this->db->database->lastInsertId();
        }

        public function updateQuestion(array $values, $id)
        {
            $query = "UPDATE zp_question
                        SET 
                            headline = :headline,
                            questionText = :questionText
                        WHERE
                            id = :id";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':questionText', $values['questionText'], PDO::PARAM_STR);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);

            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }


        public function deleteQuestion($id)
        {
            $sql = "DELETE FROM zp_question WHERE id = :id";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }
    }
}