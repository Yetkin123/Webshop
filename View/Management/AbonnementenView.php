<?php

namespace View\Management;

use Controller\AuthorizationController;

// Alleen beheerders en medewerkers mogen deze pagina zien
AuthorizationController::authorize(['Beheerder', 'Medewerker']);

class AbonnementenView extends \View\Framework\GridView
{
    function getClass(): string
    {
        return 'Abonnementen';
    }

    function getData(): array
    {
        return \Controller\ManagementController::getAbonnementen();
    }

    function getTitles(): array
    {
        return [__('abonnement_id'), __('naam'), __('omschrijving'), __('engelse_omschrijving'), __('prijs'), __('afsluitdatum'), __('ingangsdatum'), __('abonnementsperiode'), __('capaciteit'), __('afgesloten'), __('adres_id')];
    }
}

new AbonnementenView();