# Translation System Installation Guide

## Prerequisites

The ITFlow translation system requires:
- PHP 7.4 or higher
- PHP gettext extension (recommended, but not required)

## Installation Steps

### 1. Verify Directory Structure

Ensure the following directory structure exists:

```
itflow/
├── locale/
│   ├── README.md
│   ├── INSTALLATION.md
│   ├── compile_translations.php
│   ├── en_US/
│   │   └── LC_MESSAGES/
│   │       ├── itflow.po
│   │       └── itflow.mo
│   └── de_DE/
│       └── LC_MESSAGES/
│           ├── itflow.po
│           └── itflow.mo
├── functions.php (updated with translation functions)
└── includes/
    └── load_company_settings.php (updated to initialize translations)
```

### 2. Check PHP Gettext Extension

Check if the gettext extension is installed:

```bash
php -m | grep gettext
```

If not installed:

**Debian/Ubuntu:**
```bash
sudo apt-get install php-gettext
sudo systemctl restart apache2  # or php-fpm
```

**CentOS/RHEL:**
```bash
sudo yum install php-gettext
sudo systemctl restart httpd  # or php-fpm
```

**macOS (Homebrew):**
```bash
brew install gettext
```

**Windows:**
- Uncomment `extension=gettext` in php.ini
- Restart web server

### 3. Install System Locales

Ensure required locales are installed on your system:

**Debian/Ubuntu:**
```bash
sudo locale-gen en_US.UTF-8
sudo locale-gen de_DE.UTF-8
sudo update-locale
```

**CentOS/RHEL:**
```bash
sudo localedef -i en_US -f UTF-8 en_US.UTF-8
sudo localedef -i de_DE -f UTF-8 de_DE.UTF-8
```

Verify installed locales:
```bash
locale -a
```

### 4. Compile Translation Files

If .mo files don't exist or are outdated, compile them:

```bash
cd /path/to/itflow/locale
php compile_translations.php
```

Expected output:
```
ITFlow Translation Compiler
============================

Successfully compiled: locale/en_US/LC_MESSAGES/itflow.po -> locale/en_US/LC_MESSAGES/itflow.mo
Successfully compiled: locale/de_DE/LC_MESSAGES/itflow.po -> locale/de_DE/LC_MESSAGES/itflow.mo

============================
Compilation complete!
Success: 2
Errors: 0
```

### 5. Set Correct Permissions

Ensure web server can read translation files:

```bash
cd /path/to/itflow
chmod -R 755 locale/
chown -R www-data:www-data locale/  # Adjust user/group for your system
```

### 6. Clear PHP Caches

If using opcode caching, clear caches:

```bash
# For OPcache
sudo systemctl restart apache2  # or php-fpm

# For CLI testing
php -r "opcache_reset();"
```

### 7. Configure ITFlow

1. Log in to ITFlow as administrator
2. Go to **Settings > Localization**
3. Select your preferred language (English or German)
4. Click **Save**
5. Refresh the page

## Verification

### Quick Test

Create a test file `test_translation.php` in the ITFlow root:

```php
<?php
require_once 'config.php';
require_once 'functions.php';

// Test English
init_translations('en_US');
echo "English: " . __('Dashboard') . "\n";
echo "English: " . __('Settings') . "\n";

// Test German
init_translations('de_DE');
echo "German: " . __('Dashboard') . "\n";
echo "German: " . __('Settings') . "\n";
```

Run:
```bash
php test_translation.php
```

Expected output:
```
English: Dashboard
English: Settings
German: Dashboard
German: Einstellungen
```

### Check Error Logs

Monitor logs for translation warnings:

```bash
tail -f /var/log/apache2/error.log  # or your web server's error log
```

Look for lines starting with `ITFlow Translation:`

## Troubleshooting

### Translation Files Not Loading

**Symptom:** All text remains in English even after selecting German

**Solutions:**
1. Verify .mo files exist and are not empty:
   ```bash
   ls -lh locale/*/LC_MESSAGES/*.mo
   ```

2. Check file permissions:
   ```bash
   namei -l locale/de_DE/LC_MESSAGES/itflow.mo
   ```

3. Verify locale is installed:
   ```bash
   locale -a | grep de_DE
   ```

4. Check error logs for clues

### "Gettext extension not available" Warning

**Symptom:** Warning in error logs about missing gettext

**Solution:** Install PHP gettext extension (see step 2 above)

**Alternative:** The system will work in fallback mode (English only) without gettext

### "Could not set locale" Warning

**Symptom:** Warning in logs about locale not being set

**Solution:** Install the system locale (see step 3 above)

### Translations Show Old Text

**Symptom:** Changes to .po files don't appear

**Solutions:**
1. Recompile .mo files:
   ```bash
   php locale/compile_translations.php
   ```

2. Clear opcode cache:
   ```bash
   sudo systemctl restart php-fpm
   ```

3. Hard refresh browser (Ctrl+Shift+R)

### Permission Denied Errors

**Symptom:** Web server can't read translation files

**Solution:** Fix permissions:
```bash
chmod -R 755 locale/
chown -R www-data:www-data locale/
```

## Deployment Checklist

Before deploying to production:

- [ ] PHP gettext extension installed
- [ ] System locales installed (en_US.UTF-8, de_DE.UTF-8)
- [ ] All .mo files compiled and up-to-date
- [ ] File permissions set correctly
- [ ] Translation system tested with each language
- [ ] Error logs checked for warnings
- [ ] PHP opcode cache cleared/restarted

## Post-Installation

### Adding Translations to Existing Pages

To add translations to your PHP files:

1. Wrap user-facing text in translation functions:
   ```php
   // Before
   echo "Dashboard";

   // After
   echo __('Dashboard');
   // or
   _e('Dashboard');
   ```

2. Add translations to .po files
3. Recompile with `php locale/compile_translations.php`

### Maintaining Translations

1. Edit .po files when adding new strings
2. Always compile after editing
3. Test in both languages
4. Commit both .po and .mo files to git

## Support

For issues or questions:
- Check [locale/README.md](README.md) for detailed documentation
- Review ITFlow documentation at https://docs.itflow.org/
- Report bugs at https://github.com/itflow-org/itflow/issues

## Next Steps

- Review [locale/README.md](README.md) for translation guidelines
- Consider adding more languages
- Gradually add translations to more pages
- Set up continuous translation workflow with Weblate or similar

---

Installation complete! Your ITFlow instance now supports multiple languages.
