<?php

class QueryAllTaxonomyProvider
{
    public function get($taxonomyName): array
    {
        return get_terms([
            'taxonomy'   => $taxonomyName,
            'hide_empty' => false,
        ]);
    }
}