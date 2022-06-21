<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class projectroles
    {

        private $projectRepository;
        private $projectroleRepository;
        private $markerRepository;
        private $projectService;
        private $timesheetsRepo;
        private $language;

        public function __construct()
        {

            $this->tpl = new core\template();
            $this->projectRepository = new repositories\projects();
            $this->projectroleRepository = new repositories\projectroles();
            $this->ticketRepository = new repositories\tickets();
            $this->markerRepository = new repositories\markers();
            $this->language = new core\language();
            $this->projectService = new services\projects();
            $this->timesheetsRepo = new repositories\timesheets();

        }

        public function getProjectrole($id)
        {

            $projectrole = $this->projectroleRepository->getProjectrole($id);

            //Check if user is allowed to see ticket
            if($projectrole) {
                return $projectrole;
            }

            return false;
        }

        public function getAllProjectroles()
        {
            return $this->projectroleRepository->getAllProjectroles();
        }

        public function getLeads($id)
        {
            return $this->projectroleRepository->getLeads($id);
        }

        public function quickAddProjectrole($params)
        {

            $values = array(
                'name' => $params['name'],
                'leadId' => $params['leadId']
            );


            if($values['name'] == "") {
                $error = array("status"=>"error", "message"=>"Name Missing");
                return $error;
            }

            //$params is an array of field names. Exclude id
            return $this->projectroleRepository->addProjectrole($values);

        }

        public function quickAUpdateProjectrole($params)
        {

            $values = array(
                'name' => $params['name'],
                'leadId' => $params['leadId']
            );


            if($values['name'] == "") {
                $error = array("status"=>"error", "message"=>"Name Missing");
                return $error;
            }

             //$params is an array of field names. Exclude id
            return $this->projectroleRepository->updateProjectrole($values, $params["id"]);

        }

        public function deleteProjectrole($id){

            $projectrole = $this->getProjectrole($id);

            if($this->projectroleRepository->delProjectrole($id)){
                return true;
            }

            return false;

        }

    }

}
