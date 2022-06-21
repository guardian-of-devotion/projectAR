<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\repositories {

    use leantime\core;
    use pdo;

    class markers
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
        public $markers = null;

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
         * getAll - get all Markers, depending on userrole
         *
         * @access public
         * @return array
         */
        public function getAllMarkers($limit = 9999)
        {

             $query = "SELECT
                        m.id,
                        m.name,
                        m.projectroleId,
                        m.relatedMarkerId,
                        (SELECT rm.name FROM zp_marker as rm WHERE rm.id=m.relatedMarkerId) as relatedMarker,
                        zp_projectrole.name as projectrole
                        FROM 
                        zp_marker m
                        LEFT JOIN zp_projectrole on m.projectroleId=zp_projectrole.id
                    ";

            $stmn = $this->db->database->prepare($query);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\markers');
            $stmn->closeCursor();

            return $values;
        }

        /**
         * getMarker - get a specific Marker depending on the role
         *
         * @access public
         * @param  $id
         * @return \leantime\domain\models\tickets|bool
         */
        public function getMarker($id)
        {

            $query = "SELECT
                        m.id,
                        m.name,
                        m.projectroleId,
                        m.relatedMarkerId,
                        (SELECT rm.name FROM zp_marker as rm WHERE rm.id=m.relatedMarkerId) as relatedMarker,
                        zp_projectrole.name as projectrole
                    FROM 
                        zp_marker m
                    LEFT JOIN zp_projectrole on m.projectroleId=m.id
                    WHERE 
                        m.id = :markerId
                    GROUP BY
                        m.id                       
                    LIMIT 1";


            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':markerId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\tickets');
            $stmn->closeCursor();

            return $values;

        }

        public function getNumberOfAllMarkers()
        {

            $query = "SELECT
                        COUNT(zp_marker.id) AS allMarkers
                    FROM 
                        zp_marker
                    LIMIT 1";

            $stmn = $this->db->database->prepare($query);

            $stmn->execute();

            $values = $stmn->fetch();
            $stmn->closeCursor();

            return $values['allMarkers'];

        }

        /**
         * addMarker - add a Marker with postback test
         *
         * @access public
         * @param  array $values
         * @return boolean|int
         */
        public function addMarker(array $values)
        {


            $query = "INSERT INTO zp_marker (
                        name,
                        projectroleId,
                        relatedMarkerId
                    ) VALUES (
                        :name,
                        :projectroleId,
                        :relatedMarkerId
                )";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':name', $values['name'], PDO::PARAM_STR);
            $stmn->bindValue(':projectroleId', $values['projectroleId'], PDO::PARAM_STR);
            $stmn->bindValue(':relatedMarkerId', $values['relatedMarkerId'], PDO::PARAM_STR);
            $stmn->execute();

            $stmn->closeCursor();

            return $this->db->database->lastInsertId();

        }

        /**
         * updateMarker - Update Markerinformation
         *
         * @access public
         * @param  array $values
         * @param  $id
         */
        public function updateMarker(array $values, $id)
        {

            $query = "UPDATE zp_marker
            SET 
                name = :name,
                projectroleId = :projectroleId,
                relatedMarkerId =:relatedMarkerId
            WHERE id = :id LIMIT 1";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':name', $values['name'], PDO::PARAM_STR);
            $stmn->bindValue(':projectroleId', $values['projectroleId'], PDO::PARAM_STR);
            $stmn->bindValue(':relatedMarkerId', $values['relatedMarkerId'], PDO::PARAM_STR);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);

            $result = $stmn->execute();

            $stmn->closeCursor();

            return $result;
        }

        /**
         * delMarker - delete a Marker and all dependencies
         *
         * @access public
         * @param  $id
         */
        public function delMarker($id)
        {
            $query = "DELETE FROM zp_marker WHERE id = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;

        }

    }

}
