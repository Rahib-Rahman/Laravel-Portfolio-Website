<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoController extends Controller
{
    function PhotoIndex(){
        return view('Photo');
    }

    function PhotoUpload(Request $request){
        $photoPath = $request->file('photo')->store('public');
        return $photoPath;
    }
}
