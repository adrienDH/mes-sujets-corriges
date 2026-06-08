<?php

include_once "core/provider/QuerySujetDefaultProvider.php";
include_once "core/provider/QueryAllTaxonomyProvider.php";
include_once "core/provider/QuerySearchProvider.php";
include_once "core/provider/UserProvider.php";


// Redirige les personnes qui fail lors du login
function custom_login_failed_redirect($username) {
    $referrer = wp_get_referer(); // Récupère l'URL précédente
    if ($referrer && !strstr($referrer, 'wp-login') && !strstr($referrer, '?login=failed')) {
        wp_redirect($referrer . '?login=failed');
        exit;
    }
}
add_action('wp_login_failed', 'custom_login_failed_redirect');

add_action("wp_ajax_svt_contact", "contact");
add_action("wp_ajax_nopriv_svt_contact", "contact");
function contact() {
    check_ajax_referer('security-svt-sujet-corrige-nonce', 'nonce');

    if(empty($_POST['email']) || empty($_POST['message'])) {
        wp_send_json_error();
    }

    $to = 'adrien.dhermy@gmail.com';
    $subject = 'Nouveau contact du site "Mes sujets corrigés"';
    $message = "Email : ".$_POST['email']."\nMessage : ".$_POST['message'];
    wp_mail($to, $subject, $message);

    wp_send_json_success();
}


/* --- Helpers SVG — accessibles globalement depuis tous les templates --- */
function svt_icon(string $name, float $sw = 1.8, int $size = 16): string {
    $paths = [
        'search'      => '<path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm10 2-4.35-4.35"/>',
        'download'    => '<path d="M12 3v12"/><path d="m7 11 5 4 5-4"/><path d="M5 21h14"/>',
        'check'       => '<path d="m5 12 4 4 9-10"/>',
        'doc'         => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 8h6M9 12h6M9 16h4"/>',
        'user'        => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'x'           => '<path d="M6 6l12 12M18 6 6 18"/>',
        'reset'       => '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/>',
        'calendar'    => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/>',
        'pin'         => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="2.6"/>',
        'sunrise'     => '<path d="M12 2v6M5.6 9.6 7 11M2 17h3M19 17h3M17 11l1.4-1.4M22 21H2"/><path d="M16 17a4 4 0 0 0-8 0"/>',
        'tag'         => '<path d="M11 2H4a2 2 0 0 0-2 2v7l9 9a2.4 2.4 0 0 0 3.4 0l5.6-5.6a2.4 2.4 0 0 0 0-3.4l-9-9Z"/><circle cx="7" cy="7" r="1.4"/>',
        'checkCircle' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/>',
        'book'        => '<path d="M5 4.5A2.5 2.5 0 0 1 7.5 2H20v18H7.5A2.5 2.5 0 0 0 5 22.5Z"/><path d="M5 19.5A2.5 2.5 0 0 1 7.5 17H20"/>',
        'layersHist'  => '<path d="M3 7.5 12 3l9 4.5-9 4.5z"/><path d="m3 12 9 4.5L21 12M3 16.5 12 21l9-4.5"/>',
        'leaf'        => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6"/>',
        'dna'         => '<path d="M2 15c6.7-6 13.3 0 20-6"/><path d="M9 22c1.8-2 2.5-4 2.8-6"/><path d="M15 2c-1.8 2-2.5 4-2.8 6"/><path d="m17 6-2.5-2.5M14 8l-1-1M7 18l2.5 2.5M6.5 12.5l1 1M16.5 10.5l1 1M10 16l1.5 1.5"/>',
        'neuron'      => '<circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4M5.6 5.6 8.4 8.4M15.6 15.6l2.8 2.8M18.4 5.6 15.6 8.4M8.4 15.6 5.6 18.4"/>',
        'cloud'       => '<path d="M17.5 19H9a7 7 0 1 1 6.7-9h1.8a4.5 4.5 0 1 1 0 9Z"/>',
        'layers'      => '<path d="M12.8 2.2a2 2 0 0 0-1.6 0L2.6 6.1a1 1 0 0 0 0 1.8l8.6 3.9a2 2 0 0 0 1.6 0l8.6-3.9a1 1 0 0 0 0-1.8Z"/><path d="m22 17.6-9.2 4.2a2 2 0 0 1-1.6 0L2 17.6"/><path d="m22 12.6-9.2 4.2a2 2 0 0 1-1.6 0L2 12.6"/>',
        'activity'    => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'zap'         => '<path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>',
        'shield'      => '<path d="M20 13c0 5-3.5 7.5-7.7 9a1 1 0 0 1-.6 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.2-2.7a1.2 1.2 0 0 1 1.5 0C14.5 3.8 17 5 19 5a1 1 0 0 1 1 1Z"/>',
        'arrowLeft'   => '<path d="M19 12H5"/><path d="m12 5-7 7 7 7"/>',
    ];
    $d = $paths[$name] ?? '<circle cx="12" cy="12" r="4"/>';
    return '<svg viewBox="0 0 24 24" width="' . $size . '" height="' . $size . '" fill="none" stroke="currentColor" stroke-width="' . $sw . '" stroke-linecap="round" stroke-linejoin="round">' . $d . '</svg>';
}

function svt_theme_icon(string $name, int $size = 13): string {
    $map = [
        'Climat'                        => 'cloud',
        'Plantes'                        => 'leaf',
        'Génétique'                      => 'dna',
        'Génétique (première)'           => 'dna',
        'Système nerveux'                => 'neuron',
        'Stress'                         => 'zap',
        'Géologie'                       => 'layers',
        'Muscle et flux de glucose'      => 'activity',
        'Système immunitaire'            => 'shield',
        'Système immunitaire (première)' => 'shield',
    ];
    return svt_icon($map[$name] ?? 'leaf', 1.9, $size);
}

function svt_theme_colors(): array {
    return [
        'Climat'                        => ['fg' => '#0E7C8B', 'bg' => '#E2F2F4'],
        'Plantes'                        => ['fg' => '#2E7D32', 'bg' => '#E7F4E9'],
        'Génétique'                      => ['fg' => '#6A45B0', 'bg' => '#EEE9F8'],
        'Génétique (première)'           => ['fg' => '#8163C0', 'bg' => '#F1ECF9'],
        'Système nerveux'                => ['fg' => '#3A4FAE', 'bg' => '#E8EAF8'],
        'Stress'                         => ['fg' => '#C03B5B', 'bg' => '#FBE7EC'],
        'Géologie'                       => ['fg' => '#9A5A26', 'bg' => '#F4E9DC'],
        'Muscle et flux de glucose'      => ['fg' => '#B0731A', 'bg' => '#FBF0D7'],
        'Système immunitaire'            => ['fg' => '#15877A', 'bg' => '#DEF2EE'],
        'Système immunitaire (première)' => ['fg' => '#2F968A', 'bg' => '#E4F3F0'],
    ];
}

require_once get_template_directory().'/core/Hooks/ACF/ACFSelectHook.php';
require_once get_template_directory().'/core/Hooks/ACF/ACFFormHook.php';
