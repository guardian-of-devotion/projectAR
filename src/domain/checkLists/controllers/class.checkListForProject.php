<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class checkListForProject
    {

        private $tpl;
        private $checkListService;
        private $questionService;
        private $answerService;
        private $noteTemplateService;
        private $ticketTemplateService;
        private $ticketService;
        private $noteService;
        private $fileService;

        /**
         * constructor - initialize private variables
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function __construct()
        {

            $this->tpl = new core\template();
            $this->checkListService = new services\checkLists();
            $this->questionService = new services\questions();
            $this->answerService = new services\answers();
            $this->noteTemplateService = new services\noteTemplates();
            $this->ticketTemplateService = new services\ticketTemplates();
            $this->fileService = new services\files();
            $this->noteService = new services\notes();
            $this->ticketService = new services\tickets();

            $this->language = new core\language();

        }

        /**
         * get - handle get requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function get($params)
        {  
            $checkList = $this->checkListService->getCheckList($params['id']);
            $questions = $this->questionService->getAllQuestions($params['id']);
            $answers = $this->answerService->getAllAnswersForCheckList($params['id']);

            $this->tpl->assign('checkList', $checkList);
            $this->tpl->assign('questions', $questions);
            $this->tpl->assign('answers', $answers);
            $this->tpl->display('checkLists.checkListForProject');
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function post($params)
        {
            $projectId = $_SESSION['currentProject'];

            foreach ($params as $question => $answers) {
                foreach ($answers as $answer) {
                    $noteTemplates = $this->noteTemplateService->getAllNoteTemplates($answer);
                    $ticketTemplates = $this->ticketTemplateService->getAllTicketTemplates($answer);

                    foreach ($noteTemplates as $noteTemplate) {
                        $id = $this->noteService->quickAddNote((array) $noteTemplate);

                        $files = $this->fileService->getFilesByModule('noteTemplate', $noteTemplate->id); 
                        foreach ($files as $file) {
                            $this->fileService->copyFile("note", $id, $file);
                        }
                    } 

                    foreach ($ticketTemplates as $ticketTemplate) {
                        $ticketTemplate->hourRemaining = 0;
                        $ticketTemplate->dateToFinish = 0;
                        $ticketTemplate->editFrom = 0;
                        $ticketTemplate->editTo = 0;

                        $ids = $this->ticketService->addTicket((array) $ticketTemplate, json_decode($ticketTemplate->markers));

                        foreach ($ids as $ticketId) {
                            $subTasks = $this->ticketTemplateService->getAllSubtasks($ticketTemplate->id);
                            foreach ($subTasks as $subTask) {
                                $this->ticketService->upsertSubtask((array) $subTask, $this->ticketService->getTicket($ticketId));
                            }

                            $files = $this->fileService->getFilesByModule('ticketTemplate', $ticketTemplate->id); 
                            foreach ($files as $file) {
                                $this->fileService->copyFile("ticket", $ticketId, $file);
                            }
                            
                        }
                    }       
                }
            }

            $this->tpl->redirect(BASE_URL."/projects/showProject/". $projectId);
        }

        /**
         * put - handle put requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function put($params)
        {

        }

        /**
         * delete - handle delete requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function delete($params)
        {

        }

    }

}