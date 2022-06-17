<?php

namespace leantime\domain\models {

    class ticketTemplates
    {
        public $id;
        public $answerId;
        public $headline;
        public $type;
        public $description;
        public $priority;
        public $markers;
        public $storypoints;
        public $planHours;
        public $acceptanceCriteria;
        public $tags;         
        public $dependingTicketId;

        public function __construct($values = false)
        {

            if ($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->answerId = $values["answerId"] ?? '';
                $this->headline = $values["headline"] ?? '';
                $this->type = $values["type"] ?? '';
                $this->description = $values["description"] ?? '';
                $this->priority = $values["priority"] ?? '';
                $this->marker = $values["marker"] ?? '';
                $this->storypoints = $values["storypoints"] ?? '';
                $this->planHours = $values["planHours"] ?? '';
                $this->acceptanceCriteria = $values["acceptanceCriteria"] ?? '';
                $this->tags = $values["tags"] ?? '';
                $this->dependingTicketId = $values['dependingTicketId'] ?? ''; 
            }

        }
    }
}