<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\models {


    class checkLists
    {
        public $id;
        public $headline;
        public $description;

        public function __construct($values = false)
        {
            if ($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->headline = $values["headline"] ?? '';
                $this->description = $values["description"] ?? '';
            }
        }
    }
}