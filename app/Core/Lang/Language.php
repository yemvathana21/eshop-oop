<?php
namespace App\Core\Lang;

class Language {
    private static $translations = [];
    private static $currentLang = 'en';

    public static function init() {
        $lang = $_SESSION['lang'] ?? 'en';
        self::$currentLang = $lang;
        self::load($lang);
    }

    private static function load($lang) {
        $file = __DIR__ . DIRECTORY_SEPARATOR . $lang . '.php';
        if (file_exists($file)) {
            self::$translations = require $file;
        }
    }

    public static function set($lang) {
        if (in_array($lang, ['en', 'km'])) {
            $_SESSION['lang'] = $lang;
            self::$currentLang = $lang;
            self::load($lang);
        }
    }

    public static function get($key) {
        return self::$translations[$key] ?? $key;
    }

    public static function current() {
        return self::$currentLang;
    }
}

// Shorthand function
function t($key) {
    return \App\Core\Lang\Language::get($key);
}
