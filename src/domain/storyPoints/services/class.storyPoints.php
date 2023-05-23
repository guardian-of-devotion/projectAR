<?php

namespace leantime\domain\services;

class storyPoints
{
    private array $efforts = [
        1 => '1',
        2 => '2',
        3 => '4',
        5 => '10',
        8 => '18',
        13 => '28',
        21 => '40'
    ];

    public function __construct () {
        $this->storyPointsRepo = new \leantime\domain\repositories\storyPoints();
    }

    public function getStoryPointsConversion($projectId, $effortValue = null)
    {
        if ($effortValue) {
            return $this->storyPointsRepo->getStoryPointByEffortValue($projectId, $effortValue);
        }
        return $this->storyPointsRepo->getStoryPointsByProject($projectId);
    }

    public function updateStoryPointsCostInTime($value)
    {
        $efforts = $value['efforts'];
        foreach ($efforts as $effort) {
            $this->storyPointsRepo->updateStoryPointsCostInTime($effort['id'], $effort['effort_hours']);
        }
    }

        public function insertDefaultStoryPointsCostInTime($projectId)
    {
        foreach ($this->efforts as $key => $value) {
            $this->storyPointsRepo->insertStoryPointsCostInTime($projectId, $key, $value);
        }
    }
}