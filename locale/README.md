# ITFlow Translation System

This directory contains the translation files for ITFlow using the gettext system.

## Directory Structure

```
locale/
├── README.md
├── compile_translations.php
├── en_US/
│   └── LC_MESSAGES/
│       ├── itflow.po
│       └── itflow.mo
└── de_DE/
    └── LC_MESSAGES/
        ├── itflow.po
        └── itflow.mo
```

## Available Languages

- **English (US)** - `en_US` - Base language
- **German (Germany)** - `de_DE` - Fully translated

## How It Works

ITFlow uses the gettext system for internationalization (i18n). The system automatically loads translations based on the company locale setting configured in `Settings > Localization`.

### Translation Functions

The following functions are available in your PHP code:

- `__($message)` - Translate a string
- `_t($message)` - Alias for `__()`
- `_e($message)` - Translate and echo a string
- `_n($singular, $plural, $count)` - Translate plural forms
- `_x($message, $context)` - Translate with context

### Example Usage

```php
// Simple translation
echo __('Dashboard');

// Translation with echo
_e('Welcome to ITFlow');

// Plural translation
echo _n('1 ticket', '%d tickets', $ticket_count);

// Using variables
echo sprintf(__('Welcome %s'), $user_name);
```

## Fallback Behavior

If a translation file is not available for the selected locale:
1. The system falls back to English (the source language)
2. A warning is logged in the error log
3. A user notification is shown once per session (for non-English locales)

This ensures the application always works, even if translations are incomplete.

## Adding a New Language

To add support for a new language:

1. Create the directory structure:
   ```bash
   mkdir -p locale/fr_FR/LC_MESSAGES
   ```

2. Copy the English `.po` file as a template:
   ```bash
   cp locale/en_US/LC_MESSAGES/itflow.po locale/fr_FR/LC_MESSAGES/itflow.po
   ```

3. Edit the new `.po` file:
   - Update the header (Language-Team, Language, etc.)
   - Translate each `msgstr` line
   - Keep `msgid` lines unchanged

4. Compile the translation:
   ```bash
   php locale/compile_translations.php
   ```
   Or add your locale to the `$locales` array in `compile_translations.php` first.

5. The new language will be available in `Settings > Localization`

## Editing Translations

### Using a Text Editor

You can edit `.po` files directly with any text editor. The format is:

```po
msgid "English text"
msgstr "Translated text"
```

### Using Translation Tools (Recommended)

We recommend using specialized tools for easier translation:

- **Poedit** (https://poedit.net/) - Desktop application for Windows, Mac, Linux
- **Lokalize** - KDE translation tool
- **GTranslator** - GNOME translation tool
- **Weblate** - Web-based collaborative translation platform

### After Editing

Always compile the `.po` files to `.mo` files:

```bash
php locale/compile_translations.php
```

Or use `msgfmt` if available on your system:

```bash
msgfmt locale/de_DE/LC_MESSAGES/itflow.po -o locale/de_DE/LC_MESSAGES/itflow.mo
```

## Compiling Translations

### Using the PHP Compiler (No Dependencies)

```bash
php locale/compile_translations.php
```

This script compiles all `.po` files to `.mo` files without requiring gettext tools.

### Using msgfmt (If Available)

```bash
# Compile a specific language
msgfmt locale/de_DE/LC_MESSAGES/itflow.po -o locale/de_DE/LC_MESSAGES/itflow.mo

# Compile all languages
find locale -name "*.po" -execdir msgfmt itflow.po -o itflow.mo \;
```

## Testing Translations

1. Compile the translation files
2. Go to `Settings > Localization` in ITFlow
3. Select your language from the dropdown
4. Click Save
5. Refresh the page to see translations

## File Formats

### .po (Portable Object)

Human-readable text files containing translations. These are the files you edit.

### .mo (Machine Object)

Binary compiled versions of `.po` files. These are used by gettext at runtime for performance.

**Important:** Always commit both `.po` and `.mo` files to version control.

## Translation Guidelines

1. **Keep formatting consistent**: If the English text has punctuation, keep it in translations
2. **Preserve placeholders**: Keep `%s`, `%d`, etc. in the same order
3. **Context matters**: Some words translate differently based on context
4. **Test your translations**: Always check how they look in the UI
5. **Be concise**: UI translations should be brief and clear

## Troubleshooting

### Translations Not Showing Up

1. Check that the `.mo` file exists and is up-to-date
2. Verify the locale code matches exactly (e.g., `de_DE` not `de_de`)
3. Check error logs for translation warnings
4. Clear PHP opcode cache if using OPcache or APC

### Gettext Extension Not Available

If the gettext PHP extension is not installed, the system will fall back to English and log a warning. Install the extension:

```bash
# Debian/Ubuntu
apt-get install php-gettext

# CentOS/RHEL
yum install php-gettext

# macOS (Homebrew)
brew install gettext
```

Then restart your web server.

### Locale Not Available on System

If you see "Could not set locale" warnings, install the locale on your system:

```bash
# Debian/Ubuntu
locale-gen de_DE.UTF-8
update-locale

# CentOS/RHEL
localedef -i de_DE -f UTF-8 de_DE.UTF-8
```

## Contributing Translations

We welcome translation contributions! To contribute:

1. Fork the repository
2. Add or update translation files
3. Test your translations
4. Submit a pull request

Please ensure:
- Both `.po` and `.mo` files are included
- Translations are complete and accurate
- You follow the translation guidelines above

## Resources

- [GNU gettext Manual](https://www.gnu.org/software/gettext/manual/)
- [PHP gettext Documentation](https://www.php.net/manual/en/book.gettext.php)
- [Poedit Download](https://poedit.net/)
- [ITFlow Documentation](https://docs.itflow.org/)

## Current Translation Status

| Language | Code | Status | Strings Translated |
|----------|------|--------|-------------------|
| English (US) | en_US | Base | 100% (80/80) |
| German | de_DE | Complete | 100% (80/80) |

---

Last updated: 2025-01-14
