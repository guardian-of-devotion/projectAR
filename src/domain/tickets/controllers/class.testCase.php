<?php

namespace leantime\domain\controllers;

use leantime\core;
use leantime\domain\repositories;
use leantime\domain\services;
use leantime\domain\models;
use \DateTime;
use \DateInterval;

class testCase
{
    private $tpl;
    private $projects;
    private $ticketService;

    public function __construct()
    {

        $this->tpl = new core\template();
        $this->ticketService = new services\tickets();
        $this->ticketRepo = new repositories\tickets();
        $this->projectRepo = new repositories\projects();
        $this->commentsService = new services\comments();
        $this->projectService = new services\projects();
        $this->language = new core\language();
    }

    /**
     * get - handle get requests
     *
     * @access public
     * @param $params or body of the request
     */
    public function get($params)
    {
        $currentProjectId = $_SESSION['currentProject'];
        if (isset($params['id'])) {
            $testCaseId = $params['id'];
            $testCase = $this->ticketService->getTestCase($testCaseId);
        } else {
            $testCase = new models\tickets(
                array(
                    "id" => $testCaseId ?? null,
                    "userLastname" => $_SESSION['userdata']["name"],
                    "status" => 3,
                    "projectId" => $currentProjectId,
                )
            );
        }

        $this->tpl->assign('testCase', $testCase);
        $this->tpl->assign('ticketId', $params['ticketId'] ?? null);
        $testCase->date = $this->language->getFormattedDateString(date("Y-m-d H:i:s"));

        $this->tpl->assign('statusLabels', $this->ticketService->getStatusLabels());
        $this->tpl->assign('helper', new core\helper());
        $this->tpl->assign('users', $this->projectRepo->getUsersAssignedToProject($_SESSION['currentProject']));
        $this->tpl->displayPartial('tickets.testCase');

    }

    public function post($params)
    {
        if (isset($params['addTestCase']) && isset($params['ticketId'])) {
            $testCase = $this->ticketService->addTestCase($params);
        } elseif (isset($params['editTestCase'])) {
            $testCase = $this->ticketService->editTestCase($params['id'], $params);
        }

        $this->tpl->assign('statusLabels', $this->ticketService->getStatusLabels());
        $this->tpl->assign('helper', new core\helper());
        $this->tpl->assign('users', $this->projectRepo->getUsersAssignedToProject($_SESSION['currentProject']));
        $this->tpl->setNotification($this->language->__("notifications.test_case_saved"), "success");
        $this->tpl->assign('testCase', $testCase);
        $this->tpl->assign('ticketId', $params['ticketId']);
        $this->tpl->displayPartial('tickets.testCase');
    }
}