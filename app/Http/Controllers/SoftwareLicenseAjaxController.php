<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class SoftwareLicenseAjaxController extends Controller {

	public function getAllSoftwareLicenseKey($id)
	{
		if(Request::ajax())
		{
			$id = $this->sanitizeString($id);
			$software = Software::find($id);
			$softwarelicense = Softwarelicense::where('id',$software->id)
																->select('id','key')->get();
			return json_encode($softwarelicense);
		}
	}

	public function getSoftwareLicense($id)
	{
		if(Request::ajax())
		{
			$id = $this->sanitizeString($id);
			$licensekey = $this->sanitizeString(Input::get('term'));
			return json_encode(
				SoftwareLicense::where('software_id','=',$id)
								->where('key','like','%'.$licensekey.'%')
								->lists('key')
			);
		}

	}

}
