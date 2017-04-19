<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Official as Official;

class OfficialController extends Controller
{
    public function fetch(Request $request){
    	$skip = $request->input('skip');
    	$take = $request->input('take');
    	$data = Official::take(100)->get();
    	return response()->json($data);
    }
}
