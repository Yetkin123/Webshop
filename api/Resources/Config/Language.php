<?php

namespace Resources\Config;

class Language
{
    private $allowed_languages = ['nl', 'en'];

    public $listwithwords = [];

    public function __construct()
    {

        if (isset($_GET['lang'])) {
            $lang = $_GET['lang'];

            if (in_array($lang, $this->allowed_languages)) {
                $_SESSION['lang'] = $lang;
            }
        }

        if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $this->allowed_languages)) {
            require __DIR__ . '/languages/' . $_SESSION['lang'] . '.php';
        } else {
            require __DIR__ . '/languages/nl.php';
        }

        if (isset($listwithwords)) {
            $this->listwithwords = $listwithwords;
        }
    }

    public function getCurrentLang()
    {
        return $_SESSION['lang'] ?? 'nl';
    }

    public function generateFlagLinks() {
        $url = $_SERVER['REQUEST_URI'];
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'];

        return [
            'nl' => [
                'path' => $path,
                'langParam' => 'nl',
                'flagImage' => 'Flag_of_the_Netherlands.svg'
            ],
            'en' => [
                'path' => $path,
                'langParam' => 'en',
                'flagImage' => 'Flag_of_the_United_Kingdom.svg'
            ]
        ];
    }

    public function createUrl($path)
    {
        $lang = $this->getCurrentLang();

        if (strpos($path, '/lang') === 0) {
            $path = '..' . $path;
        }

        if (strpos($path, '?') !== false) {
            return $path . '&lang=' . $lang;
        } else {
            return $path . '?lang=' . $lang;
        }
    }

    public function productLanguage(): string
    {
        $lang = $_SESSION['lang'] ?? 'nl';
        if ($lang === 'nl') {
            return 'naam';
        } else if ($lang === 'en') {
            return 'naam_en';
        } else {
            return 'naam';
        }
    }

    public function descriptionLanguage(): string
    {
        $lang = $_SESSION['lang'] ?? 'nl';
        if ($lang === 'nl') {
            return 'omschrijving';
        } elseif ($lang === 'en')
            return 'omschrijving_en';
        else {
            return 'omschrijving';
        }
    }

    public function translate($key): string
    {
        $listwithwords = $this->listwithwords ?? [];
        if (array_key_exists($key, $listwithwords)) {
            return $listwithwords[$key];
        }

        return $key;
    }
}