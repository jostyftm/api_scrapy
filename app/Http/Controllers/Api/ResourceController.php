<?php

namespace App\Http\Controllers\Api;

use App\Helper;
use Illuminate\Http\Request;
use App\Models\Resource;
use App\Utils\Pagination;

class ResourceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resources = collect();
        $perPage = $request->limit ?? 10;
        $currentPage = $request->page ?? 1;

        $q = $request->q;
        $keyWords = Helper::getKeyWords($q);
        $resources = Resource::orWhereTitle($keyWords)
        ->orWhereDescription($keyWords)
        ->orderBy('id', 'desc')
        ->get();

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

    /**
     * 
     */
    public function search(Request $request)
    {
        $resources = Resource::with(['resourceType', 'search.website'])
        ->orderBy('id', 'desc')
        ->get();


        return $this->successResponse($resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
