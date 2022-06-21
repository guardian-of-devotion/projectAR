<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\models {

    class projectroles
    {
        public $id;
        public $name;
        public $leadId;

        public function __construct($values = false)
        {

            if($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->name = $values["name"] ?? '';
                $this->leadId = $values['leadId'] ?? '';
            }

        }
    }

}
