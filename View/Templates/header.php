<nav class="h-32  w-full bg-white border-b-orange-300 border-b-4 shadow-lg flex items-center justify-between">
    <div class="h-full p-3 w-32">
        <a href="/welkom">
            <img src="Resources/images/logo-color.svg" class="h-full stroke-white" alt="">
        </a>

    </div>
    <div class="flex flex-row gap-10">
		<div>
            <a class="uppercase font-bold text-orange-300 tracking-wide"
                href="/home">Home</a>
        </div>
        <div>
            <a class="uppercase font-bold text-orange-300 tracking-wide"
                href="<?= $language->createUrl('producten'); ?>"><?= __('producten'); ?></a>
        </div>
        <div>
            <a class="uppercase font-bold text-orange-300 tracking-wide"
                href="<?= $language->createUrl('abonnementen'); ?>"><?= __('abonnementen'); ?></a>
        </div>
        <div>
            <a class="uppercase font-bold text-orange-300 tracking-wide"
                href="<?= $language->createUrl('orders'); ?>"><?= __('orders'); ?></a>
        </div>
        <div>
            <a class="uppercase font-bold text-orange-300 tracking-wide"
                href="<?= $language->createUrl('adressen'); ?>"><?= __('adressen'); ?></a>
        </div>
        <div>
            <a class="uppercase font-bold text-orange-300 tracking-wide"
                href="<?= $language->createUrl('klanten'); ?>"><?= __('klanten'); ?></a>
        </div>
        <div>
            <a class="uppercase font-bold text-orange-300 tracking-wide"
                href="<?= $language->createUrl('beheer'); ?>"><?= __('beheer'); ?></a>
        </div>
    </div>
    <div class="justify-self-end mr-20 flex gap-6">
        <div class="relative inline-block group">
            <div>
                <a href="<?= $language->createUrl('/account'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-12 w-12 stroke-orange-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </a>
            </div>
            <div
                class="hidden absolute min-w-[80px] left-2/4 -translate-x-1/2 p-[20px] flex-col group-hover:flex dropdown-flag w-full bg-white border-b-orange-300 border-b-4 shadow-lg items-center justify-between">
                <a href="/?action=logout">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-11 w-11 stroke-orange-300">
                        <path d="M21 12L13 12" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M18 15L20.913 12.087V12.087C20.961 12.039 20.961 11.961 20.913 11.913V11.913L18 9"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M16 5V4.5V4.5C16 3.67157 15.3284 3 14.5 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H14.5C15.3284 21 16 20.3284 16 19.5V19.5V19"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="relative inline-block group">
            <?php
            $flagLinks = $language->generateFlagLinks();
            $currentLang = $language->getCurrentLang();
            $topFlag = $flagLinks[$currentLang];
            $dropdownFlag = $flagLinks[$currentLang == 'nl' ? 'en' : 'nl'];
            ?>
            <div
                class="rounded-full border-2 border-black h-11 w-11 bg-cover bg-center bg-[url('/Resources/images/<?= $topFlag['flagImage'] ?>')]">
            </div>
            <div
                class="hidden absolute min-w-[80px] left-2/4 -translate-x-1/2 p-[20px] flex-col group-hover:flex dropdown-flag w-full bg-white border-b-orange-300 border-b-4 shadow-lg items-center justify-between">
                <a href="<?= $dropdownFlag['path'] . '?lang=' . $dropdownFlag['langParam']; ?>">
                    <div
                        class="rounded-full border-2 border-black h-11 w-11 bg-cover bg-center bg-[url('/Resources/images/<?= $dropdownFlag['flagImage'] ?>')]">
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>