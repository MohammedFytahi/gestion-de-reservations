<?php
require_once 'Reservation.php';

class ReservationManager {
    private $reservations = [];

    public function addReservation($reservation) {
        $this->reservations[$reservation->getId()] = $reservation;
    }

    public function cancelReservation($reservationId) {
        unset($this->reservations[$reservationId]);
    }

    public function getUserReservations($userId) {
        return array_filter($this->reservations, function($reservation) use ($userId) {
            return $reservation->getUserId() === $userId;
        });
    }

    public function getAllReservations() {
        return $this->reservations;
    }
}
?>
