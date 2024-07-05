<?php
session_start();
header('Content-Type: application/json');

class SessionManager {
    public static function checkAdminSession() {
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
            return true;
        } else {
            return false;
        }
    }
}

echo json_encode(['success' => SessionManager::checkAdminSession()]);
?>
