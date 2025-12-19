<?php

require_once "./config/config.php";
require_once "./models/Users.php";
require_once "./models/Students.php";
require_once "./models/Teachers.php";

class UserControler
{ // we make a controler to manage the user's actions

    public function getCurrentUser()
    {
        if (!isset($_SESSION['user_id'])) { // if not exist session with user_id
            return null;
        }

        $userData = Users::findById($_SESSION['user_id']);//call user method to check for the userID in session

        if (!$userData) {
            return null;
        }
        $userType = (int) $userData['role_id'] === 0 ? 'teacher' : 'student';
        $username = htmlspecialchars($userData['username']);

        if ($userType === 'teacher') {
            $currentUser = new Teachers($userData['username'], $userData['email'], '', $userData['role_id'], $userData['spCode']);
        } else {
            $currentUser = new Students($userData['username'], $userData['email'], '', $userData['role_id'], $userData['spCode']);
        }

        return [
            'userType' => $userType,
            'username' => $username,
            'currentUser' => $currentUser
        ];
    }
    public function signup()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member = (int) $_POST['member'];
            if ($member === 1) {
                $user = new Students($_POST['username'], $_POST['email'], $_POST['password'], $_POST['member'], $_POST['spcode']);
            } else {
                $user = new Teachers($_POST['username'], $_POST['email'], $_POST['password'], $_POST['member'], $_POST['spcode']);
            }

            $result = $user->signup();

            if (!$result['success']) { // if the signup have error we redirect to the signup page with the error message
                header("Location: ./auth.php?mode=signup&error=" . $result['error']);
                exit();
            }

            date_default_timezone_set('Europe/Athens');

            session_start([
                'cookie_lifetime' => 3600, //the cookie will live for 3600second = 1 hour
                'cookie_httponly' => true, //protect from scam 
                'use_strict_mode' => true
            ]);  //the session accept only "coins" that php have created 


            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['role_id'] = $result['role_id'];

            header("Location: ./dashboard.php");
            exit();

        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $result = Users::login($email, $password);

            if (!$result['success']) { // if the login have error we redirect to the login page with the error message
                header("Location: ./auth.php?mode=login&error=" . $result['error']);
                exit();
            }

            date_default_timezone_set('Europe/Athens');
            session_start([
                'cookie_lifetime' => 3600, //the cookie will live for 3600second = 1 hour
                'cookie_httponly' => true, //protect from scam 
                'use_strict_mode' => true
            ]);  //the session accept only "coins" that php have created 
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['userTime'] = $result['userTime'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['role_id'] = $result['role_id'];
            header("Location: ./dashboard.php?");
            exit();
        }
    }

    public function logout()
    {
        session_start();
        session_unset(); //remove all session variables
        session_destroy(); // destroy the session
        header("Location: ./index.php"); // redirect to index.php
        exit();
    }
}