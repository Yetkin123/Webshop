<?php

namespace Controller;

use Resources\Config\Language;

class LoginController extends Controller
{
    public static function login()
    {
        $language = new Language();
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = SqlController::setup()
            ->sql('SELECT Accounts.accountId, Accounts.wachtwoord, Accounts.accounttype, Emails.email FROM Accounts JOIN Emails ON Emails.emailId = Accounts.emailId WHERE Emails.email = :username')
            ->params(['username' => $username])
            ->run();

        if ($user && password_verify($password, $user[0]['wachtwoord'])) {
            $_SESSION['user_id'] = $user[0]['accountId'];
            $_SESSION['accounttype'] = $user[0]['accounttype'];
            header('Location:' . $language->createUrl('/welkom'));
            exit;
        } else {
            $_SESSION['login_error'] = __('inlogfout');
            header('Location:' . $language->createUrl('/inloggen'));
            exit;
        }
    }

    public static function logout()
    {
        $language = new Language();
        session_start();
        session_destroy();
        header('Location:' . $language->createUrl('./inloggen'));

        exit();
    }
}
