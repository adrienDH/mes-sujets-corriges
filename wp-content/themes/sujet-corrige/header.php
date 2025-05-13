<!doctype html>
<html <?php echo get_language_attributes() ?>>
<head>

    <meta charset="<?php get_bloginfo('charset') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link href="/wp-content/themes/sujet-corrige/assets/style.css" rel="stylesheet">
    <script src="/wp-content/themes/sujet-corrige/assets/app.js"></script>
    <link rel="icon" href="https://mes-sujets-corriges.fr/wp-content/uploads/2025/02/favicon.png" sizes="32x32" />
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-00TRKNGGWW"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-00TRKNGGWW');
    </script>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>