<?php

use Resources\Config\Language;

function __($key): string
{
    $language = new Language();
    return $language->translate($key);
}