<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class editAnswer
    {

        private $tpl;
        private $answerService;
        private $ticketTemplatesService;
        private $noteTemplateService;
        private $ticketService;

        /**
         * constructor - initialize private variables
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function __construct()
        {

            $this->tpl = new core\template();
            $this->answerService = new services\answers();
            $this->ticketTemplatesService = new services\ticketTemplates();
            $this->noteTemplateService = new services\noteTemplates();
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
            $answer = $this->answerService->getAnswer($params['id']);
            $ticketTemplates = $this->ticketTemplatesService->getAllTicketTemplates($params['id']);
            $noteTemplates = $this->noteTemplateService->getAllNoteTemplates($params['id']);

            $this->tpl->assign('answer', $answer);
            $this->tpl->assign('ticketTemplates', $ticketTemplates);
            $this->tpl->assign('noteTemplates', $noteTemplates);
            $this->tpl->assign('ticketTypes', $this->ticketService->getTicketTypes());
            $this->tpl->assign('efforts', $this->ticketService->getEffortLabels());
            $this->tpl->assign('priorities', $this->ticketService->getPriorityLabels());
            $this->tpl->assign('markers', $this->ticketService->getMarkers());

            $this->tpl->display('answers.editAnswer');
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

            if ($this->answerService->updateAnswer($params) == true) {
                $this->tpl->setNotification("Answer edited successfully", "success");
                $this->tpl->assign('answer', $this->answerService->getAnswer($params['id']));
            } else {
                $this->tpl->setNotification("There was a problem saving the answer", "error");
            }

            $this->tpl->assign('answer', (object) $params);

            $this->tpl->assign('ticketTypes', $this->ticketService->getTicketTypes());
            $this->tpl->assign('efforts', $this->ticketService->getEffortLabels());
            $this->tpl->assign('priorities', $this->ticketService->getPriorityLabels());
            $this->tpl->assign('markers', $this->ticketService->getMarkers());
            $this->tpl->display('answers.editAnswer');
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