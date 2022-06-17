<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;
    use leantime\domain\models;

    class newNoteTemplate
    {
        
        private $noteTemplateService;
        private $noteService;
        private $fileService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->fileService = new services\files();
            $this->noteTemplateService = new services\noteTemplates();
            $this->noteService = new services\notes();
        }


        public function get($params)
        {

        	$noteTemplate = new models\noteTemplates();
            
            $this->tpl->assign('noteTemplate', $noteTemplate);
            $this->tpl->display('noteTemplates.newNoteTemplate');
        }

        public function post($params)
        {
        	$answerId = (int)$_GET['id'];

            $id = $this->noteTemplateService->addNoteTemplate($params, $answerId);

            if ($id == true) {
                $this->tpl->setNotification($this->language->__("notifications.ticket_saved"), "success");
                $this->tpl->redirect(BASE_URL."/noteTemplates/editNoteTemplate/".$id);

            } else {
                $this->tpl->setNotification($this->language->__($result["msg"]), "error");
                $this->tpl->display('general.error');

            }

        }

    }
}