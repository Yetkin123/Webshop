<?php
namespace Controller;

class SearchLogController extends Controller
{
    public $tableEmptyMessage;
    public $tableDeletedMessage;
    public $deleteErrorMessage;

    public function __construct()
    {
        // Initieer de vertaalde berichten bij de constructeur
        $this->tableEmptyMessage = __('lege_tabel');
        $this->tableDeletedMessage = __('gegevens_verwijderd');
        $this->deleteErrorMessage = __('fout_tijdens_verwijderen');
    }
    public static function getSearchLog(): array
    {
        $result = SqlController::setup()
            ->sql('SELECT * FROM Zoektermen')
            ->run();
        return $result !== null ? $result : [];
    }
    // Use SqlController to connect to the database to delete the data from the Zoektermen table
    public static function deleteSearchLogs(): bool
    {
        $logs = self::getSearchLog();

        if (empty($logs)) {
            return false; // Tabel is al leeg, dus return false
        }

        $result = SqlController::setup()
            ->sql('DELETE FROM Zoektermen')
            ->run(false);

        return $result !== false;
    }

    // Method for when deletion is successful
    public function deleteMessage(): string
    {
        $success = $this->deleteSearchLogs();

        if (empty($success)) {
            return $this->tableEmptyMessage;
        } elseif ($success) {
            return $this->tableDeletedMessage;
        } else {
            return $this->deleteErrorMessage;
        }
    }
}