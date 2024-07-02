<?php
require_once 'Activity.php';

class ActivityManager {
    private $activities = [];

    public function addActivity($activity) {
        $this->activities[$activity->getId()] = $activity;
    }

    public function getAllActivities() {
        return $this->activities;
    }
}
?>
