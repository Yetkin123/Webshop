<?php
namespace View;

use Resources\Config\Language;
use Controller\AbonnementController;

class AbonnementView extends Framework\AuthenticatedLayout
{
    public function content()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /inloggen');
            exit;
        }

        // Haal producten op
        $producten = AbonnementController::getProducten();

        // Haal vestigingsnamen op
        $vestigingsnamen = AbonnementController::getVestigingsnamen();

        $language = new Language();
        $productLanguage = $language->productLanguage();
        $descriptionLanguage = $language->descriptionLanguage();
        ?>
        <div class="abonnementsamenstellen-container">
            <h1 class="abonnementsamenstellen-h1"><?= __('abonnement_samenstellen'); ?></h1>
            <main>
                <div class="abonnement-container">
                    <form method="post" action="/?action=addabonnement">

                        <label class="abonnementsamenstellen-label" for="abonnement"><?= __('kies_producten'); ?> </label>
                        <?php foreach ($producten as $product): ?>
                            <div class="flex items-center gap-4 ml-24">
                            <input name="<?php echo htmlspecialchars($product['productId']) ?>" type="checkbox">

                                <label for="<?php echo htmlspecialchars($product[$productLanguage]) ?>">
                                    <?php echo htmlspecialchars($product[$productLanguage]) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>

                        <label class="abonnementsamenstellen-label" for="adres"><?= __('adres'); ?></label>
                        <select class="abonnementsamenstellen-border" name="adres">
                            <?php foreach ($vestigingsnamen as $vestigingsnaam): ?>
                                <option value="<?php echo htmlspecialchars($vestigingsnaam['adresId']) ?>">
                                    <?php echo htmlspecialchars($vestigingsnaam['vestigingsnaam']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="abonnementsamenstellen-label" for="naam"><?= __('naam'); ?></label>
                        <select class="abonnementsamenstellen-border" name="naam" required>
                            <option value="Essentials">Essentials</option>
                            <option value="Prestige">Prestige</option>
                            <option value="Signature">Signature</option>
                        </select><br><br>

                        <label class="abonnementsamenstellen-label"
                            for="<?= $descriptionLanguage; ?>"><?= __('omschrijving'); ?></label>
                        <textarea class="abonnementsamenstellen-border abonnementsamenstellen-textarea" type="text"
                            name="<?= $descriptionLanguage; ?>"></textarea>

                        <label class="abonnementsamenstellen-label" for="ingangsdatum"><?= __('ingangsdatum'); ?></label>
                        <input class="abonnementsamenstellen-border abonnementsamenstellen-datepicker" type="date"
                            name="ingangsdatum" required><br><br>

                        <label class="abonnementsamenstellen-label"
                            for="abonnementsperiode"><?= __('abonnementsperiode'); ?></label>
                        <select class="abonnementsamenstellen-border" name="abonnementsperiode" required>
                            <option value="1 jaar"><?= __('een_jaar') ?></option>
                            <option value="2 jaar"><?= __('twee_jaar') ?></option>
                            <option value="3 jaar"><?= __('drie_jaar') ?></option>
                        </select><br><br>

                        <label class="abonnementsamenstellen-label" for="capaciteit"><?= __('capaciteit_apparaat'); ?></label>
                        <select class="abonnementsamenstellen-border" name="capaciteit" required>
                            <option value="10"><?= __('10_50_medewerkers') ?></option>
                            <option value="50"><?= __('50_250_medewerkers') ?></option>
                            <option value="250"><?= __('250_plus_medewerkers') ?></option>
                        </select><br><br>

                        <input class="abonnementsamenstellen-input" type="submit" value="<?= __('bevestig_abonnement') ?>"><br>
                        <p><?= __('abonnementsvoorstel') ?></p>
                    </form>
                </div>
            </main>
        </div>
        <?php
    }
}

new AbonnementView();