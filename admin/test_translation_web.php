<?php
/**
 * Web-based translation debug page
 * Access this via: /admin/test_translation_web.php
 */

require_once "includes/inc_all_admin.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Translation Debug</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; }
    </style>
</head>
<body>
    <h1>ITFlow Translation Debug (Web UI)</h1>

    <h2>1. Session Company Locale</h2>
    <pre><?php echo "Session Company Locale: " . ($session_company_locale ?? 'NOT SET'); ?></pre>

    <h2>2. Global Translation Variables</h2>
    <pre><?php
    echo "translation_initialized: " . var_export($GLOBALS['translation_initialized'] ?? 'NOT SET', true) . "\n";
    echo "translation_available: " . var_export($GLOBALS['translation_available'] ?? 'NOT SET', true) . "\n";
    echo "translation_locale: " . var_export($GLOBALS['translation_locale'] ?? 'NOT SET', true) . "\n";
    ?></pre>

    <h2>3. Current Locale Settings</h2>
    <pre><?php
    echo "LC_MESSAGES: " . setlocale(LC_MESSAGES, 0) . "\n";
    echo "Environment LC_ALL: " . (getenv('LC_ALL') ?: 'not set') . "\n";
    echo "Environment LC_MESSAGES: " . (getenv('LC_MESSAGES') ?: 'not set') . "\n";
    echo "Environment LANGUAGE: " . (getenv('LANGUAGE') ?: 'not set') . "\n";
    ?></pre>

    <h2>4. Text Domain Binding</h2>
    <pre><?php
    $domain = 'itflow';
    $bind_result = bindtextdomain($domain, 0); // Query current binding
    echo "Current binding: " . $bind_result . "\n";
    echo "Current text domain: " . textdomain(NULL) . "\n";
    ?></pre>

    <h2>5. Translation Files</h2>
    <pre><?php
    $locale = $session_company_locale ?? 'de_DE';
    $mo_file = $_SERVER['DOCUMENT_ROOT'] . "/locale/$locale/LC_MESSAGES/itflow.mo";
    echo "MO file path: $mo_file\n";
    echo "File exists: " . (file_exists($mo_file) ? 'YES' : 'NO') . "\n";
    if (file_exists($mo_file)) {
        echo "File size: " . filesize($mo_file) . " bytes\n";
        echo "Readable: " . (is_readable($mo_file) ? 'YES' : 'NO') . "\n";
    }
    ?></pre>

    <h2>6. Direct gettext() Test</h2>
    <pre><?php
    $test_strings = ['Dashboard', 'Settings', 'Language', 'Save', 'Clients', 'Invoices'];
    foreach ($test_strings as $str) {
        $translated = gettext($str);
        $class = ($translated !== $str) ? 'success' : 'error';
        echo "<span class='$class'>gettext('$str') = '$translated'</span>\n";
    }
    ?></pre>

    <h2>7. __() Function Test</h2>
    <pre><?php
    foreach ($test_strings as $str) {
        $translated = __($str);
        $class = ($translated !== $str) ? 'success' : 'error';
        echo "<span class='$class'>__('$str') = '$translated'</span>\n";
    }
    ?></pre>

    <h2>8. Real UI Test</h2>
    <p><strong>Localization:</strong> <?php _e('Localization'); ?></p>
    <p><strong>Language:</strong> <?php _e('Language'); ?></p>
    <p><strong>Currency:</strong> <?php _e('Currency'); ?></p>
    <p><strong>Timezone:</strong> <?php _e('Timezone'); ?></p>
    <p><strong>Save:</strong> <?php _e('Save'); ?></p>

    <h2>9. Re-initialize Translation</h2>
    <pre><?php
    // Try re-initializing
    $GLOBALS['translation_initialized'] = false;
    $result = init_translations($session_company_locale ?? 'de_DE');
    echo "Re-initialization result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

    // Test again
    echo "\nAfter re-init:\n";
    foreach (['Settings', 'Language', 'Save'] as $str) {
        $translated = __($str);
        $class = ($translated !== $str) ? 'success' : 'error';
        echo "<span class='$class'>__('$str') = '$translated'</span>\n";
    }
    ?></pre>

</body>
</html>

<?php
require_once "../includes/footer.php";
