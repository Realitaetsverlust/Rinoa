<?php

class LoginController extends Controller {

    public function render() {
        $this->login();
    }

    public function login() {
        $this->_setTemplateName("login.tpl");
        $this->display();
    }

    public function logout() {
        Session::stopSession();
        $this->_setTemplateName("login.tpl");
        $this->assign("successMessage", "Erfolgreich ausgeloggt. Bis bald!");
        $this->display();
    }

    public function handleLogin() {
        $this->_setTemplateName("login.tpl");
        $user = new Users();

        foreach($_POST as $key => $postVar) {
            $post[$key] = $postVar;
        }

        $user->loadByName($post['loginname']);

        if((!empty($post['password'])) && $user->verifyPassword($post['password'])) {
            Session::startSession($post['loginname']);
            Route::rerouteToController("Main");
        } else {
            session_destroy();
            $this->assign("errorMessage", "Dieser Benutzer existiert nicht oder das Passwort ist falsch.");
            $this->display();
        }
    }

    public function register() {
        $this->_setTemplateName("register.tpl");
        $this->display();
    }

    public function handleRegistration() {
        $user = new Users();

        foreach($_POST as $key => $postVar) {
            $post[$key] = $postVar;
        }

        $this->_setTemplateName("register.tpl");

        if(!isset($post['password'])) {
            $this->assign("error", "Ein Passwort ist Vorraussetzung!");
            $this->display();
            return false;
        }

        if(!isset($post['loginname'])) {
            $this->assign("error", "Ein Benutzername ist Vorraussetzung!");
            $this->display();
            return false;
        }

        if($post['password'] !== $post['repeatPassword']) {
            $this->assign("error", "Die Passwörter stimmen nicht überrein!");
            $this->display();
            return false;
        }

        if(!empty($post['twitter'])) {
            if(strpos($post['twitter'], "@") != 0) {
                $this->assign("error", "Dieser Twittertag ist nicht gültig! Er muss mit einem '@' beginnen!");
                $this->display();
                return false;
            }
        }

        if(!empty($post['email'])) {
            if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                $this->assign("error", "Ungültige E-Mail eingegeben");
                $this->display();
                return false;
            }
        }

        $passwordHash = $user->createPasswordHash($post['password']);

        $user->setName($post['loginname']);
        $user->setPassword($passwordHash);
        $user->setTwitter($post['twitter']);
        $user->setEmail($post['email']);

        if($user->save()) {
            $this->_setTemplateName("login.tpl");
            $this->assign("successMessage", "Der Benutzer wurde erfolgreich angelegt! Du kannst dich jetzt mit ihm einloggen.");
            $this->display();
        } else {
            $this->assign("error", "Dieser Benutzer existiert bereits. Bitte wähle einen anderen Namen.");
            $this->display();
        }
    }
}