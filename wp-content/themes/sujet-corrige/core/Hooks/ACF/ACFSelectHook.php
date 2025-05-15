<?php

class ACFSelectHook {

    public function __construct() {
        add_filter('acf/load_field/name=sujet', [$this, 'setSujet']);
    }

    public function setSujet($field) {
        $sujets = get_posts([
            'post_type'      => 'sujet',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        foreach($sujets as $sujet) {
            $field['choices'][$sujet->ID] = $sujet->post_title;
        }

        return $field;
    }

}
new ACFSelectHook();