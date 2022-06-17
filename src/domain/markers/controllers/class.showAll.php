<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class showAll
    {

        private $tpl;
        private $markerService;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->markerService = new services\markers();

            $_SESSION['lastPage'] = CURRENT_URL;
        }

        public function get($params) {

            $this->tpl->assign('allMarkers', $this->markerService->getAllMarkers());

            $this->tpl->display('markers.showAll');
        }

    }
}
