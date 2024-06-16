<?php

namespace leantime\domain\controllers;


use leantime\core;
use leantime\domain\repositories;
use leantime\domain\services;
use leantime\domain\models;
use \DateTime;
use \DateInterval;

class showMatrix
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

    public function get($params)
    {
        if (isset($params['id'])) {
            $currentProjectId = $_SESSION["currentProject"];
            $ticketHeaders = $this->ticketRepo->getAllTicketsHadTestCases($currentProjectId);
            $this->tpl->assign('ticketHeaders', $ticketHeaders);
            $this->tpl->assign('currentProjectId', $currentProjectId);
            $this->tpl->assign('matrixElements', $this->ticketService->getMatrix($currentProjectId));
            $this->tpl->assign('matrixStatistics', $this->ticketService->getMatrixStatistics($currentProjectId));
            $this->tpl->assign("statusLabels", $this->ticketService->getStatusLabels());
            $this->tpl->display('tickets.showMatrix');

        } else {
            $this->tpl->setNotification($this->language->__('notification.no_project'), 'error');
            $this->tpl->redirect(BASE_URL . "/dashboard/show");
        }

    }

    public function post($params)
    {
        if (isset($params['saveMatrix'])) {
            $projectId = $_GET['id'];
            $ticketHeaders = $this->ticketRepo->getAllTicketsHadTestCases($projectId);
            foreach ($ticketHeaders as $ticket) {
                if (isset($params[$ticket['id']])) {
                    if (!$ticket['is_in_matrix']) {
                        $this->ticketService->setTestCaseInMatrix($ticket['id'], true);
                    }
                } else {
                    if ($ticket['is_in_matrix']) {
                        $this->ticketService->setTestCaseInMatrix($ticket['id'], false);
                    }
                }
            }

            $this->tpl->redirect(BASE_URL . "/tickets/showMatrix/" . $projectId);
        }
    }
}