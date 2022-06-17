<?php

namespace leantime\domain\repositories {

    use leantime\core;
    use pdo;

    class projectroles
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
        public $projectroles = null;

        /**
         * @access private
         * @var    object
         */
        private $db='';

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
         * @var    string
         */
        public $numPages='';

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

        /**
         * getAllProjectroles - get all Projectroles
         *
         * @access public
         * @return array
         */
        public function getAllProjectroles()
        {

             $query = "SELECT
                        p.id,
                        p.name,
                        p.leadId,
                        (SELECT l.name FROM zp_projectrole as l WHERE l.id=p.leadId) as leadName
                        FROM 
                        zp_projectrole as p
                    ";

            $stmn = $this->db->database->prepare($query);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\projectroles');
            $stmn->closeCursor();

            return $values;
        }

        /**
         * getLeads - get all Projectroles, depending id
         *
         * @access public
         * @return array
         */
        public function getLeads($id)
        {

             $query = "SELECT
                        p.id,
                        p.name,
                        p.leadId,
                        (SELECT l.name FROM zp_projectrole as l WHERE l.id=p.leadId) as leadName
                        FROM 
                        zp_projectrole as p
                        WHERE
                        p.id != :id
                    ";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\projectroles');
            $stmn->closeCursor();

            return $values;
        }

        /**
         * getProjectrole - get a specific Projectrole
         *
         * @access public
         * @param  $id
         * @return \leantime\domain\models\projectroles|bool
         */
        public function getProjectrole($id)
        {

            $query = "SELECT
                        p.id,
                        p.name,
                        p.leadId,
                        (SELECT l.name FROM zp_projectrole as l WHERE l.id=p.leadId) as leadName 
                    FROM 
                        zp_projectrole as p
                    WHERE 
                        p.id = :projectroleId
                    GROUP BY
                        p.id                       
                    LIMIT 1";


            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':projectroleId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\projectroles');
            $stmn->closeCursor();

            return $values;

        }

        public function getNumberOfAllProjectroles()
        {

            $query = "SELECT
                        COUNT(zp_projectrole.id) AS allProjectroles
                    FROM 
                        zp_projectrole
                    LIMIT 1";

            $stmn = $this->db->database->prepare($query);

            $stmn->execute();

            $values = $stmn->fetch();
            $stmn->closeCursor();

            return $values['allProjectroles'];

        }

        /**
         * addProjectrole - add a Projectrole with postback test
         *
         * @access public
         * @param  array $values
         * @return boolean|int
         */
        public function addProjectrole(array $values)
        {


            $query = "INSERT INTO zp_projectrole (
                        name,
                        leadId
                    ) VALUES (
                        :name,
                        :leadId
                )";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':name', $values['name'], PDO::PARAM_STR);
            $stmn->bindValue(':leadId', $values['leadId'], PDO::PARAM_STR);
        
            $stmn->execute();

            $stmn->closeCursor();

            return $this->db->database->lastInsertId();

        }

        /**
         * updateProjectrole - Update Projectroleinformation
         *
         * @access public
         * @param  array $values
         * @param  $id
         */
        public function updateProjectrole(array $values, $id)
        {

            $query = "UPDATE zp_projectrole
            SET 
                name = :name,
                leadId = :leadId
            WHERE id = :id LIMIT 1";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':name', $values['name'], PDO::PARAM_STR);
            $stmn->bindValue(':leadId', $values['leadId'], PDO::PARAM_STR);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);

            $result = $stmn->execute();

            $stmn->closeCursor();

            return $result;
        }

        /**
         * delProjectrole - delete a Projectrole and all dependencies
         *
         * @access public
         * @param  $id
         */
        public function delProjectrole($id)
        {

            $query = "DELETE FROM zp_projectrole WHERE id = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;

        }

    }

}
