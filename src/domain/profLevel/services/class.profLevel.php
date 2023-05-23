<?php

namespace leantime\domain\services;

use leantime\domain\repositories;

class profLevel
{

    public function __construct()
    {
        $this->profLevelRepo = new repositories\profLevel();
    }

    public function getProfLevels()
    {
        return $this->profLevelRepo->getAllProfLevels();
    }

    public function getUserProfLevel($userId)
    {

    }
}