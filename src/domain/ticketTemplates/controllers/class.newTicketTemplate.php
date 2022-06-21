<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class newTicketTemplate
    {

        private $ticketTemplateService;
        private $ticketService;
        private $tpl;
        private $language;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->ticketTemplateService = new services\ticketTemplates();
            $this->ticketService = new services\tickets();
        }

        public function get() {

            $ticketTemplate = new models\ticketTemplates();

            $this->tpl->assign('ticketTemplate', $ticketTemplate);
            $this->tpl->assign('ticketTypes', $this->ticketService->getTicketTypes());
            $this->tpl->assign('efforts', $this->ticketService->getEffortLabels());
            $this->tpl->assign('priorities', $this->ticketService->getPriorityLabels());
            $this->tpl->assign('markers', $this->ticketService->getMarkers());

            $this->tpl->display('ticketTemplates.newTicketTemplate');
        }

        public function post($params) {

            $answerId = (int)$_GET['id'];

            $id = $this->ticketTemplateService->addTicketTemplate($params, $answerId);

            if ($id == true) {
                $params['id'] = $id;
                
                $this->tpl->setNotification("TicketTemplate created successfully", "success");
                $this->tpl->redirect($_SESSION['lastPage']);
            
            }else {

                $this->tpl->setNotification($this->language->__($result["msg"]), "error");

                $this->tpl->assign('ticketTemplate', (object) $params);
                $this->tpl->assign('ticketTypes', $this->ticketService->getTicketTypes());
                $this->tpl->assign('efforts', $this->ticketService->getEffortLabels());
                $this->tpl->assign('priorities', $this->ticketService->getPriorityLabels());
                $this->tpl->assign('markers', $this->ticketService->getMarkers());

                $this->tpl->display('ticketTemplates.newTicketTemplate');

            }

        }

    }

}
