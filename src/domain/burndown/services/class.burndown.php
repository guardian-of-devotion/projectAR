<?php

namespace leantime\domain\services {

    use Cassandra\Date;
    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;
    use leantime\domain\models;

    class burndown
    {

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->projectRepository = new repositories\projects();
            $this->ticketRepo = new repositories\tickets();
            $this->ticketService = new services\tickets();
            $this->projectService = new services\projects();
            $this->userRepo = new repositories\users();
            $this->storyPointsService = new services\storyPoints();
        }

        public function getCurrentBurndownByProject($projectId)
        {
            $project = $this->projectRepository->getProject($projectId);
            $projectEndDate = $project['endAt'];
            $projectEndDateNextDay = New \DateTime($projectEndDate);
            $projectEndDateNextDay = $projectEndDateNextDay->modify('+1 day')->format('Y-m-d');
            $storyPointConversion = $this->storyPointsService->getStoryPointsConversion($projectId);
            $tickets = $this->ticketRepo->getTicketsByProject($projectId);
            $ticketsAddOrClose = [];
            $actualArray = array();
            $dateArray = array();
            $idealArray = array();
            $ticketsTimeSum = 0;
            $firstTicketDayTime = $projectEndDateNextDay;
            foreach ($tickets as $ticket) {
                if ($ticket['date'] < $firstTicketDayTime) {
                    $firstTicketDayTime = $ticket['date'];
                }

                $ticketTime = $this->getTicketWorkTime($ticket, $storyPointConversion);
                $ticketsTimeSum += $ticketTime;
                $openedAt = new \DateTime($ticket['date']);
                $openedAt = $openedAt->format('Y-m-d');
                if (array_key_exists($openedAt, $ticketsAddOrClose)) {
                    $ticketsAddOrClose[$openedAt] += $ticketTime;
                } else {
                    $ticketsAddOrClose[$openedAt] = +$ticketTime;
                }
                if (!$ticket['closed_at']) {
                    continue;
                }
                $closedAt = new \DateTime($ticket['closed_at']);
                $closedAt = $closedAt->format('Y-m-d');
                if (array_key_exists($closedAt, $ticketsAddOrClose)) {
                    $ticketsAddOrClose[$closedAt] -= $ticketTime;
                } else {
                    $ticketsAddOrClose[$closedAt] = -$ticketTime;
                }


            }

            //актуальный график
            $datenow = new \DateTime();
            $firstTicketDay = new \DateTime($firstTicketDayTime);
            $firstTicketDayStr = $firstTicketDay->format('Y-m-d');
            $datenowStr = $datenow->format('Y-m-d');
            $ticketTimeChar = 0;
            while ($projectEndDateNextDay > $firstTicketDayStr) {
                $dateArray[] = $firstTicketDayStr;
                if (key_exists($firstTicketDayStr, $ticketsAddOrClose)) {
                    $ticketTimeChar = $ticketTimeChar + $ticketsAddOrClose[$firstTicketDayStr];
                }
                if ($firstTicketDayStr <= $datenowStr) {
                    $actualArray[] = $ticketTimeChar;
                }

                $firstTicketDay->modify('+1 day');
                $firstTicketDayStr = $firstTicketDay->format('Y-m-d');
            }

            //идеальный график
            $maxOfActualArray = max($actualArray);
            $idealPointSum = $maxOfActualArray;
            $countDateArray = count($dateArray);
            foreach ($dateArray as $date) {
                $idealArray[] = $idealPointSum;
                $idealPointSum = $idealPointSum - ($maxOfActualArray / $countDateArray);
            }
            //Для последнего дня
            $dateArray[] = $projectEndDateNextDay;
            $idealArray[] = 0;

            return array(
                'dateArray' => $dateArray,
                'actualArray' => $actualArray,
                'countStoryPoints' => $maxOfActualArray,
                'idealArray' => $idealArray,
            );
        }

        public function getTicketWorkTime($ticket, $storyConversion)
        {

            $hourRemaining = null;
            if (array_key_exists('hourRemaining', $ticket))

            if ($hourRemaining) {
                return $hourRemaining * 60;
            } else {
                if (array_key_exists('storypoints', $ticket)) {
                    $ticketWorkTime = $this->getHoursByEffortValue($storyConversion, $ticket['storypoints']);
                    return $ticketWorkTime * 60;
                }
                return 0;

            }
        }

        public function getHoursByEffortValue($storyConversion, $effortValue)
        {
            foreach ($storyConversion as $conv) {
                if ($conv['effort_value'] == $effortValue) {
                    return $conv['effort_hours'];
                }
            }
        }
    }
}