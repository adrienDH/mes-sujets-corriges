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
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Delius&display=swap" rel="stylesheet">

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
  <div class="container">
      <div class="row">
        <div class="col-12 my-5">
            <div class="d-flex justify-content-between align-items-center">
                <h1><a href="<?= home_url('/') ?>">Mes sujets corrigés</a></h1>
                <div class="button-wrapper">
                    <img src="<?= get_template_directory_uri(); ?>/assets/images/geologie.png" alt="Fleur" class="geologie">
                    <?php if(is_user_logged_in()): ?>
                      <a href="<?php echo admin_url() ?>" class="btn btn-outline-success">Accèder à l'administration</a>
                    <?php else: ?>
                      <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal_connection">Me connecter</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="modal_connection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="connection" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="staticBackdropLabel">Merci de vous connecter ou d'avoir les droits suffisants pour accèder aux documents de correction</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if(!empty($_GET['login']) && $_GET['login'] === 'failed'): ?>
                    <div class="text-danger text-center">Identifiant ou votre mot de passe est incorrect</div>
                <?php endif; ?>
                <div class="container d-flex justify-content-center">
                    <div class="login-form-container">
                        <?= wp_login_form([
                            'redirect' => home_url()
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>