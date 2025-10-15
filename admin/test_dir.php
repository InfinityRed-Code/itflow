<?php
/**
 * Quick test to see what __DIR__ is in functions.php
 */

echo "<pre>";
echo "From test_dir.php:\n";
echo "  __DIR__ = " . __DIR__ . "\n";
echo "  __FILE__ = " . __FILE__ . "\n";

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

// Call a small test function
function test_dir_in_functions() {
    // This simulates what's in functions.php
    $functions_dir = __DIR__;  // This will be the directory of THIS file!
    return $functions_dir;
}

$result = test_dir_in_functions();
echo "\nCalling function defined in this file:\n";
echo "  __DIR__ inside function = $result\n";

// Now let's see what happens if we read the actual value from functions.php
// by creating a test function there

echo "\n\$_SERVER values:\n";
echo "  DOCUMENT_ROOT = " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "  SCRIPT_FILENAME = " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "  PWD = " . (getenv('PWD') ?: 'not set') . "\n";

echo "\nExpected paths:\n";
echo "  functions.php should be in: " . $_SERVER['DOCUMENT_ROOT'] . "/functions.php\n";
echo "  locale should be in: " . $_SERVER['DOCUMENT_ROOT'] . "/locale\n";
echo "  File exists at expected location: " . (file_exists($_SERVER['DOCUMENT_ROOT'] . "/functions.php") ? "YES" : "NO") . "\n";

echo "</pre>";
