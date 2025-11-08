<?php

namespace View\Management;

use Controller\AuthorizationController;

// Alleen beheerders en medewerkers mogen deze pagina zien
AuthorizationController::authorize(['Beheerder', 'Medewerker']);

class KlantenView extends \View\Framework\GridView
{
    function getClass(): string
    {
        return 'Klanten';
    }

    function getData(): array
    {
        return \Controller\ManagementController::getKlanten();
    }

    function getTitles(): array
    {
        return [__('klant_id'), __('kvk_nummer'), __('account_id')];
    }
}

new KlantenView();