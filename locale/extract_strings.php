<?php
/**
 * Extract translatable strings from PHP files
 * This finds all _e() and __() calls and lists the strings
 *
 * Usage: php extract_strings.php [directory]
 */

$dir = $argv[1] ?? '../admin';
$strings = [];

function extractStringsFromFile($file) {
    global $strings;

    $content = file_get_contents($file);

    // Find _e('string')
    preg_match_all('/_e\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $content, $matches);
    foreach ($matches[1] as $str) {
        $strings[$str] = true;
    }

    // Find __('string')
    preg_match_all('/__\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $content, $matches);
    foreach ($matches[1] as $str) {
        $strings[$str] = true;
    }
}

// Scan directory
$files = glob($dir . '/*.php');
foreach ($files as $file) {
    extractStringsFromFile($file);
}

echo "Found " . count($strings) . " unique strings:\n\n";

// Output in .po format
foreach (array_keys($strings) as $str) {
    echo "msgid \"$str\"\n";
    echo "msgstr \"\"\n\n";
}
