<?php
namespace leantime\domain\controllers;

use leantime\core;
use leantime\domain\services;

class delTestCase
{
    private $ticketService;
    private $tpl;
    private $language;

    public function __construct()
    {
        $this->tpl = new core\template();
        $this->language = new core\language();
        $this->ticketService = new services\tickets();

    }


    public function get()
    {
        //Only admins
        if (core\login::userIsAtLeast("clientManager")) {

            if (isset($_GET['id'])) {
                $testCaseId = $_GET['id'];
                $testCase = $this->ticketService->getTestCase($testCaseId);
            }

            $this->tpl->assign('testCase', $testCase);
            $this->tpl->assign('ticketId', $params['ticketId'] ?? null);

            $this->tpl->displayPartial('tickets.delTestCase');

        } else {
            $this->tpl->displayPartial('general.error');
        }
    }

    public function post($params)
    {

        if (isset($_GET['id'])) {
            $id = (int)($_GET['id']);
        }

        //Only admins
        if (core\login::userIsAtLeast("clientManager")) {

            if (isset($params['del'])) {

                $result = $this->ticketService->deleteTestCase($id);

                if ($result === true) {
                    $this->tpl->setNotification($this->language->__("notification.test_case_deleted"), "success");
                    $this->tpl->redirect(BASE_URL."/tickets/showKanban");

                } else {
                    $this->tpl->setNotification($this->language->__($result['msg']), "error");
                    $this->tpl->assign('testCase', $this->ticketService->getTestCase($id));
                    $this->tpl->displayPartial('tickets.delTestCase');
                }

            } else {
                $this->tpl->displayPartial('general.error');
            }

        } else {
            $this->tpl->displayPartial('general.error');
        }
    }
}