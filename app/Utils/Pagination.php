<?php

namespace App\Utils;

use Illuminate\Pagination\LengthAwarePaginator;

class Pagination extends LengthAwarePaginator
{
    public function __construct($items, $page, $limit, array $options = [])
    {

        $total = count($items);
        $perPage = $limit ?? 10;
        $currentPage = $page ?? 1;
        
        $items = $items->toArray();
        $startPoint = ($currentPage * $perPage) - $perPage;
        $items = array_slice($items, $startPoint, $perPage, false);

        parent::__construct($items, $total, $perPage, $currentPage, $options);   
    }
}