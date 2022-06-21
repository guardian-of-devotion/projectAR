<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class showAll
    {

        private $tpl;
        private $projectroleService;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->projectroleService = new services\projectroles();

            $_SESSION['lastPage'] = CURRENT_URL;


        }

        public function get($params) {

            $this->tpl->assign('allProjectroles', $this->projectroleService->getAllProjectroles());

            $this->tpl->display('projectroles.showAll');

        }



    }

}
