<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Official as Official;
use DB;
class OfficialController extends Controller
{
	public function searchQueryAll(Request $request){
		$citymun = $request->input('citymun');
		$province = $request->input('province');
		$search = $request->input('search');
		$table = 'officials';
		$columns = [];
		$resp = array();
		if ($search == '') {
			
		}else {
			$resp = DB::table('officials')
            ->where('REGION', 'LIKE', '%' . $search .'%')
            ->orWhere(function ($query) use ($search) {
                return $query->orWhere('PROVINCE', 'LIKE', '%' . $search .'%')
                            ->orWhere('CITYMUN', 'LIKE', '%' . $search .'%');
            })
            ->get();
		}
		return response()->json($resp);
	}
	public function searchQueryProvince(Request $request){
		/* province is priority here */
		$citymun = $request->input('citymun');
		$province = $request->input('province');
		$search = $request->input('search');
		$resp = array();
		if ($search == '') {
			if ($citymun == '0' && $province != '0') {
				// province only
				$resp = Official::where('PROVINCE', $province)->orderBy('LAST_NAME','ASC')->get();
			}elseif($citymun != '0' && $province == '0'){
				// citymun only
				$resp = Official::take(100)->get();
			}elseif($citymun != '0' && $province != '0'){
				// citymun & province only
				$resp = Official::where('PROVINCE', $province)
								->orderBy('LAST_NAME','ASC')->get();
			}elseif($citymun == '0' && $province == '0'){
				$resp = Official::take(100)->get();
			}
		}else {
			echo "search la";
		}
		return response()->json($resp);
	}

	public function searchQueryCItyMun(Request $request){
		/* citymun is priority here */
		$citymun = $request->input('citymun');
		$province = $request->input('province');
		$search = $request->input('search');
		$resp = array();
		if ($search == '') {
			if ($citymun == '0' && $province != '0') {
				// province only
				$resp = Official::where('PROVINCE', $province)->orderBy('LAST_NAME','ASC')->get();
			}elseif($citymun != '0' && $province == '0'){
				// citymun only
				$resp = Official::where('CITYMUN', $citymun)->orderBy('LAST_NAME','ASC')->get();
			}elseif($citymun != '0' && $province != '0'){
				// citymun & province only
				$resp = Official::where('CITYMUN', $citymun)
								->orderBy('LAST_NAME','ASC')->get();
			}elseif($citymun == '0' && $province == '0'){
				$resp = Official::take(100)->get();
			}
		}else {
			echo "search la";
		}
		return response()->json($resp);
	}

    public function fetch(Request $request){
    	$skip = $request->input('skip');
    	$take = $request->input('take');
    	$data = Official::take(100)->get();
    	return response()->json($data);
    }
}
