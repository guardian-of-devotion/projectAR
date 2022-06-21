<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    use \DateTime;
    use \DateInterval;


    class editMarker
    {

        private $tpl;
        private $markerService;
        private $projectroleService;

        /**
         * constructor - initialize private variables
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function __construct()
        {

            $this->tpl = new core\template();
            $this->markerService = new services\markers();
            $this->projectroleService = new services\projectroles;
            $this->language = new core\language();

        }

        /**
         * get - handle get requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function get($params)
        {
            if(isset($params['id'])) {
                $marker = $this->markerService->getMarker($params['id']);
            }else{
                $marker = new models\markers();

            }

            $this->tpl->assign('marker', $marker);
            $this->tpl->assign('projectroles', $this->projectroleService->getAllProjectroles());
            $this->tpl->assign('markers', $this->markerService->getAllMarkers());
            $this->tpl->displayPartial('markers.markerDialog');
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function post($params)
        {
            if(isset($_GET['id']) && $_GET['id'] > 0) {

                $params['id'] = (int)$_GET['id'];

                if ($this->markerService->quickAUpdateMarker($params) == true) {

                    $this->tpl->setNotification("Marker edited successfully", "success");
                    $this->tpl->assign('marker', $this->markerService->getMarker($params['id']));

                } else {

                    $this->tpl->setNotification("There was a problem saving the marker", "error");

                }

            }else{

                if ($this->markerService->quickAddMarker($params) == true) {

                    $this->tpl->setNotification("Marker created successfully", "success");

                } else {

                    $this->tpl->setNotification("There was a problem saving the marker", "error");

                }

            }
            
            $this->tpl->assign('projectroles', $this->projectroleService->getAllProjectroles());
            $this->tpl->assign('markers', $this->markerService->getAllMarkers());
             $this->tpl->assign('marker', (object) $params);
            $this->tpl->displayPartial('markers.markerDialog');
        }

        /**
         * put - handle put requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function put($params)
        {

        }

        /**
         * delete - handle delete requests
         *
         * @access public
         * @param  paramters or body of the request
         */
        public function delete($params)
        {

        }

    }

}