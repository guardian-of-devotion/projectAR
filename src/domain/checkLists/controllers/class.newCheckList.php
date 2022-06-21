<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class newCheckList
    {

        private $tpl;
        private $checkListService;
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
            $this->checkListService = new services\checkLists();
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
            $checkList = new models\checkLists();

            $this->tpl->assign('checkList', $checkList);
            $this->tpl->display('checkLists.newCheckList');
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function post($params)
        {

            $id = $this->checkListService->addCheckList($params);
            if ($id == true) {
                $params['id'] = $id;
                $this->tpl->setNotification("CheckList created successfully", "success");
                $this->tpl->redirect(BASE_URL."/checkLists/editCheckList/".$id);

            } else {

                $this->tpl->setNotification("There was a problem saving the checkList", "error");
                $this->tpl->assign('checkList', (object) $params);
            	$this->tpl->display('checkLists.newCheckList');
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