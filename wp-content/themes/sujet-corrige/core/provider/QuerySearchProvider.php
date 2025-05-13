<?php

class QuerySearchProvider
{
    public function search(array $params):array
    {
        $yearTermId = null;
        if(!empty($params['year-tax-name'])) {
            $yearTermId = $params['year-tax-name'];
        }

        $typeEx1TermId = null;
        if(!empty($params['type-exe-1-tax-name'])) {
            $typeEx1TermId = $params['type-exe-1-tax-name'];
        }

        $typeEx2TermId = null;
        if(!empty($params['type-exe-2-tax-name'])) {
            $typeEx2TermId = $params['type-exe-2-tax-name'];
        }

        $titleSearch = null;
        if(!empty($params['search-name'])) {
            $titleSearch = $params['search-name'];
        }

        $query = [
            'post_type'      => 'sujet',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true,
        ];

        if($titleSearch) {
            $query['s'] = $titleSearch;
        }

        if($yearTermId) {
            $query['tax_query'][] = [
                'taxonomy' => 'year-tax',
                'field'    => 'term_id',
                'terms'    => $yearTermId
            ];
        }

        if($typeEx1TermId) {
            $query['tax_query'][] = [
                'taxonomy' => 'type-exe-1-tax',
                'field'    => 'term_id',
                'terms'    => $typeEx1TermId
            ];
        }

        if($typeEx2TermId) {
            $query['tax_query'][] = [
                'taxonomy' => 'type-exe-2-tax',
                'field'    => 'term_id',
                'terms'    => $typeEx2TermId
            ];
        }

        return get_posts($query);
    }
}