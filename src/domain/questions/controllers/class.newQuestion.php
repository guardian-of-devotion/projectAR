<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class newQuestion
    {

        private $tpl;
        private $questionService;

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
            $question = new models\questions();

            $this->tpl->assign('question', $question);
            $this->tpl->display('questions.newQuestion');
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function post($params)
        {
            $checkListId = (int)$_GET['id'];

            $id = $this->questionService->addQuestion($params, $checkListId);

            if ($id == true) {
                $params['id'] = $id;
                
                $this->tpl->setNotification("Question created successfully", "success");
                $this->tpl->redirect(BASE_URL."/questions/editQuestion/".$id);
            
            } else {
                $this->tpl->setNotification("There was a problem saving the question", "error");
                
                $this->tpl->assign('question', (object) $params);
            	$this->tpl->display('questions.newQuestion');

            }
            
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