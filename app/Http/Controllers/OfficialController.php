<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Official as Official;
use DB;
class OfficialController extends Controller
{
	public function update(Request $request){
		$value = $request->input('value');
		$id = $request->input('id');
		$key = $request->input('key');
		$model = Official::findOrFail($id);
		$model[$key] = $value;
		$rs = $model->save();
		return response()->json([
			'updated' => $rs
		]);
	}
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
                            ->orWhere('CITYMUN', 'LIKE', '%' . $search .'%')
                            ->orWhere('AFFIlIATE', 'LIKE', '%' . $search .'%')
                            ->orWhere('POSITION_NAME', 'LIKE', '%' . $search .'%')
                            ->orWhere('OTHER_AFFILIATION', 'LIKE', '%' . $search .'%')
                            ->orWhere('OTHER_AFFILIATION', 'LIKE', '%' . $search .'%')
                            ->orWhere('POSITION_NAME', 'LIKE', '%' . $search .'%')
                            ->orWhere('RANK', 'LIKE', '%' . $search .'%')
                            ->orWhere('LAST_NAME', 'LIKE', '%' . $search .'%')
                            ->orWhere('FIRST_NAME', 'LIKE', '%' . $search .'%')
                            ->orWhere('MIDDLE_NAME', 'LIKE', '%' . $search .'%')
                            ->orWhere('SUFFIX', 'LIKE', '%' . $search .'%')
                            ->orWhere('EDUCATION', 'LIKE', '%' . $search .'%')
                            ->orWhere('BIRTH_DATE', 'LIKE', '%' . $search .'%')
                            ->orWhere('SEX', 'LIKE', '%' . $search .'%')
                            ->orWhere('OFFICE_ADDRESS', 'LIKE', '%' . $search .'%')
                            ->orWhere('CONTACT', 'LIKE', '%' . $search .'%')
                            ->orWhere('FAX', 'LIKE', '%' . $search .'%')
                            ->orWhere('CELLPHONE', 'LIKE', '%' . $search .'%')
                            ->orWhere('EMAIL', 'LIKE', '%' . $search .'%')
                            ->orWhere('CIVIL_STATUS', 'LIKE', '%' . $search .'%')
                            ->orWhere('RELIGION', 'LIKE', '%' . $search .'%')
                            ->orWhere('BASIS_OF_ASSUMPTION', 'LIKE', '%' . $search .'%')
                            ->orWhere('SERVICE_RECORD', 'LIKE', '%' . $search .'%')
                            ->orWhere('EDUCATIONAL_ATTAINMENT', 'LIKE', '%' . $search .'%')
                            ->orWhere('MEMBERSHIP_IN_ORGANIZATION', 'LIKE', '%' . $search .'%')
                            ->orWhere('ACHIEVEMENT_RECORD', 'LIKE', '%' . $search .'%');
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
    	$data = Official::take(100)->orderBy('LAST_NAME','ASC')->get();
    	return response()->json($data);
    }
}
