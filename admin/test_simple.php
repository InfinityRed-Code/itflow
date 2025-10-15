<?php
/**
 * Simplest possible gettext test - no ITFlow code involved
 */

echo "<pre>";
echo "=== Pure gettext test (no ITFlow dependencies) ===\n\n";

$locale = 'de_DE.UTF-8';
$domain = 'itflow';
$locale_path = '/var/www/itflow.it-softengine.de/locale';

echo "1. Setting locale...\n";
$result = setlocale(LC_ALL, $locale);
echo "   setlocale(LC_ALL, '$locale') = " . var_export($result, true) . "\n";

echo "\n2. Setting environment...\n";
putenv("LC_ALL=$locale");
putenv("LC_MESSAGES=$locale");
putenv("LANGUAGE=de_DE");
echo "   Environment variables set\n";

echo "\n3. Binding text domain...\n";
$bind = bindtextdomain($domain, $locale_path);
echo "   bindtextdomain('$domain', '$locale_path') = $bind\n";

$codeset = bind_textdomain_codeset($domain, 'UTF-8');
echo "   bind_textdomain_codeset('$domain', 'UTF-8') = $codeset\n";

$td = textdomain($domain);
echo "   textdomain('$domain') = $td\n";

echo "\n4. Testing gettext...\n";
$tests = ['Settings', 'Language', 'Save', 'Clients'];
foreach ($tests as $str) {
    $translated = gettext($str);
    echo "   gettext('$str') = '$translated'\n";
}

echo "\n5. Testing with _() shortcut...\n";
foreach ($tests as $str) {
    $translated = _($str);
    echo "   _('$str') = '$translated'\n";
}

echo "\n6. Check .mo file...\n";
$mo_file = "$locale_path/de_DE/LC_MESSAGES/$domain.mo";
echo "   File: $mo_file\n";
echo "   Exists: " . (file_exists($mo_file) ? 'YES' : 'NO') . "\n";
echo "   Readable: " . (is_readable($mo_file) ? 'YES' : 'NO') . "\n";
echo "   Size: " . filesize($mo_file) . " bytes\n";

// Check file content
$content = file_get_contents($mo_file);
echo "   Contains 'Einstellungen': " . (strpos($content, 'Einstellungen') !== false ? 'YES' : 'NO') . "\n";
echo "   Contains 'Sprache': " . (strpos($content, 'Sprache') !== false ? 'YES' : 'NO') . "\n";

echo "\n7. Verify with dcgettext (direct call)...\n";
foreach ($tests as $str) {
    $translated = dcgettext($domain, $str, LC_MESSAGES);
    echo "   dcgettext('$domain', '$str', LC_MESSAGES) = '$translated'\n";
}

echo "</pre>";
