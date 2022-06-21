<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class showNote
    {
        private $projectService;
        private $noteService;
        private $tpl;
        private $fileService;
        private $commentService;
        private $userService;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();

            $this->language = new core\language();

            $this->projectService = new services\projects();
            $this->noteService = new services\notes();
            $this->fileService = new services\files();
            $this->commentService = new services\comments();
            $this->userService = new services\users();

            if (isset($_SESSION['lastPage']) === false) {
                $_SESSION['lastPage'] = BASE_URL . "/tickets/showKanban";
            }
        }


        public function get($params)
        {

            if (isset($params['id']) === true) {

                $id = (int)($params['id']);
                $note = $this->noteService->getNote($id);

                if ($note === false) {
                    $this->tpl->display('general.error');
                    return;
                }

                //Ensure this ticket belongs to the current project
                if ($_SESSION["currentProject"] != $note->projectId) {
                    $this->projectService->changeCurrentSessionProject($note->projectId);
                    $this->tpl->redirect(BASE_URL . "/notes/showNote/" . $id);
                }
                //Delete file
            if (isset($params['delFile']) === true) {

                $result = $this->fileService->deleteFile($params['delFile']);

                if($result === true) {
                    $this->tpl->setNotification($this->language->__("notifications.file_deleted"), "success");
                    $this->tpl->redirect(BASE_URL."/notes/showNote/".$id."#files");
                }else {
                    $this->tpl->setNotification($result["msg"], "error");
                }
            }

                //Delete comment
                if (isset($params['delComment']) === true) {

                    $commentId = (int)($params['delComment']);

                    if ($this->commentService->deleteComment($commentId)) {
                        $this->tpl->setNotification($this->language->__("notifications.comment_deleted"), "success");
                        $this->tpl->redirect(BASE_URL . "/notes/showNote/" . $id);
                    } else {
                        $this->tpl->setNotification($this->language->__("notifications.comment_deleted_error"), "error");
                    }
                }

                $this->tpl->assign('note', $note);


                $this->tpl->assign('userInfo', $this->userService->getUser($_SESSION['userdata']['id']));
                $this->tpl->assign('users', $this->projectService->getUsersAssignedToProject($note->projectId));

                $projectData = $this->projectService->getProject($note->projectId);
                $this->tpl->assign('projectData', $projectData);

                $comments = $this->commentService->getComments('note', $id, $_SESSION["projectsettings"]['commentOrder']);

                $this->tpl->assign('numComments', count($comments));
                $this->tpl->assign('comments', $comments);


                $files = $this->fileService->getFilesByModule('note', $id);
                $this->tpl->assign('numFiles', count($files));
                $this->tpl->assign('files', $files);


                //TODO: Refactor thumbnail generation in file manager
                $this->tpl->assign('imgExtensions', array('jpg', 'jpeg', 'png', 'gif', 'psd', 'bmp', 'tif', 'thm', 'yuv'));

                $this->tpl->display('notes.showNote');

            } else {

                $this->tpl->display('general.error');

            }
        }

        public function post($params)
        {

            if (isset($_GET['id']) === true) {

                $id = (int)($_GET['id']);
                $note = $this->noteService->getNote($id);

                if($note === false) {
                    $this->tpl->display('general.error');
                    return;
                }

                //Upload File
                if (isset($params['upload'])) {

                    if ($this->fileService->uploadFile($_FILES, "note", $id, $note)) {
                        $this->tpl->setNotification($this->language->__("notifications.file_upload_success"), "success");
                    } else {
                        $this->tpl->setNotification($this->language->__("notifications.file_upload_error"), "error");
                    }
                }

                //Add a comment
                if (isset($params['comment']) === true) {

                    if($this->commentService->addComment($_POST, "note", $id, $note)) {
                        $this->tpl->setNotification($this->language->__("notifications.comment_create_success"), "success");
                    }else {
                        $this->tpl->setNotification($this->language->__("notifications.comment_create_error"), "error");
                    }
                }


                //Save Note
                if (isset($params["saveNote"]) === true || isset($params["saveAndCloseNote"]) === true) {

                    $result = $this->noteService->updateNote($id, $params);

                    if($result === true) {
                        $this->tpl->setNotification($this->language->__("notifications.ticket_saved"), "success");
                    }else {
                        $this->tpl->setNotification($this->language->__($result["msg"]), "error");
                    }

                    if(isset($params["saveAndCloseTicket"]) === true) {
                        $this->tpl->redirect($_SESSION['lastPage']);
                    }
                }

                $this->tpl->redirect(BASE_URL."/notes/showNote/".$id);

            } else {

                $this->tpl->display('general.error');

            }

        }

    }
}