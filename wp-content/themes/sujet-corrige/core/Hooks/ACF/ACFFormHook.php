<?php

class AcfFormHook {

    public function __construct() {
        add_action('acf/save_post',  [$this, 'handleForm']);

    }

    public function handleForm($postId) {
        $page = get_post($postId);
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
            $lastname =^$_POST['acf']['field_68263684aad67'];
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
            $correctionAttachmentId = $_POST['acf']['field_68263758aad6b'];
        }

        if('' === $email || '' === $password || false === $sujet instanceof WP_Post || false === is_int(correctionAttachmentId)) {
            return;
        }

        $userId = $this->createAuthor($email, $password, $lastname, $firstname);

        if(false === is_int($userId)) {
            return;
        }

        $this->updateAcfUser($sujet->post_title, $correctionAttachmentId)

        $this->sendAdminEmail($lastname, $firstname, $email, $sujet->post_title, $correctionAttachmentId)

    }

    private function createAuthor(string $email, string $password, string $lastname, string $firstname) {
        $userData = [
            'user_login'   => $email,
            'user_pass'    => $password,
            'user_email'   => $email,
            'first_name'   => $firstname, 
            'last_name'    => $lastname,
            'role'         => 'author',
        ];

        return wp_insert_user($userData);
    }

    private function updateAcfUser(string $sujetTitle, int $correctionAttachmentId) {

    }

    private function sendAdminEmail(string $lastname, string $firstname, string $email, string $titreSujet, int $correctionAttachmentId) {

    }

}
new AcfFormHook();