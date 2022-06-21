<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\models {

    class answers
    {
        public $id;
        public $questionId;
        public $answerText;

        public function __construct($values = false)
        {
            if ($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->questionId = $values["questionId"] ?? '';
                $this->answerText = $values["answerText"] ?? '';
            }
        }
    }
}