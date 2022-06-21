<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;

    class delMarker
    {

        private $markerService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->markerService = new services\markers();

        }


        public function get()
        {

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('marker', $this->markerService->getMarker($id));
                
                $this->tpl->displayPartial('markers.delMarker');

            } else {

                $this->tpl->displayPartial('general.error');

            }

        }

        public function post($params) {

            if (isset($_GET['id'])) {
                $id = (int)($_GET['id']);
            }

            //Only admins
            if(core\login::userIsAtLeast("clientManager")) {

                if (isset($params['del'])) {

                    $result = $this->markerService->deleteMarker($id);

                    if($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.marker_deleted"), "success");
                        $this->tpl->redirect(BASE_URL."/markers/showAll");
                    }else{
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('markers', $this->markerService->getMarker($id));
                        $this->tpl->displayPartial('markers.delMarker');
                    }

                }else{
                    $this->tpl->displayPartial('general.error');
                }

            }else{
                $this->tpl->displayPartial('general.error');
            }
        }

    }

}
