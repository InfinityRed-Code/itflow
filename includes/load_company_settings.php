<?php
$sql = mysqli_query($mysqli, "SELECT * FROM companies, settings WHERE settings.company_id = companies.company_id AND companies.company_id = 1");
$row = mysqli_fetch_array($sql);

$session_company_name = $row['company_name'];
$session_company_country = $row['company_country'];
$session_company_locale = $row['company_locale'];
$session_company_currency = $row['company_currency'];

$currency_format = numfmt_create($session_company_locale, NumberFormatter::CURRENCY);

// Initialize translation system
$translation_initialized = init_translations($session_company_locale);

// Show warning if translation not available for selected locale but is not English
if (!$translation_initialized && $session_company_locale !== 'en_US' && !str_starts_with($session_company_locale, 'en_')) {
    // Extract language name for user-friendly message
    $locale_parts = explode('_', $session_company_locale);
    $language_code = $locale_parts[0];

    // Map common language codes to names
    $language_names = array(
        'de' => 'German',
        'fr' => 'French',
        'es' => 'Spanish',
        'it' => 'Italian',
        'nl' => 'Dutch',
        'pt' => 'Portuguese',
        'pl' => 'Polish',
        'ru' => 'Russian',
        'ja' => 'Japanese',
        'zh' => 'Chinese',
        'ar' => 'Arabic'
    );

    $language_name = $language_names[$language_code] ?? $session_company_locale;

    // Store notification in session to display once on next page load
    if (!isset($_SESSION['translation_warning_shown'])) {
        $_SESSION['translation_warning'] = "Translation for $language_name is not yet implemented. Using English as fallback.";
        $_SESSION['translation_warning_shown'] = true;
    }
}
