<?php

class AcfFormHook {

    public function __construct() {
        add_action('acf/save_post',  [$this, 'handleForm']);

    }

    public function handleForm() {
        $page = get_post(get_the_ID());
        if (false === $page instanceof WP_Post || $page->post_title !== 'Inscription') {
            return;
        }

        $lastname = '';
        $firstname = '';
        $email = '';
        $password = '';
        $sujet = null;
        $correctionAttachmentId = null;

        if(!empty($_POST['acf']['field_68263684aad67'])) {
            $lastname = $_POST['acf']['field_68263684aad67'];
        }
        if(!empty($_POST['acf']['field_682636d7aad68'])) {
            $firstname = $_POST['acf']['field_682636d7aad68'];
        }
        if(!empty($_POST['acf']['field_682636e9aad69'])) {
            $email = $_POST['acf']['field_682636e9aad69'];
        }
        if(!empty($_POST['acf']['field_682644ac33f52'])) {
            $password = $_POST['acf']['field_682644ac33f52'];
        }
        if(!empty($_POST['acf']['field_682636f8aad6a'])) {
            $sujet = get_post($_POST['acf']['field_682636f8aad6a']);
        }
        if(!empty($_POST['acf']['field_68263758aad6b'])) {
            $correctionAttachmentId = (int)$_POST['acf']['field_68263758aad6b'];
        }

        if('' === $email || '' === $password || false === $sujet instanceof WP_Post || false === is_int($correctionAttachmentId)) {
            return;
        }

        $userId = $this->createAuthor($email, $password, $lastname, $firstname);

        if(false === is_int($userId)) {
            return;
        }

        $this->updateAcfUser($sujet->post_title, $correctionAttachmentId, $userId);

        $this->sendAdminEmail($lastname, $firstname, $email, $sujet->post_title, $correctionAttachmentId);
    }

    private function createAuthor(string $email, string $password, string $lastname, string $firstname) {
        $userData = [
            'user_login'   => $email,
            'user_pass'    => $password,
            'user_email'   => $email,
            'first_name'   => $firstname, 
            'last_name'    => $lastname,
            'role'         => 'contributor',
        ];

        return wp_insert_user($userData);
    }

    private function updateAcfUser(string $sujetTitle, int $correctionAttachmentId, int $userId) {
        update_field('ma_premiere_correction_le_nom_du_sujet_corrige', $sujetTitle, 'user_'.$userId);
        update_field('ma_premiere_correction_ma_correction', $correctionAttachmentId, 'user_'.$userId);
    }

    private function sendAdminEmail(string $lastname, string $firstname, string $email, string $titreSujet, int $correctionAttachmentId) {
        $message = "
            Adeline,<br>
            Il y a un nouvel inscrit sur mes-sujets-corrigés.fr. Tu dois aller valider l'inscription.<br><br>

            Nom : ".$lastname."<br>
            Prénom : ".$firstname."<br>
            Email : ".$email."<br>
            Titre de la première correction : ".$titreSujet."<br><br>
        ";
        wp_mail('adelinedauria@hotmail.fr', 'Nouvel inscrit sur mes sujets corrigés !', $message, [], [get_attached_file($correctionAttachmentId)]);
    }

}
new AcfFormHook();