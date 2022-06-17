<?php

namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;

    class answers
    {
        private $answersRepository;


        public function __construct()
        {
            $this->answersRepository = new repositories\answers();
        }

        public function getAllAnswers($questionId)
        {
            return $this->answersRepository->getAllAnswers($questionId);
        }

        public function getAnswer($id)
        {
            return $this->answersRepository->getAnswer($id);
        }

        public function addAnswer(array $params, $questionId)
        {
            $values = array(
                'answerText' => $params['answerText'],
            );

            if ($values['answerText'] == "") {
                $error = array("status" => "error", "message" => "AnswerText Missing");
                return $error;
            }

            return $this->answersRepository->addAnswer($values, $questionId);

        }

        public function updateAnswer(array $params)
        {
            $values = array(
                'answerText' => $params['answerText'],
            );

            if ($values['answerText'] == "") {
                $error = array("status" => "error", "message" => "AnswerText Missing");
                return $error;
            }

            return $this->answersRepository->updateAnswer($values, $params['id']);
        }

        public function deleteAnswer($id)
        {
            if ($this->answersRepository->deleteAnswer($id)) {
                return true;
            }

            return false;
        }

        public function getAllAnswersForCheckList($checkListId) {
            $answers = [];

            $questionRepo = new repositories\questions();
            $questions = $questionRepo->getAllQuestions($checkListId);

            foreach ($questions as $key => $value) {
                $answersForQuestion = $this->answersRepository->getAllAnswers($value->id);

                if (count($answersForQuestion) > 0) {
                    $answers[$value->id] = $answersForQuestion;
                }
            }

            return $answers;
        }
    }
}