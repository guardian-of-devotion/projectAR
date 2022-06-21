<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;
        use leantime\domain\models;

    class editNoteTemplate
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

            $id = (int)($params['id']);
            $noteTemplate = $this->noteTemplateService->getNoteTemplate($id);

	        if (isset($params['delFile']) === true) {

	            $result = $this->fileService->deleteFile($params['delFile']);

	            if($result === true) {
	                $this->tpl->setNotification($this->language->__("notifications.file_deleted"), "success");
	                $this->tpl->redirect(BASE_URL."/noteTemplates/editNoteTemplate/".$id."#files");
	            }else {
	                $this->tpl->setNotification($result["msg"], "error");
	            }
	        }

            $this->tpl->assign('noteTemplate', $noteTemplate);
            $files = $this->fileService->getFilesByModule('noteTemplate', $id);
            $this->tpl->assign('numFiles', count($files));
            $this->tpl->assign('files', $files);

            $this->tpl->assign('imgExtensions', array('jpg', 'jpeg', 'png', 'gif', 'psd', 'bmp', 'tif', 'thm', 'yuv'));
            $this->tpl->display('noteTemplates.editNoteTemplate');    
        }

        public function post($params)
        {

            $id = (int)($_GET['id']);
            $noteTemplate = $this->noteTemplateService->getNoteTemplate($id);

            if($noteTemplate === false) {
                $this->tpl->display('general.error');
                return;
            }

            //Upload File
            if (isset($params['upload'])) {
                if ($this->fileService->uploadFile($_FILES, "noteTemplate", $id, $noteTemplate)) {
                    $this->tpl->setNotification($this->language->__("notifications.file_upload_success"), "success");
                } else {
                    $this->tpl->setNotification($this->language->__("notifications.file_upload_error"), "error");
                }
            }

            //Save Note
            if (isset($params["saveNoteTemplate"]) === true) {

                $result = $this->noteTemplateService->updateNoteTemplate($id, $params);

                if($result === true) {
                    $this->tpl->setNotification($this->language->__("notifications.ticket_saved"), "success");
                }else {
                    $this->tpl->setNotification($this->language->__($result["msg"]), "error");
                }
            }

            $this->tpl->redirect(BASE_URL."/answers/editAnswer/".$noteTemplate->answerId);

        }

    }
}