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
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Spectral:ital,wght@0,400;0,500;0,600;1,400;1,600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
  <?php if (!is_front_page()): ?>
  <nav class="page-nav">
      <a class="page-nav-brand" href="<?= esc_url(home_url('/')) ?>">
          <span class="brand-mark">
              <svg viewBox="0 0 40 40" width="20" height="20" fill="none">
                  <path d="M20 6c7 0 12 4 12 11 0 5-4 8-8 8-1.5 0-3-.4-4-1.2" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>
                  <path d="M20 6c-2.5 4-3 9-1 13.5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" opacity=".75"/>
                  <path d="M8 30h24M11 35h18" stroke="#E6C79B" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
          </span>
          <span class="brand-title">Mes sujets corrigés</span>
      </a>
      <span class="page-nav-spacer"></span>
      <a href="<?= esc_url(home_url('/')) ?>" class="page-nav-back">
          <?= svt_icon('arrowLeft', 2, 14) ?> Retour aux sujets
      </a>
      <?php if (is_user_logged_in()): ?>
          <a href="<?= esc_url(admin_url()) ?>" class="page-nav-btn">
              <?= svt_icon('user', 1.8, 14) ?> Administration
          </a>
      <?php else: ?>
          <button class="page-nav-btn" data-bs-toggle="modal" data-bs-target="#modal_connection">
              <?= svt_icon('user', 1.8, 14) ?> Se connecter
          </button>
      <?php endif; ?>
  </nav>
  <?php endif; ?>

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