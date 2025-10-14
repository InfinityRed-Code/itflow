<?php
/**
 * Compile PO files to MO files
 * This script compiles .po translation files to .mo files for gettext
 * Run this script whenever translation files are updated
 *
 * Usage: php compile_translations.php
 */

// Simple PO to MO compiler
class PoToMoCompiler {

    public function compile($po_file, $mo_file) {
        if (!file_exists($po_file)) {
            echo "Error: PO file not found: $po_file\n";
            return false;
        }

        $entries = $this->parse_po_file($po_file);
        $mo_data = $this->generate_mo_data($entries);

        if (file_put_contents($mo_file, $mo_data) !== false) {
            echo "Successfully compiled: $po_file -> $mo_file\n";
            return true;
        } else {
            echo "Error: Could not write MO file: $mo_file\n";
            return false;
        }
    }

    private function parse_po_file($filename) {
        $entries = array();
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $msgid = '';
        $msgstr = '';
        $in_msgid = false;
        $in_msgstr = false;

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || $line[0] == '#') {
                continue;
            }

            // Header entry
            if ($line == 'msgid ""' && empty($msgid)) {
                $in_msgid = true;
                continue;
            }

            if (strpos($line, 'msgid "') === 0) {
                // Save previous entry
                if (!empty($msgid)) {
                    $entries[$msgid] = $msgstr;
                }

                $msgid = $this->extract_string($line);
                $msgstr = '';
                $in_msgid = true;
                $in_msgstr = false;
            } elseif (strpos($line, 'msgstr "') === 0) {
                $msgstr = $this->extract_string($line);
                $in_msgid = false;
                $in_msgstr = true;
            } elseif ($line[0] == '"' && ($in_msgid || $in_msgstr)) {
                // Multiline string
                $string_part = $this->extract_string($line);
                if ($in_msgid) {
                    $msgid .= $string_part;
                } elseif ($in_msgstr) {
                    $msgstr .= $string_part;
                }
            }
        }

        // Save last entry
        if (!empty($msgid)) {
            $entries[$msgid] = $msgstr;
        }

        return $entries;
    }

    private function extract_string($line) {
        // Remove msgid " or msgstr " prefix and trailing "
        $line = preg_replace('/^(msgid|msgstr)\s+"/', '', $line);
        $line = preg_replace('/"$/', '', $line);

        // Unescape special characters
        $line = stripcslashes($line);

        return $line;
    }

    private function generate_mo_data($entries) {
        // MO file format constants
        $MAGIC = 0x950412de;
        $REVISION = 0;

        $count = count($entries);
        $keys = array_keys($entries);

        // Sort by msgid for binary search
        sort($keys);

        $ids = '';
        $strs = '';
        $keyoffsets = array();
        $valueoffsets = array();

        // Generate string table
        $id_offset = 0;
        $str_offset = 0;

        foreach ($keys as $key) {
            $keyoffsets[] = array(strlen($key), $id_offset);
            $id_offset += strlen($key) + 1;
            $ids .= $key . "\x00";

            $str = $entries[$key];
            $valueoffsets[] = array(strlen($str), $str_offset);
            $str_offset += strlen($str) + 1;
            $strs .= $str . "\x00";
        }

        // Calculate offsets
        $keystart = 28; // Header size
        $valuestart = $keystart + ($count * 8);
        $keyoffset = $valuestart + ($count * 8);
        $valueoffset = $keyoffset + strlen($ids);

        // Build MO file
        $mo = pack('Iiiiiii',
            $MAGIC,         // Magic number
            $REVISION,      // File format revision
            $count,         // Number of strings
            $keystart,      // Offset of table with original strings
            $valuestart,    // Offset of table with translation strings
            0,              // Size of hashing table (we don't use it)
            0               // Offset of hashing table
        );

        // Original strings offset table
        foreach ($keyoffsets as $offset) {
            $mo .= pack('ii', $offset[0], $keyoffset + $offset[1]);
        }

        // Translated strings offset table
        foreach ($valueoffsets as $offset) {
            $mo .= pack('ii', $offset[0], $valueoffset + $offset[1]);
        }

        // Original strings
        $mo .= $ids;

        // Translated strings
        $mo .= $strs;

        return $mo;
    }
}

// Main execution
echo "ITFlow Translation Compiler\n";
echo "============================\n\n";

$compiler = new PoToMoCompiler();
$base_path = __DIR__;

$locales = array('en_US', 'de_DE');
$success_count = 0;
$error_count = 0;

foreach ($locales as $locale) {
    $po_file = "$base_path/$locale/LC_MESSAGES/itflow.po";
    $mo_file = "$base_path/$locale/LC_MESSAGES/itflow.mo";

    if ($compiler->compile($po_file, $mo_file)) {
        $success_count++;
    } else {
        $error_count++;
    }
}

echo "\n============================\n";
echo "Compilation complete!\n";
echo "Success: $success_count\n";
echo "Errors: $error_count\n";

if ($error_count > 0) {
    exit(1);
}
