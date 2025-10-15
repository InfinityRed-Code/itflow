#!/usr/bin/env php
<?php
/**
 * Find missing translations and translate them using DeepL API
 *
 * Usage:
 *   export DEEPL_API_KEY="your-key-here"
 *   php translate_missing.php
 */

// Get DeepL API key from environment
$deepl_api_key = getenv('DEEPL_API_KEY');
if (empty($deepl_api_key)) {
    die("Error: DEEPL_API_KEY environment variable not set.\n\nUsage:\n  export DEEPL_API_KEY=\"your-key\"\n  php translate_missing.php\n");
}

echo "ITFlow Translation Tool\n";
echo "=======================\n\n";

// Step 1: Extract all strings from PHP files
echo "Step 1: Extracting strings from PHP files...\n";

$project_root = dirname(__DIR__);
$strings_to_translate = array();

// Find all PHP files in admin directory
$php_files = glob($project_root . '/admin/*.php');
$php_files = array_merge($php_files, glob($project_root . '/admin/**/*.php'));

$pattern_e = '/_e\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/';
$pattern_func = '/__\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/';

foreach ($php_files as $file) {
    $content = file_get_contents($file);

    // Find _e() calls
    if (preg_match_all($pattern_e, $content, $matches)) {
        foreach ($matches[1] as $str) {
            $strings_to_translate[$str] = true;
        }
    }

    // Find __() calls
    if (preg_match_all($pattern_func, $content, $matches)) {
        foreach ($matches[1] as $str) {
            $strings_to_translate[$str] = true;
        }
    }
}

echo "  Found " . count($strings_to_translate) . " unique strings\n\n";

// Step 2: Load existing translations from .po file
echo "Step 2: Loading existing German translations...\n";

$po_file = __DIR__ . '/de_DE/LC_MESSAGES/itflow.po';
$existing_translations = array();

if (file_exists($po_file)) {
    $po_content = file_get_contents($po_file);

    // Parse .po file to find existing msgid entries
    if (preg_match_all('/msgid "([^"]+)"/s', $po_content, $matches)) {
        foreach ($matches[1] as $msgid) {
            $msgid = stripcslashes($msgid);
            if (!empty($msgid)) {
                $existing_translations[$msgid] = true;
            }
        }
    }
}

echo "  Found " . count($existing_translations) . " existing translations\n\n";

// Step 3: Find missing translations
echo "Step 3: Finding missing translations...\n";

$missing = array();
foreach (array_keys($strings_to_translate) as $str) {
    if (!isset($existing_translations[$str])) {
        $missing[] = $str;
    }
}

echo "  Found " . count($missing) . " missing translations\n\n";

if (empty($missing)) {
    echo "✓ All strings are already translated!\n";
    exit(0);
}

// Step 4: Translate missing strings using DeepL
echo "Step 4: Translating missing strings via DeepL...\n";

$new_entries = array();
$translated_count = 0;

foreach ($missing as $index => $english_text) {
    echo "  [" . ($index + 1) . "/" . count($missing) . "] Translating: " . substr($english_text, 0, 50) . "...\n";

    // Call DeepL API
    $german_text = translate_deepl($english_text, $deepl_api_key);

    if ($german_text !== false) {
        $new_entries[] = array(
            'msgid' => $english_text,
            'msgstr' => $german_text
        );
        $translated_count++;
    } else {
        echo "    WARNING: Translation failed, will use English as fallback\n";
        $new_entries[] = array(
            'msgid' => $english_text,
            'msgstr' => $english_text
        );
    }

    // Sleep to avoid rate limiting
    usleep(100000); // 100ms delay
}

echo "\n  Successfully translated: $translated_count/" . count($missing) . "\n\n";

// Step 5: Update .po file
echo "Step 5: Updating .po file...\n";

$po_content = file_get_contents($po_file);

// Add new entries before the last line
$new_po_entries = "\n";
foreach ($new_entries as $entry) {
    $msgid = addcslashes($entry['msgid'], '"\\');
    $msgstr = addcslashes($entry['msgstr'], '"\\');

    $new_po_entries .= "msgid \"$msgid\"\n";
    $new_po_entries .= "msgstr \"$msgstr\"\n";
    $new_po_entries .= "\n";
}

$po_content .= $new_po_entries;
file_put_contents($po_file, $po_content);

echo "  ✓ Updated $po_file\n\n";

// Step 6: Compile .po to .mo
echo "Step 6: Compiling .mo file...\n";

$compile_script = __DIR__ . '/compile_translations.php';
if (file_exists($compile_script)) {
    include $compile_script;
    echo "  ✓ Compiled .mo file\n\n";
} else {
    echo "  WARNING: compile_translations.php not found, please compile manually\n\n";
}

echo "✓ Translation complete!\n";
echo "\nSummary:\n";
echo "  - Total strings found: " . count($strings_to_translate) . "\n";
echo "  - Already translated: " . count($existing_translations) . "\n";
echo "  - Newly translated: " . count($missing) . "\n";

/**
 * Translate text using DeepL API
 */
function translate_deepl($text, $api_key) {
    $url = 'https://api-free.deepl.com/v2/translate';

    $data = array(
        'auth_key' => $api_key,
        'text' => $text,
        'source_lang' => 'EN',
        'target_lang' => 'DE'
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === false) {
        return false;
    }

    $json = json_decode($result, true);

    if (isset($json['translations'][0]['text'])) {
        return $json['translations'][0]['text'];
    }

    return false;
}
