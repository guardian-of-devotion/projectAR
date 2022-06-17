<?php

namespace leantime\domain\repositories {

    use leantime\core;
    use pdo;

    class answers
    {
        public function __construct()
        {
            $this->db = core\db::getInstance();
            $this->language = new core\language();
        }

        public function getAllAnswers($questionId)
        {
            $sql = "SELECT
                        id,
                        answerText,
                        questionId
                    FROM zp_answer
                    WHERE questionId = :questionId
                    ORDER BY id ASC";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':questionId', $questionId, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\answers');
            $stmn->closeCursor();

            return $values;

        }

        public function getAnswer($id)
        {
            $sql = "SELECT
                        id,
                   		answerText,
                        questionId
                    FROM zp_answer
                    WHERE id = :questionId";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':questionId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\answers');
            $stmn->closeCursor();

            return $values;
        }

        public function addAnswer(array $values, $questionId)
        {
            $query = "INSERT INTO zp_answer (
						answerText,
						questionId
                    ) VALUES (
						:answerText,
						:questionId
				    )";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':answerText', $values['answerText'], PDO::PARAM_STR);
            $stmn->bindValue(':questionId', $questionId, PDO::PARAM_STR);

            $stmn->execute();
            $stmn->closeCursor();

            return $this->db->database->lastInsertId();
        }

        public function updateAnswer(array $values, $id)
        {
            $query = "UPDATE zp_answer
                        SET 
                            answerText = :answerText
                        WHERE
                            id = :id";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':answerText', $values['answerText'], PDO::PARAM_STR);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);

            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }


        public function deleteAnswer($id)
        {
            $sql = "DELETE FROM zp_answer WHERE id = :id";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;
        }
    }
}