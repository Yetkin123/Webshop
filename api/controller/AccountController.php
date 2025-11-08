<?php

namespace Controller;

class AccountController extends Controller
{
    public static function setup(): AccountController
    {
        return new AccountController();
    }

    public function getName(): string
    {
        $userId = $_SESSION['user_id'] ?? null;

        $accounttypeResultaat = SqlController::setup()
            ->sql('SELECT accounttype, emailId FROM Accounts WHERE accountId = :accountId')
            ->params([':accountId' => $userId])
            ->run();

        if (!empty($accounttypeResultaat)) {
            $accounttype = $accounttypeResultaat[0]['accounttype'];
            $emailId = $accounttypeResultaat[0]['emailId'];

            if ($accounttype == 'Klant') {
                $naam = SqlController::setup()
                    ->sql('SELECT vestigingsnaam FROM Adressen WHERE emailId = :emailId')
                    ->params([':emailId' => $emailId])
                    ->run();

                if (!empty($naam) && isset($naam[0]['vestigingsnaam'])) {
                    return $naam[0]['vestigingsnaam'];
                } else {
                    return '';
                }
            } elseif ($accounttype == 'Beheerder' || $accounttype == 'Medewerker') {
                return __($accounttype);
            }
        }

        return 'Onbekende accounttype'; 
    }
}
