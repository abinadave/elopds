<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Official as Official;
use DB;
class ProvinceController extends Controller
{
    public function fetchUnique(){
    	$resp = DB::table('officials')->select(DB::raw('distinct PROVINCE'))->orderBy('PROVINCE', 'ASC')->get();
    	return response()->json($resp);
    }
}
