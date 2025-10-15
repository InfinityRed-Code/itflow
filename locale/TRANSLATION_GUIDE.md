# ITFlow Translation Guide - Seiten übersetzen

## Übersicht

Um eine Seite zu übersetzen, müssen Sie:
1. Hardcoded englische Strings durch Übersetzungsfunktionen ersetzen
2. Die Strings in den .po-Dateien hinzufügen (falls noch nicht vorhanden)
3. Die .mo-Dateien neu kompilieren

## Übersetzungsfunktionen

| Funktion | Verwendung | Beispiel |
|----------|------------|----------|
| `_e($text)` | Übersetzen + ausgeben | `<?php _e('Save'); ?>` |
| `__($text)` | Übersetzen + zurückgeben | `$title = __('Dashboard');` |
| `_n($singular, $plural, $count)` | Pluralformen | `_n('1 item', '%d items', $count)` |

## Schritt-für-Schritt Anleitung

### Schritt 1: Identifizieren Sie übersetzbare Strings

Suchen Sie nach:
- Text in `<label>`, `<h1>`, `<h2>`, `<h3>`, etc.
- Text in `<button>`, `<a>` Elementen
- `placeholder="..."` Attribute
- Fest kodierte Nachrichten

### Schritt 2: Ersetzen Sie die Strings

#### Beispiel 1: Einfacher Text
```php
<!-- VORHER -->
<h3>Company Details</h3>

<!-- NACHHER -->
<h3><?php _e('Company Details'); ?></h3>
```

#### Beispiel 2: Labels
```php
<!-- VORHER -->
<label>Name <strong class="text-danger">*</strong></label>

<!-- NACHHER -->
<label><?php _e('Name'); ?> <strong class="text-danger">*</strong></label>
```

#### Beispiel 3: Buttons
```php
<!-- VORHER -->
<button type="submit" name="edit_company" class="btn btn-primary">
    <i class="fas fa-check mr-2"></i>Save
</button>

<!-- NACHHER -->
<button type="submit" name="edit_company" class="btn btn-primary">
    <i class="fas fa-check mr-2"></i><?php _e('Save'); ?>
</button>
```

#### Beispiel 4: Placeholders
```php
<!-- VORHER -->
<input type="text" placeholder="Company Name" value="...">

<!-- NACHHER -->
<input type="text" placeholder="<?php echo __('Company Name'); ?>" value="...">
```

#### Beispiel 5: Dropdown-Optionen
```php
<!-- VORHER -->
<option value="">- Country -</option>

<!-- NACHHER -->
<option value="">- <?php _e('Country'); ?> -</option>
```

### Schritt 3: Strings zu .po-Dateien hinzufügen

Wenn ein String noch nicht in den .po-Dateien existiert:

#### In `locale/de_DE/LC_MESSAGES/itflow.po`:
```po
msgid "Company Details"
msgstr "Firmendetails"

msgid "Name"
msgstr "Name"

msgid "Address"
msgstr "Adresse"

msgid "City"
msgstr "Stadt"

msgid "State / Province"
msgstr "Bundesland / Provinz"

msgid "Postal Code"
msgstr "Postleitzahl"

msgid "Country"
msgstr "Land"

msgid "Phone"
msgstr "Telefon"

msgid "Email"
msgstr "E-Mail"

msgid "Website"
msgstr "Webseite"

msgid "Tax ID"
msgstr "Steuernummer"

msgid "Company Name"
msgstr "Firmenname"

msgid "Street Address"
msgstr "Straßenadresse"

msgid "Phone Number"
msgstr "Telefonnummer"

msgid "Email address"
msgstr "E-Mail-Adresse"

msgid "Website address"
msgstr "Webseiten-Adresse"

msgid "Upload company logo"
msgstr "Firmenlogo hochladen"

msgid "Remove Logo"
msgstr "Logo entfernen"
```

#### In `locale/en_US/LC_MESSAGES/itflow.po`:
```po
msgid "Company Details"
msgstr "Company Details"

msgid "Name"
msgstr "Name"

# ... (alle gleich wie msgid, da Englisch die Basis ist)
```

### Schritt 4: Kompilieren
```bash
cd /var/www/itflow.it-softengine.de/locale
php compile_translations.php
```

### Schritt 5: Testen
- Seite im Browser aufrufen
- Locale auf Deutsch umstellen (Settings → Localization)
- Seite neu laden
- Überprüfen, dass deutsche Texte angezeigt werden

## Vollständiges Beispiel: settings_company.php übersetzen

Hier ist ein Beispiel wie `settings_company.php` übersetzt aussehen würde:

```php
<div class="card card-dark">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-fw fa-briefcase mr-2"></i><?php _e('Company Details'); ?></h3>
    </div>
    <div class="card-body">
        <form action="post.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">

            <div class="row">
                <div class="col-md-3 text-center">
                    <?php if ($company_logo) { ?>
                        <img class="img-thumbnail" src="<?php echo "../uploads/settings/$company_logo"; ?>">
                        <a href="post.php?remove_company_logo" class="btn btn-outline-danger btn-block">
                            <?php _e('Remove Logo'); ?>
                        </a>
                        <hr>
                    <?php } ?>
                    <div class="form-group">
                        <label><?php _e('Upload company logo'); ?></label>
                        <input type="file" class="form-control-file" name="file" accept=".jpg, .jpeg, .png">
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label><?php _e('Name'); ?> <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
                            </div>
                            <input type="text" class="form-control" name="name"
                                   placeholder="<?php echo __('Company Name'); ?>"
                                   value="<?php echo $company_name; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php _e('Address'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="address"
                                   placeholder="<?php echo __('Street Address'); ?>"
                                   value="<?php echo $company_address; ?>">
                        </div>
                    </div>

                    <!-- ... weitere Felder analog ... -->

                    <hr>

                    <button type="submit" name="edit_company" class="btn btn-primary text-bold">
                        <i class="fas fa-check mr-2"></i><?php _e('Save'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
```

## Was NICHT übersetzt wird

❌ **Variablen-Inhalte aus der Datenbank:**
```php
<?php echo $company_name; ?>  // NICHT übersetzen - ist User-Daten
```

❌ **Technische Werte:**
```php
<input type="hidden" name="csrf_token" value="...">  // NICHT übersetzen
```

❌ **CSS-Klassen, IDs:**
```php
<div class="btn btn-primary">  // NICHT übersetzen
```

❌ **Icon-Klassen:**
```php
<i class="fas fa-check mr-2"></i>  // NICHT übersetzen
```

✅ **Sichtbarer Text für Benutzer:**
```php
<h3>Company Details</h3>  // ✅ ÜBERSETZEN
<button>Save</button>  // ✅ ÜBERSETZEN
<label>Name</label>  // ✅ ÜBERSETZEN
placeholder="Enter name"  // ✅ ÜBERSETZEN
```

## Priorisierung

Übersetzen Sie Seiten in dieser Reihenfolge:
1. **Admin-Einstellungen** (Settings-Seiten) - wichtig für Admin
2. **Navigation** (Top Nav, Side Nav) - wird überall gesehen
3. **Client Management** - häufig verwendet
4. **Ticket System** - häufig verwendet
5. **Reports & Dashboard** - weniger kritisch
6. **Modals** - bei Bedarf

## Automatisierung (Optional)

Sie könnten ein Skript erstellen, das automatisch nach übersetzbare Strings sucht:

```bash
# Finde alle hardcoded Labels
grep -rn '<label>[^<]*</label>' admin/*.php

# Finde alle Buttons mit Text
grep -rn '<button[^>]*>[^<]*</button>' admin/*.php
```

## Häufige Muster

### Pattern 1: Card Headers
```php
<div class="card-header">
    <h3 class="card-title"><i class="fas fa-fw fa-icon mr-2"></i><?php _e('Title'); ?></h3>
</div>
```

### Pattern 2: Form Labels
```php
<label><?php _e('Field Name'); ?> <strong class="text-danger">*</strong></label>
```

### Pattern 3: Buttons
```php
<button type="submit" name="action" class="btn btn-primary">
    <i class="fas fa-icon mr-2"></i><?php _e('Action'); ?>
</button>
```

### Pattern 4: Links
```php
<a href="..." class="btn btn-danger">
    <?php _e('Delete'); ?>
</a>
```

## Tipps

1. **Konsistenz:** Verwenden Sie immer die gleichen Übersetzungen für die gleichen Begriffe
2. **Kontext:** Manchmal brauchen Wörter verschiedene Übersetzungen je nach Kontext (z.B. "Close" als Verb vs. "Close" als Adjektiv)
3. **Teste früh:** Übersetzen Sie eine Seite, testen Sie sie, dann weiter zur nächsten
4. **Nutzen Sie existierende Strings:** Viele Strings wie "Save", "Cancel", "Delete" sind bereits in den .po-Dateien
5. **Dokumentieren Sie:** Notieren Sie sich welche Seiten bereits übersetzt sind

## Support

- Bei Fragen: Siehe [locale/README.md](README.md)
- Bei Problemen: Siehe [locale/INSTALLATION.md](INSTALLATION.md#troubleshooting)
