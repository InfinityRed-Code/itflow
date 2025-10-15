<?php
/**
 * Debug script for translation system
 * This will show us exactly what's happening with gettext
 */

require_once 'config.php';
require_once 'functions.php';

echo "=== ITFlow Translation Debug ===\n\n";

// Test German
echo "--- Testing German (de_DE) ---\n";
$locale = 'de_DE';

echo "1. Checking file existence:\n";
$mo_file = __DIR__ . "/locale/$locale/LC_MESSAGES/itflow.mo";
$po_file = __DIR__ . "/locale/$locale/LC_MESSAGES/itflow.po";

echo "   MO file: $mo_file\n";
echo "   Exists: " . (file_exists($mo_file) ? "YES" : "NO") . "\n";
if (file_exists($mo_file)) {
    echo "   Size: " . filesize($mo_file) . " bytes\n";
    echo "   Readable: " . (is_readable($mo_file) ? "YES" : "NO") . "\n";
}

echo "\n2. Setting locale:\n";
$locale_variants = [
    $locale . '.UTF-8',
    $locale . '.utf8',
    $locale,
];

foreach ($locale_variants as $lv) {
    $result = setlocale(LC_MESSAGES, $lv);
    echo "   setlocale(LC_MESSAGES, '$lv'): " . ($result !== false ? "SUCCESS ($result)" : "FAILED") . "\n";
    if ($result !== false) {
        break;
    }
}

echo "\n3. Setting environment:\n";
putenv("LC_ALL=$locale");
putenv("LC_MESSAGES=$locale");
putenv("LANGUAGE=$locale");
echo "   Environment variables set\n";

echo "\n4. Binding text domain:\n";
$domain = 'itflow';
$locale_path = __DIR__ . '/locale';
$locale_path_abs = realpath($locale_path);
echo "   Domain: $domain\n";
echo "   Path: $locale_path_abs\n";
$bind_result = bindtextdomain($domain, $locale_path_abs);
echo "   bindtextdomain result: $bind_result\n";

$codeset_result = bind_textdomain_codeset($domain, 'UTF-8');
echo "   bind_textdomain_codeset result: $codeset_result\n";

$textdomain_result = textdomain($domain);
echo "   textdomain result: $textdomain_result\n";

echo "\n5. Testing gettext directly:\n";
$test_strings = ['Dashboard', 'Settings', 'Language', 'Save', 'Clients', 'Invoices'];
foreach ($test_strings as $str) {
    $translated = gettext($str);
    $status = ($translated !== $str) ? "TRANSLATED" : "NOT TRANSLATED";
    echo "   '$str' -> '$translated' [$status]\n";
}

echo "\n6. Checking .mo file content:\n";
if (file_exists($mo_file)) {
    $content = file_get_contents($mo_file);
    echo "   File size: " . strlen($content) . " bytes\n";
    echo "   Magic number: " . bin2hex(substr($content, 0, 4)) . " (should be: de120495 for little-endian)\n";

    // Check if file contains some German words
    $german_words = ['Einstellungen', 'Sprache', 'Speichern', 'Kunden', 'Rechnungen'];
    $found_words = 0;
    foreach ($german_words as $word) {
        if (strpos($content, $word) !== false) {
            $found_words++;
        }
    }
    echo "   German words found in file: $found_words/" . count($german_words) . "\n";
} else {
    echo "   MO file does not exist!\n";
}

echo "\n7. Testing with init_translations():\n";
$init_result = init_translations($locale);
echo "   init_translations('$locale'): " . ($init_result ? "SUCCESS" : "FAILED") . "\n";

echo "\n8. Testing __() function:\n";
foreach ($test_strings as $str) {
    $translated = __($str);
    $status = ($translated !== $str) ? "TRANSLATED" : "NOT TRANSLATED";
    echo "   __('$str') -> '$translated' [$status]\n";
}

echo "\n=== End Debug ===\n";
