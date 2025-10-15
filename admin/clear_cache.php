<?php
/**
 * Clear OPcache
 * Access this once to clear PHP's opcode cache
 * Then delete this file for security
 */

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared successfully!<br>";
    echo "Please refresh the test page now.<br>";
    echo "<br><strong>IMPORTANT: Delete this file after use for security!</strong>";
} else {
    echo "OPcache is not enabled or not available.<br>";
    echo "Please restart your web server instead:<br>";
    echo "<code>sudo systemctl restart apache2</code> or <code>sudo systemctl restart php-fpm</code>";
}
