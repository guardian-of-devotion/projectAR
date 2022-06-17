<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class delNote
    {
        private $noteService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->noteService = new services\notes();

        }


        public function get()
        {

            //Only admins
            if (core\login::userIsAtLeast("developer")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('note', $this->noteService->getNote($id));
                $this->tpl->display('notes.delNote');

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
            if (core\login::userIsAtLeast("developer")) {

                if (isset($params['del'])) {

                    $result = $this->noteService->deleteNote($id);

                    if ($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.todo_deleted"), "success");
                        $this->tpl->redirect($_SESSION['lastPage']);
                    } else {
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('note', $this->noteService->getNote($id));
                        $this->tpl->display('notes.delNote');
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