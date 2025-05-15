<?php

/* Template Name: Inscription */

?>

<?php acf_form_head(); ?>
<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <p>Vous êtes professeur en lycée et disposez d'une adresse e-mail académique : les inscriptions vous sont ouvertes.</p>
            <p>La procédure d'inscription est simple :</p>
            <ul>
                <li>Remplissez ce formulaire en joignant une première correction ;</li>
                <li>Votre profil et votre contribution seront examinés par nos professeurs ;</li>
                <li>Si votre profil est validé, vous aurez accès à l'ensemble des corrections.</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php
                acf_form(array(
                    'form' => true,
                    'field_groups' => ['group_68263684b6b3c'],
                    'html_submit_button'  => '<input type="submit" class="acf-button button button-primary button-large" value="%s" />',
                    'return' => add_query_arg('success', '1', get_permalink()),
                ));
             ?>  
        </div>
    </div>
</div> 

<?php get_footer(); ?>