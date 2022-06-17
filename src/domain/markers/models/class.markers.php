<?php

namespace leantime\domain\models {

    class markers
    {
        public $id;
        public $name;
        public $projectroleId;
        public $relatedMarkerId;

        public function __construct($values = false)
        {

            if($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->name = $values["name"] ?? '';
                $this->projectroleId = $values["projectRoleId"] ?? '';
                $this->relatedMarkerId = $values["relatedMarkerId"] ?? '';
            }

        }
    }

}
