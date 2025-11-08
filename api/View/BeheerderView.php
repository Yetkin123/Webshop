<?php

namespace View;

use Resources\Config\Language;

use Controller\AuthorizationController;

// Alleen beheerders en medewerkers mogen deze pagina zien
AuthorizationController::authorize(['Beheerder', 'Medewerker']);

class BeheerderView extends Framework\AuthenticatedLayout
{
    function content()
    {
        $language = new Language();

        ?>
        <div
            class="w-full max-w-[800px] p-[40px] bg-[#fff] mx-[auto] my-[20px] [box-shadow:0_0_10px_rgba(0,_0,_0,_0.1)] text-center rounded-[10px]">
            <div class="grid grid-cols-3 divide-x">
                <div>
                    <h1 class="font-bold text-neutral-700 text-lg"><?= __('upload_afbeelding'); ?></h1>
                    <hr class="h-1 mx-auto my-4 bg-gray-100 border-0 rounded dark:bg-gray-700">
                </div>
                <div>
                    <h1 class="font-bold text-neutral-700 text-lg"><?= __('zoektermen_opvragen'); ?></h1>
                    <hr class="h-1 mx-auto my-4 bg-gray-100 border-0 rounded dark:bg-gray-700">
                </div>
                <div>
                    <h1 class="font-bold text-neutral-700 text-lg"><?= __('beheerfunctionaliteit'); ?></h1>
                    <hr class="h-1 mx-auto my-4 bg-gray-100 border-0 rounded dark:bg-gray-700">
                </div>
            </div>
            <div class="grid grid-cols-3 divide-x items-center">
                <div class="pr-4 border-none">
                    <form action="/?action=uploadimage" method="post" enctype="multipart/form-data">
                        <label
                            class="block text-center border-orange-400 hover:border-orange-300 border-solid border-2 rounded-full text-orange-400 hover:text-orange-300 bg-white hover:bg-slate-200 mb-4 p-4 cursor-pointer">
                            <span><?= __('selecteer_afbeeldingen'); ?></span>
                            <input type="file" name="images" class="hidden">
                        </label>
                        <button type="submit"
                            class="rounded-full bg-orange-400 hover:bg-orange-300"><?= __('upload'); ?></button>
                    </form>
                </div>
                <div class="pl-4 pr-4 border-none">
                    <form action="<?= $language->createUrl('/zoektermen'); ?>" method="post">
                        <button class="rounded-full bg-orange-400 hover:bg-orange-300"><?= __('opvragen'); ?></button>
                    </form>
                </div>
                <div class="pl-4 border-none">
                    <div class="flex justify-center">
                        <ul class="pl-4 list-disc text-left">
                            <li><a class="hover:text-blue-600" href=<?= $language->createUrl("abonnementen"); ?>><?= __('abonnementen'); ?></a></li>
                            <li><a class="hover:text-blue-600" href=<?= $language->createUrl("adressen"); ?>><?= __('adressen'); ?></a></li>
                            <li><a class="hover:text-blue-600" href=<?= $language->createUrl("klanten"); ?>><?= __('klanten'); ?></a></li>
                            <li><a class="hover:text-blue-600" href=<?= $language->createUrl("orders"); ?>><?= __('orders'); ?></a></li>
                            <li><a class="hover:text-blue-600" href=<?= $language->createUrl("producten"); ?>><?= __('producten'); ?></a></li>
                            <li><a class="hover:text-blue-600" href=<?= $language->createUrl("klantproducten"); ?>><?= __('klantproducten'); ?></a></li>
                            <li><a class="hover:text-blue-600" href=<?= $language->createUrl("registreer"); ?>><?= __('registreer'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

new BeheerderView();