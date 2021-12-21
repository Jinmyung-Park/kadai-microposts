<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFavoriteController extends Controller
{
    public function store($id)
    {
        \Auth::user() -> addFavorite($id);
        return back();
    }
    
    public function destroy($id)
    {
        \Auth::user() -> deleteFavorite($id);
        return back();
    }
    
}