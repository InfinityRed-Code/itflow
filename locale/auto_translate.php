<?php
/**
 * Automatic Translation Wrapper Script
 *
 * This script automatically finds and wraps English strings in PHP files with translation functions
 *
 * Usage: php auto_translate.php <file_or_directory>
 *
 * WARNING: This will modify files! Make sure you have backups or use version control.
 *
 * Examples:
 *   php auto_translate.php ../admin/settings_company.php
 *   php auto_translate.php ../admin/settings_*.php
 */

class AutoTranslate {

    private $dry_run = false;
    private $translations = [];
    private $stats = [
        'files_processed' => 0,
        'strings_found' => 0,
        'strings_replaced' => 0,
        'strings_skipped' => 0,
    ];

    public function __construct($dry_run = false) {
        $this->dry_run = $dry_run;
        $this->loadExistingTranslations();
    }

    /**
     * Load existing translations from .po file so we don't duplicate
     */
    private function loadExistingTranslations() {
        $po_file = __DIR__ . '/de_DE/LC_MESSAGES/itflow.po';
        if (!file_exists($po_file)) {
            return;
        }

        $lines = file($po_file, FILE_IGNORE_NEW_LINES);
        $msgid = '';

        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, 'msgid "') === 0) {
                $msgid = substr($line, 7, -1);
                $msgid = stripcslashes($msgid);
                if (!empty($msgid)) {
                    $this->translations[$msgid] = true;
                }
            }
        }

        echo "Loaded " . count($this->translations) . " existing translations\n";
    }

    /**
     * Process a single file
     */
    public function processFile($file_path) {
        if (!file_exists($file_path)) {
            echo "Error: File not found: $file_path\n";
            return false;
        }

        echo "\nProcessing: $file_path\n";
        $this->stats['files_processed']++;

        $content = file_get_contents($file_path);
        $original_content = $content;

        // Pattern 1: Simple HTML tags with text content
        // <h1>Text</h1> -> <h1><?php _e('Text'); ?></h1>
        $content = $this->replaceSimpleTags($content);

        // Pattern 2: Labels
        // <label>Text</label> -> <label><?php _e('Text'); ?></label>
        $content = $this->replaceLabels($content);

        // Pattern 3: Buttons
        // <button>Text</button> -> <button><?php _e('Text'); ?></button>
        $content = $this->replaceButtons($content);

        // Pattern 4: Placeholders
        // placeholder="Text" -> placeholder="<?php echo __('Text'); ?>"
        $content = $this->replacePlaceholders($content);

        // Pattern 5: Option text
        // <option>Text</option> -> <option><?php _e('Text'); ?></option>
        $content = $this->replaceOptions($content);

        if ($content !== $original_content) {
            if (!$this->dry_run) {
                file_put_contents($file_path, $content);
                echo "  ✓ File updated\n";
            } else {
                echo "  [DRY RUN] Would update file\n";
            }
        } else {
            echo "  - No changes needed\n";
        }

        return true;
    }

    /**
     * Replace text in simple HTML tags
     */
    private function replaceSimpleTags($content) {
        // Tags that commonly contain translatable text
        $tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'a', 'th', 'td', 'li', 'strong', 'em'];

        foreach ($tags as $tag) {
            // Match: <tag>English Text</tag>
            // But NOT: <tag><?php ... or <tag attr="...">
            $pattern = '/<' . $tag . '([^>]*)>([A-Z][a-zA-Z0-9\s\-\/]+)<\/' . $tag . '>/';

            $content = preg_replace_callback($pattern, function($matches) use ($tag) {
                $attrs = $matches[1];
                $text = $matches[2];

                // Skip if already has PHP
                if (strpos($attrs, '<?php') !== false) {
                    return $matches[0];
                }

                // Skip if text already has PHP
                if (strpos($text, '<?php') !== false) {
                    return $matches[0];
                }

                // Skip very short text or numbers
                $text = trim($text);
                if (strlen($text) < 3 || is_numeric($text)) {
                    return $matches[0];
                }

                $this->stats['strings_found']++;
                $this->stats['strings_replaced']++;

                return '<' . $tag . $attrs . '><?php _e(\'' . addslashes($text) . '\'); ?></' . $tag . '>';
            }, $content);
        }

        return $content;
    }

    /**
     * Replace label text
     */
    private function replaceLabels($content) {
        // <label>Text <strong...
        $pattern = '/<label>([A-Z][a-zA-Z0-9\s]+)(\s*<)/';

        $content = preg_replace_callback($pattern, function($matches) {
            $text = trim($matches[1]);
            $after = $matches[2];

            if (strlen($text) < 2) {
                return $matches[0];
            }

            $this->stats['strings_found']++;
            $this->stats['strings_replaced']++;

            return '<label><?php _e(\'' . addslashes($text) . '\'); ?>' . $after;
        }, $content);

        return $content;
    }

    /**
     * Replace button text
     */
    private function replaceButtons($content) {
        // <button...>Text</button>
        $pattern = '/<button([^>]*)>(?!<)([A-Z][a-zA-Z0-9\s]+)<\/button>/';

        $content = preg_replace_callback($pattern, function($matches) {
            $attrs = $matches[1];
            $text = trim($matches[2]);

            // Skip if already has PHP
            if (strpos($text, '<?php') !== false) {
                return $matches[0];
            }

            if (strlen($text) < 2) {
                return $matches[0];
            }

            $this->stats['strings_found']++;
            $this->stats['strings_replaced']++;

            return '<button' . $attrs . '><?php _e(\'' . addslashes($text) . '\'); ?></button>';
        }, $content);

        // Handle buttons with icons: <button><i class="..."></i>Text</button>
        $pattern = '/<button([^>]*)>(<i[^>]*><\/i>)([A-Z][a-zA-Z0-9\s]+)<\/button>/';

        $content = preg_replace_callback($pattern, function($matches) {
            $attrs = $matches[1];
            $icon = $matches[2];
            $text = trim($matches[3]);

            if (strpos($text, '<?php') !== false) {
                return $matches[0];
            }

            if (strlen($text) < 2) {
                return $matches[0];
            }

            $this->stats['strings_found']++;
            $this->stats['strings_replaced']++;

            return '<button' . $attrs . '>' . $icon . '<?php _e(\'' . addslashes($text) . '\'); ?></button>';
        }, $content);

        return $content;
    }

    /**
     * Replace placeholder attributes
     */
    private function replacePlaceholders($content) {
        // placeholder="Text"
        $pattern = '/placeholder="([A-Z][a-zA-Z0-9\s\-\/]+)"/';

        $content = preg_replace_callback($pattern, function($matches) {
            $text = $matches[1];

            if (strlen($text) < 3) {
                return $matches[0];
            }

            $this->stats['strings_found']++;
            $this->stats['strings_replaced']++;

            return 'placeholder="<?php echo __(\'' . addslashes($text) . '\'); ?>"';
        }, $content);

        return $content;
    }

    /**
     * Replace option text
     */
    private function replaceOptions($content) {
        // <option value="...">Text</option>
        $pattern = '/<option([^>]*)>(?!<)([A-Z\-][a-zA-Z0-9\s\-]+)<\/option>/';

        $content = preg_replace_callback($pattern, function($matches) {
            $attrs = $matches[1];
            $text = trim($matches[2]);

            if (strpos($text, '<?php') !== false) {
                return $matches[0];
            }

            if (strlen($text) < 2) {
                return $matches[0];
            }

            $this->stats['strings_found']++;
            $this->stats['strings_replaced']++;

            return '<option' . $attrs . '><?php _e(\'' . addslashes($text) . '\'); ?></option>';
        }, $content);

        return $content;
    }

    /**
     * Print statistics
     */
    public function printStats() {
        echo "\n=== Statistics ===\n";
        echo "Files processed: " . $this->stats['files_processed'] . "\n";
        echo "Strings found: " . $this->stats['strings_found'] . "\n";
        echo "Strings replaced: " . $this->stats['strings_replaced'] . "\n";
        echo "Strings skipped: " . $this->stats['strings_skipped'] . "\n";
    }
}

// CLI Usage
if (php_sapi_name() === 'cli') {
    if ($argc < 2) {
        echo "Usage: php auto_translate.php [--dry-run] <file_or_pattern>\n";
        echo "\nExamples:\n";
        echo "  php auto_translate.php ../admin/settings_company.php\n";
        echo "  php auto_translate.php --dry-run '../admin/settings_*.php'\n";
        exit(1);
    }

    $dry_run = false;
    $pattern = $argv[1];

    if ($argv[1] === '--dry-run') {
        $dry_run = true;
        $pattern = $argv[2] ?? '';
    }

    if (empty($pattern)) {
        echo "Error: No file pattern specified\n";
        exit(1);
    }

    echo "ITFlow Auto-Translation Tool\n";
    echo "============================\n";
    if ($dry_run) {
        echo "DRY RUN MODE - No files will be modified\n";
    }
    echo "\n";

    $translator = new AutoTranslate($dry_run);

    // Handle glob patterns
    $files = glob($pattern);
    if (empty($files)) {
        // Try as single file
        if (file_exists($pattern)) {
            $files = [$pattern];
        } else {
            echo "Error: No files found matching: $pattern\n";
            exit(1);
        }
    }

    foreach ($files as $file) {
        if (is_file($file)) {
            $translator->processFile($file);
        }
    }

    $translator->printStats();
}
