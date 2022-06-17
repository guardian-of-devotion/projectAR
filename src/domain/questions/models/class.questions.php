<?php

namespace leantime\domain\models {

    class questions
    {
        public $id;
        public $checkListId;
        public $headline;
        public $questionText;

        public function __construct($values = false)
        {
            if ($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->checkListId = $values["checkListId"] ?? '';
                $this->headline = $values["headline"] ?? '';
                $this->questionText = $values["questionText"] ?? '';
            }
        }
    }
}