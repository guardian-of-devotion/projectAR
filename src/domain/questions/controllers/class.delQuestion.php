<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class delQuestion
    {

        private $questionService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->questionService = new services\questions();

        }


        public function get()
        {

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('question', $this->questionService->getQuestion($id));
                $this->tpl->display('questions.delQuestion');

            } else {

                $this->tpl->display('general.error');

            }

        }

        public function post($params) {

            if (isset($_GET['id'])) {
                $id = (int)($_GET['id']);
            }

            $question = $this->questionService->getQuestion($id);

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($params['del'])) {

                    $result = $this->questionService->deleteQuestion($id);

                    if($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.question_deleted"), "success");
                        $this->tpl->redirect(BASE_URL."/checkLists/editCheckList/".$question->checkListId);
                    }else{
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('question', $question);
                        $this->tpl->display('questions.delQuestion');
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
