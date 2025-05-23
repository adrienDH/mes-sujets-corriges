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


require_once get_template_directory().'/core/Hooks/ACF/ACFSelectHook.php';
require_once get_template_directory().'/core/Hooks/ACF/ACFFormHook.php';
