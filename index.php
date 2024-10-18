<?php
function redirect($request){

    $path = strtok($request, '?');

    $routes = getRoutes();

    if (isset($routes[$path])) 
    {
        require __DIR__ . "/View/{$routes[$path]}";
    }
    else 
    {
        require __DIR__ . "/View/WelcomeView.php";
    }
}

function getRoutes(){
    $routes = [
        // Voeg hier routes toe...
        "/orders" => "Management/OrdersView.php",
        "/adressen" => "Management/AdressenView.php",
        "/abonnementen" => "Management/AbonnementenView.php",
        "/producten" => "Management/ProductenView.php",
        "/klanten" => "Management/KlantenView.php",
        "/inloggen" => "LoginView.php",
        "/welkom" => "WelcomeView.php",
        "/beheer" => "BeheerderView.php",
        "/zoektermen" => "Management/SearchLogView.php",
        "/klantproducten" => "ProductenView.php",
        '/abonnement_samenstellen' => "AbonnementView.php",
        "/registreer" => "RegisterView.php",
        "/account" => "AccountView.php",
        "/winkelwagen" => "CartView.php",
        "/order" => "OrderView.php",
        "/order_bevestiging" => "OrderConfirmationView.php", 
        "/order_betalen" => "OrderPaymentView.php",
        "/retouneren" => "ReturnView.php",
        "/klant" => "CustomerView.php",
        "/home" => "../index.html"
    ];

    return $routes;
}

function handleControllerTask(){
    $action = filter_input(INPUT_GET, 'action');
    if (!empty($action)) {
        switch($action){
            case 'login': \Controller\LoginController::login(); break;
            case 'uploadcsv': \Controller\ManagementController::openCsv(); break;
            case 'uploadimage': \Controller\ManagementController::uploadImages(); break;
            case 'logout': \Controller\LoginController::logout();  break;
            case 'addabonnement': \Controller\AbonnementController::saveAbonnement(); break;
        }
    }
}

session_start();
function autoloadFiles(){
    spl_autoload_register(function($class){
        $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
        require_once $file;
    });
    
    require_once __DIR__ . '/Resources/Config/PublicFunctions.php';
}

autoloadFiles();
$request = $_SERVER['REQUEST_URI'];
handleControllerTask();
redirect($request);