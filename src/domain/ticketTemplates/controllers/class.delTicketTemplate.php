<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class delTicketTemplate
    {

        private $ticketTemplateService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->ticketTemplateService = new services\ticketTemplates();

        }

        public function get()
        {

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('ticketTemplates', $this->ticketTemplateService->getTicketTemplate($id));
                $this->tpl->display('ticketTemplates.delTicketTemplate');

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

                    $result = $this->ticketTemplateService->deleteTicketTemplate($id);

                    if($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.todo_deleted"), "success");
                        $this->tpl->redirect($_SESSION['lastPage']);
                    }else{
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('ticketTemplate', $this->ticketTemplateService->getTicketTemplate($id));
                        $this->tpl->display('ticketTemplates.delTicketTemplate');
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
