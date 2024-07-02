<?php
class Reservation {
    private $id;
    private $userId;
    private $activityId;
    private $date;

    public function __construct($id, $userId, $activityId, $date) {
        $this->id = $id;
        $this->userId = $userId;
        $this->activityId = $activityId;
        $this->date = $date;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getActivityId() {
        return $this->activityId;
    }

    public function getDate() {
        return $this->date;
    }
}
?>
