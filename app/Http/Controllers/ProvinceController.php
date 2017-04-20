<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Official as Official;
use DB;
class ProvinceController extends Controller
{
	public function fetchRelatedCityMun(Request $request){
		$province = $request->input('province');
		$resp = DB::table('officials')->where('PROVINCE', $province)->select(DB::raw('distinct CITYMUN'))->orderBy('CITYMUN', 'ASC')->get();
		return response()->json($resp);
	}
	public function fetchCityMuns(){
		$resp = DB::table('officials')->select(DB::raw('distinct CITYMUN'))->orderBy('CITYMUN', 'ASC')->get();
		return response()->json($resp);
	}
    public function fetchUnique(){
    	$resp = DB::table('officials')->select(DB::raw('distinct PROVINCE'))->orderBy('PROVINCE', 'ASC')->get();
    	return response()->json($resp);
    }
}
