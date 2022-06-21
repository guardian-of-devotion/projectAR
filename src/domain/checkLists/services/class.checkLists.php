<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;

    class checkLists
    {
        private $checkListsRepository;


        public function __construct()
        {
            $this->checkListsRepository = new repositories\checkLists();
        }

        public function getAllCheckLists()
        {
            return $this->checkListsRepository->getAllCheckLists();
        }

        public function getCheckList($id)
        {
            return $this->checkListsRepository->getCheckList($id);
        }

        public function addCheckList(array $params)
        {
            $values = array(
                'headline' => $params['headline'],
                'description' => isset($params['description']) ? $params['description'] : '',
            );

            if ($values['headline'] == "") {
                $error = array("status" => "error", "message" => "Headline Missing");
                return $error;
            }

            return $this->checkListsRepository->addCheckList($values);

        }

        public function updateCheckList(array $params)
        {
            $values = array(
                'headline' => $params['headline'],
                'description' => isset($params['description']) ? $params['description'] : '',
            );

            if ($values['headline'] == "") {
                $error = array("status" => "error", "message" => "Headline Missing");
                return $error;
            }

            return $this->checkListsRepository->updateCheckList($values, $params['id']);
        }

        public function deleteCheckList($id)
        {
            if ($this->checkListsRepository->deleteCheckList($id)) {
                return true;
            }

            return false;
        }
    }
}