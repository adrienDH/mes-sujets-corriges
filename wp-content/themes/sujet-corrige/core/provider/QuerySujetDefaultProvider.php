<?php

class QuerySujetDefaultProvider
{
    public function getDefault()
    {
        $query = [
            'post_type'      => 'sujet',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true,
        ];
        return get_posts($query);
    }
}