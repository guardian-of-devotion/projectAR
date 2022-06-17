<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class editTicketTemplate
    {

        private $ticketTemplateService;
        private $ticketService;
        private $fileService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->ticketTemplateService = new services\ticketTemplates();
            $this->ticketService = new services\tickets();
            $this->fileService = new services\files();
        }

        public function get($params) {
            $id = $params['id'];

            $ticketTemplate = $this->ticketTemplateService->getTicketTemplate($id);

            $this->tpl->assign('ticketTemplate', $ticketTemplate);
            $this->tpl->assign('ticketTypes', $this->ticketService->getTicketTypes());
            $this->tpl->assign('efforts', $this->ticketService->getEffortLabels());
            $this->tpl->assign('priorities', $this->ticketService->getPriorityLabels());
            $this->tpl->assign('markers', $this->ticketService->getMarkers());

            //Delete file
            if (isset($params['delFile']) === true) {

                $result = $this->fileService->deleteFile($params['delFile']);

                if($result === true) {
                    $this->tpl->setNotification($this->language->__("notifications.file_deleted"), "success");
                    $this->tpl->redirect(BASE_URL."/ticketTemplates/editTicket/".$id."#files");
                }else {
                    $this->tpl->setNotification($result["msg"], "error");
                }
            }

            $subTasks = $this->ticketTemplateService->getAllSubtasks($id);
            $this->tpl->assign('numSubTasks', count($subTasks));
            $this->tpl->assign('allSubTasks', $subTasks);

            $files = $this->fileService->getFilesByModule('ticketTemplate', $id);
            $this->tpl->assign('numFiles', count($files));
            $this->tpl->assign('files', $files);

            $this->tpl->assign('imgExtensions', array('jpg', 'jpeg', 'png', 'gif', 'psd', 'bmp', 'tif', 'thm', 'yuv'));

            $this->tpl->display('ticketTemplates.editTicketTemplate');
        }

        public function post($params) {

            $id = (int)($_GET['id']);
            $ticketTemplate = $this->ticketTemplateService->getTicketTemplate($id);

            if($ticketTemplate === false) {
                $this->tpl->display('general.error');
                return;
            }

            //Upload File
            if (isset($params['upload'])) {

                if ($this->fileService->uploadFile($_FILES, "ticketTemplate", $id, $ticketTemplate)) {
                    $this->tpl->setNotification($this->language->__("notifications.file_upload_success"), "success");
                } else {
                    $this->tpl->setNotification($this->language->__("notifications.file_upload_error"), "error");
                }
            }

            //Save Substask
            if (isset($params['subtaskSave']) === true) {

                if($this->ticketTemplateService->upsertSubtask($params, $ticketTemplate)) {
                    $this->tpl->setNotification($this->language->__("notifications.subtask_saved"), "success");
                }else {
                    $this->tpl->setNotification($this->language->__("notifications.subtask_save_error"), "error");
                }

            }

            //Delete Subtask
            if (isset($params['subtaskDelete']) === true) {

                $subtaskId = $params['subtaskId'];
                if($this->ticketTemplateService->deleteTicketTemplate($subtaskId)) {
                    $this->tpl->setNotification($this->language->__("notifications.subtask_deleted"), "success");
                }else {
                    $this->tpl->setNotification($this->language->__("notifications.subtask_delete_error"), "error");
                }
            }

            //Save Ticket
            if (isset($params["saveTicket"])) {

                $result = $this->ticketTemplateService->updateTicketTemplate($id, $params);

                if($result === true) {
                    $this->tpl->setNotification($this->language->__("notifications.ticket_saved"), "success");
                }else {
                    $this->tpl->setNotification($this->language->__($result["msg"]), "error");
                }
            }

            $this->tpl->redirect(BASE_URL."/ticketTemplates/editTicketTemplate/".$id);    

        }
    }

}
