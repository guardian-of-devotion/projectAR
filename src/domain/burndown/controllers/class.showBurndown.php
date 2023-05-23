<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class showBurndown
    {
        public function __construct()
        {
            $this->tpl = new core\template();
            $this->burnwdown = new services\burndown();
        }

        public function run()
        {
            $projectId = $_SESSION["currentProject"];
            if ($projectId) {
                $result = $this->burnwdown->getCurrentBurndownByProject($projectId);
                $this->tpl->assign('dateArray', $result['dateArray']);
                $this->tpl->assign('countStoryPoints', $result['countStoryPoints']);
                $this->tpl->assign('idealArray', $result['idealArray']);
                $this->tpl->assign('actualArray', $result['actualArray']);
                $this->tpl->display('burndown.showBurndown');
            }
        }
    }
}