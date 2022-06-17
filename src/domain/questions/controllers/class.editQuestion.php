<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class editQuestion
    {

        private $tpl;
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
            $this->questionService = new services\questions();
            $this->answerService = new services\answers();
            $this->ticketTemplateService = new services\ticketTemplates();
            $this->noteTemplateService = new services\noteTemplates();
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
            $question = $this->questionService->getQuestion($params['id']);
            $answers = $this->answerService->getAllAnswers($params['id']);
            $noteTemplates = $this->noteTemplateService->getAllNoteTemplatesForQuestion($params['id']);
            $ticketTemplates = $this->ticketTemplateService->getAllTicketTemplatesForQuestion($params['id']);

            $this->tpl->assign('question', $question);
            $this->tpl->assign('answers', $answers);
            $this->tpl->assign('noteTemplates', $noteTemplates);
            $this->tpl->assign('ticketTemplates', $ticketTemplates);

            $this->tpl->display('questions.editQuestion');
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

            if ($this->questionService->updateQuestion($params) == true) {
                $this->tpl->setNotification("Question edited successfully", "success");
                $this->tpl->assign('question', $this->questionService->getQuestion($params['id']));
            } else {
                $this->tpl->setNotification("There was a problem saving the question", "error");
            }

            $this->tpl->assign('question', (object) $params);
            $this->tpl->display('questions.editQuestion');
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