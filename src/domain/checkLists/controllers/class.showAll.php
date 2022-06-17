<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class showAll
    {

        private $checkListsService;
        private $tpl;

        public function __construct()
        {
            $this->checkListsService = new services\checkLists();
            $this->tpl = new core\template;
            $_SESSION['lastPage'] = CURRENT_URL;
        }

        public function get($params)
        {
            $this->tpl->assign('allCheckLists', $this->checkListsService->getAllCheckLists());
            $this->tpl->display('checkLists.showAll');

        }

    }
}