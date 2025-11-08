<?php

namespace View\Management;

use Controller\AuthorizationController;

// Alleen beheerders en medewerkers mogen deze pagina zien
AuthorizationController::authorize(['Beheerder', 'Medewerker']);

class OrdersView extends \View\Framework\GridView
{
    function getClass(): string
    {
        return 'Orders';
    }
    
    function getData(): array
    {
        $controller = new \Controller\ManagementController();
        return $controller->getOrders();
    }

    function getTitles(): array
    {
        return [__('id'), __('status'), __('totaal_prijs'), __('order_status'), __('adres_id'), __('betaald')];
    }
}

new OrdersView();