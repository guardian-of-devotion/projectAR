<?php

namespace leantime\domain\models;
class profLevel
{
    public $id;
    public $name;

    public function __construct($values = false)
    {
        if ($values !== false) {
            $this->id = $values["id"] ?? '';
            $this->name = $values["headline"] ?? '';
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
