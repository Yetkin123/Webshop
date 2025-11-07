<?php

namespace View\Management;

use Controller\AuthorizationController;

// Alleen beheerders en medewerkers mogen deze pagina zien
AuthorizationController::authorize(['Beheerder', 'Medewerker']);

class AdressenView extends \View\Framework\GridView
{
    function getClass(): string
    {
        return 'Adressen';
    }

    function getData(): array
    {
        return \Controller\ManagementController::getAdressen();
    }

    function getTitles(): array
    {
        return [__('adres_id'), __('vestigingsnaam'), __('postcode'), __('huisnummer'), __('toevoeging'), __('klant_id'), __('contact_id'), __('email_id')];
    }
}

new AdressenView();