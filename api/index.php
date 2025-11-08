<?php
// Debug info - TIJDELIJK
error_log("=== VERCEL DEBUG ===");
error_log("REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET'));
error_log("SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET'));
error_log("PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET'));
error_log("QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NOT SET'));
error_log("===================");

function redirect($request){
    $path = strtok($request, '?');

    // normaliseer: verwijderd trailing slash (behalve root) en lowercase
    if ($path !== '/' && substr($path, -1) === '/') {
        $path = rtrim($path, '/');
    }
    $path = strtolower($path);

    // debug log naar server error log
    error_log("[router] requested path: " . $path);

    $routes = getRoutes();

    if (isset($routes[$path])) 
    {
        require __DIR__ . "/View/{$routes[$path]}";
    }
    else 
    {        
        // Anders: normale 404 fallback voor pagina's
        error_log("[router] no route matched for {$path}, serving WelcomeView");
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
        "/" => "Home.php",
        "/coffeepedia" => "Coffeepedia.php",
        "/about" => "About.php",
        "/products" => "Products.php",
        "/subscriptions" => "Subscriptions.php"
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

// Laat de PHP built-in server statische bestanden direct serveren
if (php_sapi_name() === 'cli-server') {
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // gebruik project root (één niveau boven api) als document root
    $docRoot = realpath(__DIR__ . '/..'); // c:\...\Webshop
    $filePath = $docRoot . $urlPath;

    // beveiliging: normaliseer pad en controleer of het daadwerkelijk binnen docRoot valt
    $real = realpath($filePath);
    if ($real && str_starts_with($real, $docRoot) && is_file($real)) {
        return false; // laat de built-in server het bestand teruggeven
    }
}

redirect($request);