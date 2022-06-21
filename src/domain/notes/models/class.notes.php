<?php

/**
 * @author Regina Sharaeva
 */
namespace leantime\domain\models {

    class notes
    {
        public $id;
        public $projectId;
        public $editorId;
        public $userId;

        public $headline;
        public $description;
        public $status;
        public $date;
        public $tags;
        public $editFrom;
        public $editTo;

        public $projectName;
        public $userFirstname;
        public $userLastname;
        public $editorFirstname;
        public $editorLastname;


        public function __construct($values = false)
        {

            if($values !== false) {
                $this->id = $values["id"] ?? '';
                $this->headline = $values["headline"] ?? '';
                $this->description = $values["description"] ?? '';
                $this->status = $values["status"] ?? '8';
                $this->projectId = $values["projectId"] ?? '';
                $this->editorId = $values["editorId"] ?? '';
                $this->userId = $values["userId"] ?? '';
                $this->date = $values["date"] ?? date('Y-m-d  H:i:s');
                $this->tags = $values["tags"] ?? '';
                $this->editFrom = $values["editFrom"] ?? '';
                $this->editTo = $values["editTo"] ?? '';

                $this->projectName = $values["projectName"] ?? '';
                $this->userFirstname = $values["userFirstname"] ?? '';
                $this->userLastname = $values["userLastname"] ?? '';
                $this->editorFirstname = $values["editorFirstname"] ?? '';
                $this->editorLastname = $values["editorLastname"] ?? '';
            }

        }
    }

}
