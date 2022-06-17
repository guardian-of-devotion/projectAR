<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class delProjectrole
    {

        private $projectroleService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->projectroleService = new services\projectroles();

        }


        public function get()
        {

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('projectrole', $this->projectroleService->getProjectrole($id));
                $this->tpl->displayPartial('projectroles.delProjectrole');

            } else {

                $this->tpl->displayPartial('general.error');

            }

        }

        public function post($params) {

            if (isset($_GET['id'])) {
                $id = (int)($_GET['id']);
            }

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($params['del'])) {

                    $result = $this->projectroleService->deleteProjectrole($id);

                    if($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.projectrole_deleted"), "success");
                        $this->tpl->redirect(BASE_URL."/projectroles/showAll");
                    }else{
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('projectroles', $this->projectroleService->getProjectrole($id));
                        $this->tpl->displayPartial('projectroles.delProjectrole');
                    }

                }else{
                    $this->tpl->displayPartial('general.error');
                }

            }else{
                $this->tpl->displayPartial('general.error');
            }
        }

    }

}
