<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class delAnswer
    {

        private $answerService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->answerService = new services\answers();

        }


        public function get()
        {

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('answer', $this->answerService->getAnswer($id));
                $this->tpl->display('answers.delAnswer');

            } else {

                $this->tpl->display('general.error');

            }

        }

        public function post($params) {

            if (isset($_GET['id'])) {
                $id = (int)($_GET['id']);
            }

            $answer = $this->answerService->getAnswer($id);

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($params['del'])) {

                    $result = $this->answerService->deleteAnswer($id);

                    if($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.answer_deleted"), "success");
                        $this->tpl->redirect(BASE_URL."/questions/editQuestion/".$answer->questionId);
                    }else{
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('answer', $answer);
                        $this->tpl->display('answers.delAnswer');
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
