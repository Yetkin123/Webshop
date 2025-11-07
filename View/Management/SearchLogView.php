<?php

namespace View\Management;

use Controller\SearchLogController;

use Resources\Config\Language;

use Controller\AuthorizationController;

// Alleen beheerders en medewerkers mogen deze pagina zien
AuthorizationController::authorize(['Beheerder', 'Medewerker']);

// Initialisatie van $language
$language = new Language();

$searchLogController = new SearchLogController();

// Controleer of het formulier is ingediend en verwerk het
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_logs'])) {
    $message = $searchLogController->deleteMessage();

    if ($message === $searchLogController->tableEmptyMessage) {
        echo "<script>alert('$message');</script>";
        header('Refresh: 0; url=' . $language->createUrl("/zoektermen"));
        exit;
    } else if ($message === $searchLogController->tableDeletedMessage) {
        echo "<script>alert('$message');</script>";
        // Refresh de pagina om de bijgewerkte gegevens te tonen
        header('Refresh: 0; url=' . $language->createUrl("/zoektermen"));
        exit;
    } else {
        echo "<script>alert('$message');</script>";
        header('Refresh: 0; url=' . $language->createUrl("/zoektermen"));
        exit;
    }
}

class SearchLogView extends \View\Framework\GridView
{
    function getClass(): string
    {
        return 'SearchLog';
    }

    function getData(): array
    {
        return SearchLogController::getSearchLog();
    }

    function getTitles(): array
    {
        return [__('id'), __('zoekterm'), __('aantal'), __('tijdstip')];
    }
}
new SearchLogView();
?>

<div>
    <h1 class="font-bold text-neutral-700 text-lg"><?= __('verwijder_zoekwoorden'); ?></h1>
    <hr class="h-1 mx-auto my-4 bg-gray-100 border-0 rounded dark:bg-gray-700">
    <form method="post" action="">
        <button type="submit" name="delete_logs" class="rounded-full bg-orange-400 hover:bg-orange-300">
            <?= __('verwijder'); ?>
        </button>
    </form>
</div>
