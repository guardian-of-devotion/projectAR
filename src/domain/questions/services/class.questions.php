<?php

namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;

    class questions
    {
        private $questionsRepository;


        public function __construct()
        {
            $this->questionsRepository = new repositories\questions();
        }

        public function getAllQuestions($checkListId)
        {
            return $this->questionsRepository->getAllQuestions($checkListId);
        }

        public function getQuestion($id)
        {
            return $this->questionsRepository->getQuestion($id);
        }

        public function addQuestion(array $params, $checkListId)
        {
            $values = array(
                'headline' => $params['headline'],
                'questionText' => isset($params['questionText']) ? $params['questionText'] : '',
            );

            if ($values['headline'] == "") {
                $error = array("status" => "error", "message" => "Headline Missing");
                return $error;
            }

            return $this->questionsRepository->addQuestion($values, $checkListId);

        }

        public function updateQuestion(array $params)
        {
            $values = array(
                'headline' => $params['headline'],
                'questionText' => isset($params['questionText']) ? $params['questionText'] : '',
            );

            if ($values['headline'] == "") {
                $error = array("status" => "error", "message" => "Headline Missing");
                return $error;
            }

            return $this->questionsRepository->updateQuestion($values, $params['id']);
        }

        public function deleteQuestion($id)
        {
            if ($this->questionsRepository->deleteQuestion($id)) {
                return true;
            }

            return false;
        }
    }
}