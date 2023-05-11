<?php

namespace App\Http\Controllers\api;

use App\Helper;
use App\Http\Controllers\Api\ApiController;
use App\Models\Resource;
use App\Utils\Pagination;
use Illuminate\Http\Request;

class SearchResourceController extends ApiController
{
    /**
     * 
     */
    public function index(Request $request)
    {
        $resources = collect();
        $perPage = $request->limit ?? 10;
        $currentPage = $request->page ?? 1;

        $q = $request->q;

        $keyWords = Helper::getKeyWords($q);

        $resources = Resource::orWhereTitle($keyWords)
        ->orWhereDescription($keyWords)->get();

        if(isset($request->paginate) && $request->paginate == true){
            return $this->successResponse(
                new Pagination($resources, $currentPage, $perPage, [
                    'path'  =>  $request->url(),
                    'query' =>  $request->query()
                ])
            );  
        }

        return $this->successResponse($resources);
    }
}
