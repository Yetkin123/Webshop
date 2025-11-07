<?php

namespace View\Framework;

abstract class GridView extends AuthenticatedLayout
{
    abstract function getData(): array;

    abstract function getTitles(): array;

    abstract function getClass(): string;

    function content()
    {

        $data = $this->getData();
        $titles = $this->getTitles();
        $class = $this->getClass();
?>
        <div class="w-full h-full flex">
            <!-- Sidebar -->
            <div class="w-64 fixed inset-y-1/2 transform -translate-y-1/2  p-4 text-white flex flex-col">
                <ul class="space-y-4">
                    <li><a href="/abonnementen" class="block text-center py-2 bg-orange-300 rounded hover:bg-orange-400"><?= __('abonnementen')?> </a></li>
                    <li><a href="/klanten" class="block text-center py-2 bg-orange-300 rounded hover:bg-orange-400"><?= __('klanten')?></a></li>
                    <li><a href="/producten" class="block text-center py-2 bg-orange-300 rounded hover:bg-orange-400"><?= __('producten')?></a></li>
                    <li><a href="/adressen" class="block text-center py-2 bg-orange-300 rounded hover:bg-orange-400"><?= __('adressen')?></a></li>
                    <li><a href="/orders" class="block text-center py-2 bg-orange-300 rounded hover:bg-orange-400"><?= __('orders')?></a></li>
                    <li><a href="/welkom" class="block text-center py-2 bg-orange-300 rounded hover:bg-orange-400"><?= __('welkom')?></a></li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="ml-64 flex-1 p-4">
                <div class="flex justify-end mb-4">
                    <form action="/?action=uploadcsv" method="post" enctype="multipart/form-data" class="grid grid-cols-2 place-items-end gap-4">
                        <input class="hidden" type="hidden" name="class" value="<?= htmlspecialchars($class) ?>">
                        <label class="csv-select cursor-pointer text-orange-300 text-center bg-transparent border-orange-300 border-2 border-solid font-semibold rounded hover:bg-slate-200 w-60">
                            <span>Selecteer CSV</span>
                            <input class="hidden" type="file" name="csv">
                        </label>
                        <button type="submit" class="csv-upload border-orange-300 border-2 border-solid bg-orange-300 text-white font-semibold rounded hover:bg-orange-400 w-60">Upload CSV</button>
                    </form>
                </div>
                <table class="w-full border-collapse mx-auto text-xs text-gray-700 uppercase bg-white dark:bg-gray-700 dark:text-gray-400 shadow-md">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <?php foreach ($titles as $title) : ?>
                                <th class="px-6 py-3"><?= htmlspecialchars($title) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $record) : ?>
                            <tr class="hover:bg-slate-50 hover:cursor-pointer border-b dark:bg-gray-800 dark:border-gray-700 text-center">
                                <?php foreach ($record as $field) : ?>
                                    <td class="px-6 py-4"><?= htmlspecialchars($field) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
<?php
    }
}
?>
