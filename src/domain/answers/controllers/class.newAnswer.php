<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class newAnswer
    {

        private $tpl;
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
            $this->answerService = new services\answers();
            $this->language = new core\language();

        }

        /**
         * get - handle get requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function get()
        {
            $answer = new models\answers();

            $this->tpl->assign('answer', $answer);
            $this->tpl->display('answers.newAnswer');
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function post($params)
        {
            $questionId = (int)$_GET['id'];

            $id = $this->answerService->addAnswer($params, $questionId);

            if ($id == true) {
                $params['id'] = $id;
                
                $this->tpl->setNotification("Answer created successfully", "success");
                $this->tpl->redirect(BASE_URL."/answers/editAnswer/".$id);
            
            } else {
                $this->tpl->setNotification("There was a problem saving the answer", "error");
                
                $this->tpl->assign('answer', (object) $params);
            	$this->tpl->display('answers.newAnswer');

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