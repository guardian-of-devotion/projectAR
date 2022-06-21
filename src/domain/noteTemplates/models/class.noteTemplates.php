<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\models {

    class noteTemplates
    {
        public $id;
        public $answerId;
        public $headline;
        public $description;
        public $status;
        public $tags;

        public function __construct($values = false)
        {
            if ($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->answerId = $values["answerId"] ?? '';
                $this->headline = $values["headline"] ?? '';
                $this->description = $values["description"] ?? '';
                $this->status = $values["status"] ?? '8';
                $this->tags = $values["tags"] ?? '';
            }
        }
    }
}