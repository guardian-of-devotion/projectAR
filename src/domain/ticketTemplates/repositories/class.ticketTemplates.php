<?php

namespace leantime\domain\repositories {

    use leantime\core;
    use pdo;

    class ticketTemplates
    {

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
         * getAllTicketTemplates - get all TicketTemplates, depending on answerId
         *
         * @access public
         * @return array
         */
        public function getAllTicketTemplates($answerId)
        {
	 	    $sql = "SELECT
						zp_ticket_template.id,
						zp_ticket_template.headline, 
						zp_ticket_template.type,
						zp_ticket_template.description,
						zp_ticket_template.priority,
                        zp_ticket_template.markers,
						zp_ticket_template.storypoints,
						zp_ticket_template.acceptanceCriteria,
						zp_ticket_template.planHours,
						zp_ticket_template.tags,					
						zp_ticket_template.answerId,
                        zp_ticket_template.dependingTicketId
                    FROM zp_ticket_template
                    WHERE zp_ticket_template.answerId = :answerId
                    ORDER BY id ASC";

            $stmn = $this->db->database->prepare($sql);
            $stmn->bindValue(':answerId', $answerId, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll(PDO::FETCH_CLASS, 'leantime\domain\models\ticketTemplates');
            $stmn->closeCursor();

            return $values;
        }

        /**
         * getTicketTemplate - get a TicketTemplate
         *
         * @access public
         * @param  $id
         * @return \leantime\domain\models\ticketstemplate|bool
         */
        public function getTicketTemplate($id)
        {

            $query = "SELECT
						zp_ticket_template.id,
						zp_ticket_template.headline, 
						zp_ticket_template.type,
						zp_ticket_template.description,
						zp_ticket_template.priority,
                        zp_ticket_template.markers,
						zp_ticket_template.storypoints,
						zp_ticket_template.acceptanceCriteria,
						zp_ticket_template.planHours,
						zp_ticket_template.tags,					
						zp_ticket_template.answerId,
                        zp_ticket_template.dependingTicketId
					FROM zp_ticket_template
					WHERE 
						zp_ticket_template.id = :tickettemplateId
					GROUP BY
						zp_ticket_template.id						
					LIMIT 1";


            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':tickettemplateId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchObject('\leantime\domain\models\ticketTemplates');
            $stmn->closeCursor();

            return $values;

        }

        /**
         * addTicket - add a Ticket with postback test
         *
         * @access public
         * @param  array $values
         * @return boolean|int
         */
        public function addTicketTemplate(array $values, $answerId)
        {
            $query = "INSERT INTO zp_ticket_template (
						headline, 
						type, 
						description,  
						tags, 
						storypoints,
						priority,
                        markers,
						planHours,
						acceptanceCriteria,
						answerId,
                        dependingTicketId
                    ) VALUES (
						:headline,
						:type,
						:description,
						:tags,
						:storypoints,
						:priority,
                        :markers,
						:planHours,
						:acceptanceCriteria,
						:answerId,
                        :dependingTicketId
				)";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':type', $values['type'], PDO::PARAM_STR);
            $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
            $stmn->bindValue(':tags', $values['tags'], PDO::PARAM_STR);
            $stmn->bindValue(':storypoints', $values['storypoints'], PDO::PARAM_STR);
            $stmn->bindValue(':priority', $values['priority'], PDO::PARAM_STR);
            $stmn->bindValue(':markers', json_encode($values['markers']), PDO::PARAM_STR);
            $stmn->bindValue(':planHours', $values['planHours'], PDO::PARAM_STR);
            $stmn->bindValue(':acceptanceCriteria', $values['acceptanceCriteria'], PDO::PARAM_STR);
            $stmn->bindValue(':answerId', $answerId, PDO::PARAM_STR);
            $stmn->bindValue(':dependingTicketId', $values['dependingTicketId'], PDO::PARAM_STR);

            $stmn->execute();

            $stmn->closeCursor();

            return $this->db->database->lastInsertId();

        }

        /**
         * updateTicket - Update Ticketinformation
         *
         * @access public
         * @param  array $values
         * @param  $id
         */
        public function updateTicketTemplate(array $values, $id)
        {

            $query = "UPDATE zp_ticket_template
			SET 
				headline = :headline,
				type = :type,
				description=:description,			
				storypoints = :storypoints,
				priority = :priority,
                markers = :markers,
				planHours = :planHours,
				tags = :tags,
				acceptanceCriteria = :acceptanceCriteria,
                dependingTicketId = :dependingTicketId
			WHERE id = :id LIMIT 1";

            $stmn = $this->db->database->prepare($query);

            $stmn->bindValue(':headline', $values['headline'], PDO::PARAM_STR);
            $stmn->bindValue(':type', $values['type'], PDO::PARAM_STR);
            $stmn->bindValue(':description', $values['description'], PDO::PARAM_STR);
            $stmn->bindValue(':storypoints', $values['storypoints'], PDO::PARAM_STR);
            $stmn->bindValue(':priority', $values['priority'], PDO::PARAM_STR);
            $stmn->bindValue(':markers', json_encode($values['markers']), PDO::PARAM_STR);
            $stmn->bindValue(':acceptanceCriteria', $values['acceptanceCriteria'], PDO::PARAM_STR);
            $stmn->bindValue(':planHours', $values['planHours'], PDO::PARAM_STR);
            $stmn->bindValue(':tags', $values['tags'], PDO::PARAM_STR);
            $stmn->bindValue(':dependingTicketId', $values['dependingTicketId'], PDO::PARAM_STR);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);

            $result = $stmn->execute();

            $stmn->closeCursor();

            return $result;
        }

        // public function deleteTicketTemplateMarkers($marker)
        // {
        //     $query = "UPDATE zp_ticket_template
        //         SET
        //             markerId = null
        //         WHERE markerId = :markerId";

        //     $stmn = $this->db->database->prepare($query);
        //     $stmn->bindValue(':markerId', $marker, PDO::PARAM_INT);
            
        //     $result = $stmn->execute();

        //     $stmn->closeCursor();

        //     return $result;

        // }

        /**
         * delTicket - delete a Ticket and all dependencies
         *
         * @access public
         * @param  $id
         */
        public function deleteTicketTemplate($id)
        {

            $query = "DELETE FROM zp_ticket_template WHERE id = :id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':id', $id, PDO::PARAM_STR);
            $result = $stmn->execute();
            $stmn->closeCursor();

            return $result;

        }

        public function getAllSubtasks($id)
        {

            $query = "SELECT
                        id,
                        headline,
                        type, 
                        description,
                        planHours,
                        dependingTicketId
                    FROM 
                        zp_ticket_template
                    WHERE 
                        dependingTicketId = :ticketId AND type = 'subtask'
                    GROUP BY
                        id";

            $stmn = $this->db->database->prepare($query);
            $stmn->bindValue(':ticketId', $id, PDO::PARAM_INT);

            $stmn->execute();
            $values = $stmn->fetchAll();
            $stmn->closeCursor();

            return $values;

        }
    }
}
