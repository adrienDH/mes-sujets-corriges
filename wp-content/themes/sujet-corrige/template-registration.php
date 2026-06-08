<?php

/* Template Name: Inscription */

?>
<?php acf_form_head(); ?>
<?php get_header(); ?>

<div class="page-content">
    <div class="inscription-page">

        <?php if (!isset($_GET['success'])): ?>

            <div class="inscription-intro">
                <h2>Rejoindre la communauté</h2>
                <p>Vous êtes professeur en lycée et disposez d'une adresse e-mail académique&nbsp;: les inscriptions vous sont ouvertes.</p>
                <p>La procédure d'inscription est simple&nbsp;:</p>
                <ul>
                    <li>Remplissez ce formulaire en joignant une première correction&nbsp;;</li>
                    <li>Votre profil et votre contribution seront examinés par nos professeurs&nbsp;;</li>
                    <li>Si votre profil est validé, vous aurez accès à l'ensemble des corrections.</li>
                </ul>
            </div>

            <?php
                acf_form(array(
                    'post_id'            => 'new_post',
                    'form'               => true,
                    'field_groups'       => ['group_68263684b6b3c'],
                    'html_submit_button' => '<input type="submit" class="acf-button button button-primary button-large" value="Envoyer ma candidature" />',
                    'return'             => add_query_arg('success', '1', get_permalink()),
                ));
            ?>

        <?php else: ?>

            <div class="inscription-success">
                <span class="success-icon"><?= svt_icon('checkCircle', 1.8, 52) ?></span>
                <h2>Candidature envoyée</h2>
                <p>Merci pour votre inscription, nous reviendrons vers vous dès que possible.</p>
                <a href="<?= esc_url(home_url('/')) ?>" class="back-home">
                    <?= svt_icon('arrowLeft', 2, 14) ?> Retour aux sujets
                </a>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
