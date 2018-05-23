<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class UserFavoriteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
     /** Favorite get */
    public function store(Request $request, $id)
    {
        \Auth::user()->favorite($id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
     /**  Unfavorite */
    public function destroy($id)
    {
        \Auth::user()->unfavorite($id);
        return redirect()->back();
    }

}
