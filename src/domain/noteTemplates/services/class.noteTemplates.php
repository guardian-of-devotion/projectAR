<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;

    class noteTemplates
    {
        private $noteTemplateRepository;
        private $language;

        public function __construct()
        {

            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->noteTemplateRepository = new repositories\noteTemplates();

        }

        public function getAllNoteTemplates($answerId) {
            return $this->noteTemplateRepository->getAllNoteTemplates($answerId);
        }

        public function getNoteTemplate($id) {
            return $this->noteTemplateRepository->getNoteTemplate($id);
        }

        public function addNoteTemplate($params, $answerId)
        {

            $values = array(
                'headline' => $params['headline'],
                'description' => isset($params['description']) ? $params['description'] : '',
                'status' => isset($params['status']) ? (int)$params['status'] : 8,
                'tags' => $params['tags'],
            );

            if ($values['headline'] == "") {
                $error = array("status" => "error", "message" => "Headline Missing");
                return $error;
            }

            return $this->noteTemplateRepository->addNoteTemplate($values, $answerId);
        }

        //Update
        public function updateNoteTemplate($id, $values)
        {

            $values = array(
                'headline' => $values['headline'],
                'description' => $values['description'],
                'tags' => $values['tags'],
            );

            if ($values['headline'] == "") {
                $error = array("status" => "error", "message" => "Headline Missing");
                return $error;
            }
           
            return $this->noteTemplateRepository->updateNoteTemplate($values, $id);
        }

        public function deleteNoteTemplate($id) {
            if($this->noteTemplateRepository->deleteNote($id)){
                return true;
            }

            return false;

        }

        public function getAllNoteTemplatesForQuestion($questionId) {
            $noteTemplates = [];

            $answersRepo = new repositories\answers();
            $answers = $answersRepo->getAllAnswers($questionId);

            foreach ($answers as $key => $value) {
                $notes = $this->noteTemplateRepository->getAllNoteTemplates($value->id);

                if (count($notes) > 0) {
                    $noteTemplates[$value->id] = $notes;
                }
            }

            return $noteTemplates;
        }

        public function getAllNoteTemplatesForCheckList($checkListId) {
            $noteTemplates = [];

            $questionRepo = new repositories\questions();
            $questions = $questionRepo->getAllQuestions($checkListId);
            $answersRepo = new repositories\answers();

            foreach ($questions as $key => $value) {
                $answersForQuestion = $answersRepo->getAllAnswers($value->id);

                if (count($answersForQuestion) > 0) {
                    foreach ($answersForQuestion as $k => $v) {
                        $notes = $this->noteTemplateRepository->getAllNoteTemplates($v->id);

                        if (count($notes) > 0) {
                            $noteTemplates[$v->id] = $notes;
                        }
                    }
                }
            }

            return $noteTemplates;
        }
    }
}