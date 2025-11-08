<?php

namespace View\Management;

use Controller\AuthorizationController;

// Alleen beheerders en medewerkers mogen deze pagina zien
AuthorizationController::authorize(['Beheerder', 'Medewerker']);

class ProductenView extends \View\Framework\GridView
{
    function getClass(): string
    {
        return 'Producten';
    }
   
    function getData(): array
    {
        return \Controller\ManagementController::getProducten();
    }

    public function getTitles(): array
    {
        return [__('product_id'), __('naam'), __('engelse_naam'), __('omschrijving'), __('engelse_omschrijving'), __('prijs'), __('afbeelding')];
    }
}

new ProductenView();