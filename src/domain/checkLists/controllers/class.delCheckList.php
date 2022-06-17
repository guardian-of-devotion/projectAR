<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class delCheckList
    {

        private $checkListService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->checkListService = new services\checkLists();

        }


        public function get()
        {

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('checkList', $this->checkListService->getCheckList($id));
                $this->tpl->display('checkLists.delCheckList');

            } else {

                $this->tpl->display('general.error');

            }

        }

        public function post($params) {

            if (isset($_GET['id'])) {
                $id = (int)($_GET['id']);
            }

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($params['del'])) {

                    $result = $this->checkListService->deleteCheckList($id);

                    if($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.checkList_deleted"), "success");
                        $this->tpl->redirect(BASE_URL."/checkLists/showAll");
                    }else{
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('checkLists', $this->checkListService->getCheckList($id));
                        $this->tpl->display('checkLists.delCheckList');
                    }

                }else{
                    $this->tpl->display('general.error');
                }

            }else{
                $this->tpl->display('general.error');
            }
        }

    }

}
