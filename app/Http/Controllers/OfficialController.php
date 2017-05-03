<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Official as Official;
use Illuminate\Support\Facades\Storage;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use App\Lgu as Lgu;
class OfficialController extends Controller
{
	public function fetchReportByProvince(){
		$resp = Official::whereIn( 'POSITION_NAME' ,  
			[
				'SANGGUNIANG PANLALAWIGAN MEMBER', 
				'PROVINCIAL GOVERNOR',
				'PROVINCIAL VICE-GOVERNOR']
		)->get();
		return response()->json($resp);
	}
	public function fetchAllLgus(){
		$lgus = Lgu::all();
		return response()->json($lgus);
	}
	public function fetchByProvince(Request $request){
		$lgu = $request->input('lgu');
		$province = $request->input('province');
		$resp = Official::where('PROVINCE', $province['name'])->get();
		return response()->json($resp);
	}
	public function fetchByCityMun(Request $request){
		$lgu = $request->input('lgu');
		$province = $request->input('province');
		// print_r($lgu);
		// print_r($province);
		$resp = Official::where('CITYMUN', $lgu['name'])->where('PROVINCE', $province['name'])->get();
		return response()->json($resp);
	}
	public function importExcel(Request $request)
	{
		if(Input::hasFile('import_file')){
			$path = Input::file('import_file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get();
			if(!empty($data) && $data->count()){
				// foreach ($data as $key => $value) {
				// 	$insert[] = ['LAST_NAME' => $value->LAST_NAME, 'description' => $value->description];
				// }
				// if(!empty($insert)){
				// 	DB::table('officials_copy')->insert($insert);
				// 	dd('Insert Record successfully.');
				// }
				// foreach ($data as $key => $value) {
				// 	print_r($value);
				// }
			}
		}
		// return back()
;	}
	public function importFile(Request $request){
		if ($request->hasFile('excel_file')) {
		    $file = $request->file('excel_file');
		    $filename = $file->getClientOriginalName();
		    $path = $file->getRealPath();
		    Storage::putFile('temp', $file);
		}else {
			echo "File was not found!";
		}
	}
	public function fetchAll(){
		$resp = Official::all();
		return response()->json($resp);
	}
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
				$resp = Official::where('PROVINCE', $province)->orderBy('POSITION_NAME','ASC')->get();
			}elseif($citymun != '0' && $province == '0'){
				// citymun only
				$resp = Official::take(100)->get();
			}elseif($citymun != '0' && $province != '0'){
				// citymun & province only
				$resp = Official::where('PROVINCE', $province)
								->orderBy('POSITION_NAME','ASC')->get();
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
				$resp = Official::where('PROVINCE', $province)->orderBy('POSITION_NAME','ASC')->get();
			}elseif($citymun != '0' && $province == '0'){
				// citymun only
				$resp = Official::where('CITYMUN', $citymun)->orderBy('POSITION_NAME','ASC')->get();
			}elseif($citymun != '0' && $province != '0'){
				// citymun & province only
				$resp = Official::where('CITYMUN', $citymun)
								->orderBy('POSITION_NAME','ASC')->get();
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
    	$data = Official::take(100)->orderBy('POSITION_NAME','ASC')->get();
    	return response()->json($data);
    }
}
