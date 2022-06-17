<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class delTemplateNote
    {
        private $noteTemplateService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->noteTemplateService = new services\noteTemplates();

        }


        public function get()
        {

            //Only admins
            if (core\login::userIsAtLeast("clientManager")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('noteTemplate', $this->noteTemplateService->getNotetemplate($id));
                $this->tpl->display('noteTemplates.delNoteTemplate');

            } else {

                $this->tpl->display('general.error');
            }
        }

        public function post($params)
        {

            if (isset($_GET['id'])) {
                $id = (int)($_GET['id']);
            }

            //Only admins
            if (core\login::userIsAtLeast("clientManager")) {

                if (isset($params['del'])) {

                    $result = $this->noteTemplateService->deleteNoteTemplate($id);

                    if ($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.todo_deleted"), "success");
                        $this->tpl->redirect($_SESSION['lastPage']);
                    } else {
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('noteTemplate', $this->noteTemplateService->getNoteTemplate($id));
                        $this->tpl->display('noteTemplates.delNoteTemplate');
                    }

                } else {
                    $this->tpl->display('general.error');
                }

            } else {
                $this->tpl->display('general.error');
            }
        }
    }
}