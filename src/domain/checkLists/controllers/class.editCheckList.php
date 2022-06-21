<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class editCheckList
    {

        private $tpl;
        private $checkListService;
        private $questionService;
        private $answerService;

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
            $this->ticketTemplateService = new services\ticketTemplates();
            $this->noteTemplateService = new services\noteTemplates();

            $this->language = new core\language();
            $_SESSION['lastPage'] = CURRENT_URL;

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
            $noteTemplates = $this->noteTemplateService->getAllNoteTemplatesForCheckList($params['id']);
            $ticketTemplates = $this->ticketTemplateService->getAllTicketTemplatesForCheckList($params['id']);

            $this->tpl->assign('checkList', $checkList);
            $this->tpl->assign('questions', $questions);
            $this->tpl->assign('answers', $answers);
            $this->tpl->assign('noteTemplates', $noteTemplates);
            $this->tpl->assign('ticketTemplates', $ticketTemplates);
            $this->tpl->display('checkLists.editCheckList');
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function post($params)
        {
            $params['id'] = (int)$_GET['id'];

            if ($this->checkListService->updateCheckList($params) == true) {

                $this->tpl->setNotification("CheckList edited successfully", "success");
                $this->tpl->assign('checkList', $this->checkListService->getCheckList($params['id']));

            } else {

                $this->tpl->setNotification("There was a problem saving the checkList", "error");

            }

            $this->tpl->assign('checkList', (object) $params);
            $this->tpl->display('checkLists.editCheckList');
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