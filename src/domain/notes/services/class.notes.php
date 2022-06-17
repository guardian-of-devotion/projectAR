<?php
namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;

    class notes
    {
        private $projectRepository;
        private $noteRepository;
        private $projectService;
        private $timesheetsRepo;
        private $language;

        public function __construct()
        {

            $this->tpl = new core\template();
            $this->projectRepository = new repositories\projects();
            $this->noteRepository = new repositories\notes();
            $this->language = new core\language();
            $this->projectService = new services\projects();
            $this->timesheetsRepo = new repositories\timesheets();

        }

        //GET Properties
        public function getStatuses() {
            return $this->noteRepository->getStateLabels();

        }

        public function getAllNotesForProjects() {

            return $this->noteRepository->getAllNotesForProjects($_SESSION['currentProject']);
        }

        public function getNote($id) {
            $note = $this->noteRepository->getNote($id);

            return $note;
        }


        public function quickAddNote($params)
        {

            $values = array(
                'headline' => $params['headline'],
                'description' => isset($params['description']) ? $params['description'] : '',
                'projectId' => $_SESSION['currentProject'],
                'editorId' => $_SESSION['userdata']['id'],
                'userId' => $_SESSION['userdata']['id'],
                'date' => date("Y-m-d H:i:s"),
                'status' => isset($params['status']) ? (int)$params['status'] : 8,
                'tags' => '',
                'editFrom' => '',
                'editTo' => '',
            );

            if ($values['headline'] == "") {
                $error = array("status" => "error", "message" => "Headline Missing");
                return $error;
            }

            return $this->noteRepository->addNote($values);
        }

        public function patchNote($id, $params)
        {

            //$params is an array of field names. Exclude id
            unset($params["id"]);

            return $this->noteRepository->patchNote($id, $params);

        }

        //Update
        public function updateNote($id, $values)
        {

            $values = array(
                'id' => $id,
                'headline' => $values['headline'],
                'description' => $values['description'],
                'projectId' => $_SESSION['currentProject'],
                'date' => date('Y-m-d  H:i:s'),
                'tags' => $values['tags'],
            );

            if(!$this->projectService->isUserAssignedToProject($_SESSION['userdata']['id'], $values['projectId'])) {

                return array("msg" => "notifications.note_save_error_no_access", "type" => "error");

            }

            if ($values['headline'] === '') {

                return array("msg" => "notifications.note_save_error_no_headline", "type" => "error");

            } else {

                //Update Note
                if($this->noteRepository->updateNote($values, $id) === true) {
                    return $this->noteRepository->updateNote($values, $id);
                }

            }
        }

        public function deleteNote($id){

            $ticket = $this->getNote($id);

            if(!$this->projectService->isUserAssignedToProject($_SESSION['userdata']['id'], $ticket->projectId)) {
                return array("msg" => "notifications.note_delete_error", "type" => "error");
            }

            if($this->noteRepository->deleteNote($id)){
                return true;
            }

            return false;

        }
    }
}