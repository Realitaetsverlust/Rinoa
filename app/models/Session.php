<?php

class Session extends SuperConfig {
    public function getExpectedFieldNames() {
        return null;
    }

    public static function isUserLoggedIn() {
        if(isset($_SESSION['username'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function startSession($username) {
        $_SESSION['username'] = $username;
        $_SESSION['loggedInAt'] = time();
    }

    public static function stopSession() {
        session_destroy();
        unset($_SESSION);
    }

    public static function getUsernameFromSession() {
        return $_SESSION['username'];
    }

    public static function isUserAllowedForAdmin() {
        $user = new Users();
        $user->loadByName(self::getUsernameFromSession());
        if($user->getRights() === 1) {
            return true;
        }
        return false;
    }
}