<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    use \DateTime;
    use \DateInterval;


    class editProjectrole
    {

        private $tpl;
        private $projectroleService;

        /**
         * constructor - initialize private variables
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function __construct()
        {

            $this->tpl = new core\template();
            $this->projects = new repositories\projects();
            $this->projectroleService = new services\projectroles();
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
            if(isset($params['id'])) {
                $projectrole = $this->projectroleService->getProjectrole($params['id']);
                $this->tpl->assign('leads', $this->projectroleService->getLeads($params['id']));
            }else{
                $projectrole = new models\projectroles();
                $this->tpl->assign('leads', $this->projectroleService->getAllProjectroles());
            }

            $this->tpl->assign('projectrole', $projectrole);
            $this->tpl->displayPartial('projectroles.projectroleDialog');
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function post($params)
        {
            if(isset($_GET['id']) && $_GET['id'] > 0) {

                $params['id'] = (int)$_GET['id'];

                if ($this->projectroleService->quickAUpdateProjectrole($params) == true) {

                    $this->tpl->setNotification("Projectrole edited successfully", "success");

                } else {

                    $this->tpl->setNotification("There was a problem saving the projectrole", "error");

                }
                $this->tpl->assign('leads', $this->projectroleService->getLeads($params['id']));

            }else{

                if ($this->projectroleService->quickAddProjectrole($params) == true) {

                    $this->tpl->setNotification("Projectrole created successfully", "success");

                } else {

                    $this->tpl->setNotification("There was a problem saving the projectrole", "error");

                }
                $this->tpl->assign('leads', $this->projectroleService->getAllProjectroles());
            }
            $this->tpl->assign('projectrole', (object) $params);
            $this->tpl->displayPartial('projectroles.projectroleDialog');
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