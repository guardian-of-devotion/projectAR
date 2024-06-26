<?php

/**
 * updated by
 * @author Regina Sharaeva
 */ 
namespace leantime\domain\models {

    class tickets
    {
        public $id;
        public $headline;
        public $type;
        public $description;
        public $projectId;
        public $editorId;
        public $userId;
        public $priority;
        public $markerId;
        public $minProfLevelId;

        public $date;
        public $dateToFinish;
        public $status;
        public $storypoints;
        public $hourRemaining;
        public $planHours;
        public $sprint;
        public $acceptanceCriteria;
        public $tags;
        public $editFrom;
        public $editTo;
        public $dependingTicketId;
        public $result;
        public $relatedTicketId;

        public $projectName;
        public $clientName;
        public $userFirstname;
        public $userLastname;
        public $editorFirstname;
        public $editorLastname;
        public $precondition;
        public $postcondition;
        public $steps;

        public $doneTickets;


        public function __construct($values = false)
        {

            if($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->headline = $values["headline"] ?? '';
                $this->type = $values["type"] ?? '';
                $this->description = $values["description"] ?? '';
                $this->projectId = $values["projectId"] ?? '';
                $this->editorId = $values["editorId"] ?? '';
                $this->userId = $values["userId"] ?? '';
                $this->priority = $values["priority"] ?? '';
                $this->marker = $values["marker"] ?? '';

                $this->date = $values["date"] ?? date('Y-m-d  H:i:s');
                $this->dateToFinish = $values["dateToFinish"] ?? '';
                $this->status = $values["status"] ?? '3';
                $this->storypoints = $values["storypoints"] ?? '';
                $this->hourRemaining = $values["hourRemaining"] ?? '';
                $this->planHours = $values["planHours"] ?? '';
                $this->sprint = $values["sprint"] ?? '';
                $this->acceptanceCriteria = $values["acceptanceCriteria"] ?? '';
                $this->tags = $values["tags"] ?? '';
                $this->editFrom = $values["editFrom"] ?? '';
                $this->editTo = $values["editTo"] ?? '';
                $this->dependingTicketId = $values["dependingTicketId"] ?? '';
                $this->result = $values["result"] ?? '';
                $this->relatedTicketId = $values["relatedTicketId"] ?? '';

                $this->projectName = $values["projectName"] ?? '';
                $this->clientName = $values["clientName"] ?? '';
                $this->userFirstname = $values["userFirstname"] ?? '';
                $this->userLastname = $values["userLastname"] ?? '';
                $this->editorFirstname = $values["editorFirstname"] ?? '';
                $this->editorLastname = $values["editorLastname"] ?? '';
                $this->precondition = $values["precondition"] ?? '';
                $this->postcondition = $values["postcondition"] ?? '';
                $this->steps = $values["steps"] ?? '';
            }

        }
    }

}
