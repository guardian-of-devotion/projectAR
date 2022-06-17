<?php

namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class ticketTemplates
    {

        private $ticketTemplateRepository;
        private $ticketRepository;
        private $markerRepository;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->ticketRepository = new repositories\tickets();
            $this->ticketTemplateRepository = new repositories\ticketTemplates();
            $this->markerRepository = new repositories\markers();
            $this->language = new core\language();
        }

        public function getAllTicketTemplates($answerId) {

            return $this->ticketTemplateRepository->getAllTicketTemplates($answerId);
        }

        public function getTicketTemplate($id)
        {

            return $this->ticketTemplateRepository->getTicketTemplate($id);
        }

        public function addTicketTemplate($values, $answerId)
        {   
            $values = array(
                'headline' => $values['headline'],
                'type' => $values['type'],
                'description' => $values['description'],
                'planHours' => $values['planHours'],
                'tags' => $values['tags'],
                'storypoints' => $values['storypoints'],
                'priority' => $values['priority'],
                'markers' => $values['markers'],
                'acceptanceCriteria' => $values['acceptanceCriteria'],
                'answerId' => $answerId,
                'dependingTicketId' => $values['dependingTicketId']
            );

            if ($values['headline'] === '') {

                return array("msg" => "notifications.ticket_save_error_no_headline", "type" => "error");

            }

            return $this->ticketTemplateRepository->addTicketTemplate($values, $answerId);
        }

        //Update
        public function updateTicketTemplate($id, $values)
        {
            $values = array(
                'id' => $id,
                'headline' => $values['headline'],
                'type' => $values['type'],
                'description' => $values['description'],
                'planHours' => $values['planHours'],
                'tags' => $values['tags'],
                'storypoints' => $values['storypoints'],
                'priority' => $values['priority'],
                'markers' => $values['markers'],
                'acceptanceCriteria' => $values['acceptanceCriteria'],
                'dependingTicketId' => $values['dependingTicketId']
            );

            if ($values['headline'] === '') {

                $error = array("status" => "error", "message" => "Headline Missing");
                return $error;

 			}
            
            return $this->ticketTemplateRepository->updateTicketTemplate($values, $id);

        }

        //Delete
        public function deleteTicketTemplate($id){

            if ($this->ticketTemplateRepository->deleteTicketTemplate($id)) {
                return true;
            }

            return false;

        }

        public function getAllSubtasks($ticketId)
        {
           return $this->ticketTemplateRepository->getAllSubtasks($ticketId);
        }

        public function upsertSubtask($values, $parentTicket)
        {

            $subtaskId = $values['subtaskId'];

            $values = array(
                'headline' => $values['headline'],
                'type' => 'subtask',
                'description' => $values['description'],
                'planHours' => $values['planHours'],
                'dependingTicketId' => $parentTicket->id,
            );

            if ($subtaskId == "new" || $subtaskId == "") {

                //New Ticket
                if (!$this->ticketTemplateRepository->addTicketTemplate($values, $parentTicket->answerId)) {
                    return false;
                }

            } else {

                //Update Ticket

                if (!$this->ticketTemplateRepository->updateTicketTemplate($values, $subtaskId)) {
                    return false;
                }

            }

            return true;

        }

        public function getAllTicketTemplatesForQuestion($questionId) {
            $ticketTemplates = [];

            $answersRepo = new repositories\answers();
            $answers = $answersRepo->getAllAnswers($questionId);

            foreach ($answers as $key => $value) {
                $tickets = $this->ticketTemplateRepository->getAllTicketTemplates($value->id);

                if (count($tickets) > 0) {
                    $ticketTemplates[$value->id] = $tickets;
                }
            }

            return $ticketTemplates;
        }

        public function getAllTicketTemplatesForCheckList($checkListId) {
            $ticketTemplates = [];

            $questionRepo = new repositories\questions();
            $questions = $questionRepo->getAllQuestions($checkListId);
            $answersRepo = new repositories\answers();

            foreach ($questions as $key => $value) {
                $answersForQuestion = $answersRepo->getAllAnswers($value->id);

                if (count($answersForQuestion) > 0) {
                    foreach ($answersForQuestion as $k => $v) {
                        $tickets = $this->ticketTemplateRepository->getAllTicketTemplates($v->id);

                        if (count($tickets) > 0) {
                            $ticketTemplates[$v->id] = $tickets;
                        }
                    }
                }
            }

            return $ticketTemplates;
        }

    }

}
