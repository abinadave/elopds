<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfficialController extends Controller
{
    public function fetch(Request $request){
    	$skip = $request->input('skip');
    	$take = $request->input('take');
    	echo 'skip: ' . $skip . '<br>';
    	echo 'take: ' . $take;
    }
}
