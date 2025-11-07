<?php

namespace View;

use Controller\CustomerController;
use View\Framework\AuthenticatedLayout;
use Resources\Config\Language;

class CustomerView extends AuthenticatedLayout
{
    public function content()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /inloggen');
            exit;
        }

        $customerController = CustomerController::setup();
        $userData = $customerController->getUserDataFromSession();

        if ($userData === null) {
            echo "<p>Gebruiker is niet ingelogd.</p>";
            return;
        }

        $subscriptions = $userData['subscriptions'] ?? [];

        // Array om unieke abonnementsnamen bij te houden
        $uniqueSubscriptions = [];

        // Geaggregeerde array voor alle abonnementen met hun producten
        $aggregatedSubscriptions = [];

        foreach ($subscriptions as $subscription) {
            // Controleer of abonnement al in de lijst van unieke abonnementen staat
            if (!isset($uniqueSubscriptions[$subscription['abonnement_id']])) {
                $uniqueSubscriptions[$subscription['abonnement_id']] = $subscription['abonnement_naam'];

                // Voeg het abonnement toe aan de geaggregeerde array
                $aggregatedSubscriptions[$subscription['abonnement_id']] = [
                    'abonnement_naam' => $subscription['abonnement_naam'],
                    'afgesloten' => $subscription['afgesloten'],
                    'producten' => [],
                    'abonnement_id' => $subscription['abonnement_id']
                ];
            }

            // Voeg producten toe aan het juiste abonnement in de geaggregeerde array
            $product_naam = $subscription['product_naam'];
            $product_naam_en = $subscription['product_naam_en'];
            $product_aantal = $subscription['product_aantal'];

            $aggregatedSubscriptions[$subscription['abonnement_id']]['producten'][] = [
                'product_naam' => $product_naam,
                'product_naam_en' => $product_naam_en,
                'product_aantal' => $product_aantal,
                'abonnement_id' => $subscription['abonnement_id'],
            ];
        }

        ?>

        <div class="klantgegevens-container">
            <h1 class="klantgegevens-h1"><?= __('klantgegevens'); ?></h1>

            <h2 class="klantgegevens-h2"><?= __('afgesloten_abonnementsproducten'); ?></h2>

            <?php foreach ($aggregatedSubscriptions as $subscription): ?>
                <?php if ($subscription['afgesloten']): ?>
                    <h3><strong><?= __('abonnement') ?>: </strong><?= $subscription['abonnement_naam'] ?></h3>
                    <h3><strong><?= __('abonnement_id'); ?>: </strong><?= $subscription['abonnement_id'] ?></h3>
                    <table>
                        <tr>
                            <th><?= __('product'); ?></th>
                            <th><?= __('aantal_producten'); ?></th>
                        </tr>
                        <?php foreach ($subscription['producten'] as $product): ?>
                            <tr>
                                <td><?= $product['product_naam'] ?></td>
                                <td><?= $product['product_aantal'] ?>x</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            <?php endforeach; ?>


            <h2 class="klantgegevens-h2"><?= __('aangevraagde_abonnementsproducten'); ?></h2>

            <?php foreach ($aggregatedSubscriptions as $subscription): ?>
                <?php if (!$subscription['afgesloten']): ?>
                    <h3><strong><?= __('abonnement') ?>: </strong><?= $subscription['abonnement_naam'] ?></h3>
                    <h3><strong><?= __('abonnement_id'); ?>: </strong><?= $subscription['abonnement_id'] ?></h3>
                    <table>
                        <tr>
                            <th><?= __('product'); ?></th>
                            <th><?= __('aantal_producten'); ?></th>
                        </tr>
                        <?php foreach ($subscription['producten'] as $product): ?>
                            <tr>
                                <td><?= $product['product_naam'] ?></td>
                                <td><?= $product['product_aantal'] ?>x</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                <?php endif; ?>
            <?php endforeach; ?>

            <h2 class="klantgegevens-h2"><?= __('services') ?></h2>
            <table>
                <tr>
                    <th><?= __('service_id'); ?></th>
                    <th><?= __('servicetijdstip'); ?></th>
                    <th><?= __('aanvraagdatum'); ?></th>
                    <th><?= __('servicetype'); ?></th>
                </tr>
                <?php foreach ($userData['services'] ?? [] as $service): ?>
                    <tr>
                        <td><?= $service['serviceId'] ?></td>
                        <td><?= $service['servicetijdstip'] ?></td>
                        <td><?= $service['aanvraagdatum'] ?></td>
                        <td><?= $service['servicetype'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>


            <h2 class="klantgegevens-h2"><?= __('facturen') ?></h2>
            <table>
                <tr>
                    <th><?= __('factuur_id'); ?></th>
                    <th><?= __('factuurbedrag'); ?></th>
                    <th><?= __('status'); ?></th>
                </tr>
                <?php foreach ($userData['invoices'] ?? [] as $invoice): ?>
                    <tr>
                        <td><?= $invoice['factuurId'] ?></td>
                        <td><?= $invoice['factuurbedrag'] ?></td>
                        <td><?= $invoice['status'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>


            <h2 class="klantgegevens-h2"><?= __('betaalgegevens') ?></h2>
            <table>
                <tr>
                    <th><?= __('betaal_id'); ?></th>
                    <th><?= __('rekeningnummer'); ?></th>
                    <th><?= __('betaalmethode'); ?></th>
                    <th><?= __('betaaldatum'); ?></th>
                    <th><?= __('betalingstype'); ?></th>
                </tr>
                <?php foreach ($userData['paymentMethods'] ?? [] as $paymentMethod): ?>
                    <tr>
                        <td><?= $paymentMethod['betaalId'] ?></td>
                        <td><?= $paymentMethod['rekeningnummer'] ?></td>
                        <td><?= $paymentMethod['betaalmethode'] ?></td>
                        <td><?= $paymentMethod['betaaldatum'] ?></td>
                        <td><?= $paymentMethod['betalingstype'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h2 class="klantgegevens-h2"><?= __('automatische_incassos') ?></h2>
            <table>
                <tr>
                    <th><?= __('abonnement_id'); ?></th>
                    <th><?= __('status'); ?></th>
                    <th><?= __('incassobedrag'); ?></th>
                </tr>
                <?php foreach ($userData['directDebits'] ?? [] as $directDebit): ?>
                    <tr>
                        <td><?= $directDebit['abonnementId'] ?></td>
                        <td><?= $directDebit['status'] ?></td>
                        <td><?= $directDebit['incassobedrag'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <?php
    }
}

new CustomerView();

?>