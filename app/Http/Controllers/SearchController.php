<?php

namespace App\Http\Controllers;

use App\EDSAPI;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $discovery = new EDSAPI(
            env('EDS_USERID'),
            env('EDS_PASSWORD'),
            env('EDS_PROFILE'),
            env('EDS_ORG')
        );

        $result = $discovery->search($request);
        $items = $result['items'];
        $source_types = $result['source_types'];
        $subjects = $result['subjects'];

        return view('search.results', compact('items', 'source_types', 'subjects'));

    }

}
