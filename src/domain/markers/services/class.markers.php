<?php

namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class markers
    {

        private $markerRepository;
        private $language;

        public function __construct()
        {

            $this->tpl = new core\template();
            $this->markerRepository = new repositories\markers();
            $this->language = new core\language();

        }

        public function getMarker($id)
        {

            $marker = $this->markerRepository->getMarker($id);

            //Check if user is allowed to see ticket
            if($marker) {
                return $marker;
            }

            return false;
        }

        public function getAllMarkers()
        {

            return $this->markerRepository->getAllMarkers();

            return false;

        }

        public function quickAddMarker($params)
        {

            $values = array(
                'name' => $params['name'],
                'projectroleId' => $params['projectroleId'],
                'relatedMarkerId' => $params['relatedMarkerId']
            );


            if($values['name'] == "") {
                $error = array("status"=>"error", "message"=>"Name Missing");
                return $error;
            }

            //$params is an array of field names. Exclude id
            return $this->markerRepository->addMarker($values);

        }

        public function quickAUpdateMarker($params)
        {

            $values = array(
                'name' => $params['name'],
                'projectroleId' => $params['projectroleId'],
                'relatedMarkerId' => $params['relatedMarkerId']
            );


            if($values['name'] == "") {
                $error = array("status"=>"error", "message"=>"Name Missing");
                return $error;
            }

             //$params is an array of field names. Exclude id
            return $this->markerRepository->updateMarker($values, $params["id"]);

        }

        public function deleteMarker($id){

            if($this->markerRepository->delMarker($id)){
                $ticketRepo = new repositories\tickets();
                $ticketRepo->deleteTicketMarkers($id);

                $ticketTemplateRepo = new repositories\ticketTemplates();
                return true;
            }

            return false;

        }

    }

}
