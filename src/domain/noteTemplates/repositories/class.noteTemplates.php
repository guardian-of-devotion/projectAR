<?php


/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\repositories;

use leantime\core;
use pdo;

class noteTemplates
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

    /**
         * getAllNoteTemplates - get all NoteTemplates, depending on answerId
         *
         * @access public
         * @return array
         */
        public function getAllNoteTemplates($answerId)
        {
            $sql = "SELECT
                        id,
                        headline, 
                        description,
                        tags,                    
                        answerId
                    FROM zp_note_template
                    WHERE answerId = :answerId
                    ORDER BY id ASC";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':answerId', $answerId, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\noteTemplates');
            $stmn->closeCursor();

            return $values;
        }

        /**
         * getNoteTemplate - get a NoteTemplate
         *
         * @access public
         * @param  $id
         * @return \leantime\domain\models\noteTemplates|bool
         */
        public function getNoteTemplate($id)
        {

            $query = "SELECT
                        id,
                        headline, 
                        description,
                        tags,                    
                        answerId
                    FROM zp_note_template
                    WHERE 
                        id = :id
                    GROUP BY
                        id                       
                    LIMIT 1";


            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\noteTemplates');
            $stmn->closeCursor();

            return $values;

        }

    /**
     *
     * @access public
     * @param  array $values
     * @return boolean|int
     */
    public function addNoteTemplate(array $values, $answerId)
    {
        $query = "INSERT INTO zp_note_template (
                        headline, 
                        description,  
                        status,  
                        tags, 
                        answerId
                    ) VALUES (
                        :headline,
                        :description,
                        :status,
                        :tags,
                        :answerId
                )";

        $stmn = $this->db->database->prepare($query);

        $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
        $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
        $stmn->bindValue(':status', $values['status'], PDO::PARAM_STR);
        $stmn->bindValue(':tags', $values['tags'], PDO::PARAM_STR);
        $stmn->bindValue(':answerId', $answerId, PDO::PARAM_STR);

        $stmn->execute();

        $stmn->closeCursor();

        return $this->db->database->lastInsertId();
    }

    public function updateNoteTemplate(array $values, $id)
    {
        $query = "UPDATE zp_note_template
            SET 
                headline = :headline,
                description=:description,
                answerId = :answerId
            WHERE id = :id LIMIT 1";

        $stmn = $this->db->database->prepare($query);

        $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
        $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
        $stmn->bindValue(':tags', $values['tags'], PDO::PARAM_STR);
        $stmn->bindValue(':id', $id, PDO::PARAM_STR);


        $result = $stmn->execute();

        $stmn->closeCursor();

        return $result;
    }

    /**
     * deleteNoteTemplate - delete a Note and all dependencies
     *
     * @access public
     * @param  $id
     */
    public function deleteNoteTemplate($id)
    {

        $query = "DELETE FROM zp_note_template WHERE id = :id";

        $stmn = $this->db->database->prepare($query);
        $stmn->bindValue(':id', $id, PDO::PARAM_STR);
        $result = $stmn->execute();
        $stmn->closeCursor();

        return $result;
    }
}