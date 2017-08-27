<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\ReservationItems;
use App\ItemType;
use App\ItemProfile;
use App\Inventory;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ReservationItemsAjaxController extends Controller {

	public function getAllReservationItemList()
	{
		if(Request::ajax())
		{
			$reservationitems = ReservationItems::leftJoin('inventory','inventory.id','=','reservationitems.inventory_id')->leftJoin('itemtype','itemtype.id','=','reservationitems.itemtype_id')->select('reservationitems.id as id','itemtype.name as name','inventory.model as model','inventory.brand as brand','reservationitems.included as included','reservationitems.excluded as excluded','reservationitems.status as status')->get();
			return json_encode(['data'=>$reservationitems]);
		}
	}

	public function updateReservationItemListStatus($id)
	{
		$reservationitems = ReservationItems::find($id);
		if(count($reservationitems) > 0)
		{
			($reservationitems->status == 'Disabled')  ? $reservationitems->status = 'Enabled' : $reservationitems->status = 'Disabled';
			$reservationitems->save();
			return json_encode('success');
		}
	}

	public function getAllReservationItemType()
	{
		$reservationitems = ReservationItems::leftJoin('itemtype','reservationitems.itemtype_id','=','itemtype.id')
													->select('itemtype.name as name')->get();
		return json_encode($reservationitems);
	}

	public function getAllReservationItemBrand()
	{
		$itemtype = $this->sanitizeString(Input::get('itemtype'));
		$itemtype = Itemtype::where('name',$itemtype)->select('id')->first();
		if(count($itemtype) > 0)
		{
			$reservationitems = Inventory::where('itemtype_id',$itemtype->id)->select('brand')->get();
			return json_encode($reservationitems);
		}
	}

	public function getAllReservationItemModel()
	{
		$brand = $this->sanitizeString(Input::get('brand'));
		$model = Inventory::where('brand',$brand)->select('model')->get();
		return json_encode($model);
	}

	public function getAllReservationItemPropertyNumber()
	{
		$propertynumber = $this->sanitizeString(Input::get('propertynumber'));
		$itemtype = $this->sanitizeString(Input::get('itemtype'));
		$itemtype = ItemType::where('name',$itemtype)
								->select('id')
								->first();
		if(count($itemtype) > 0)
		{
			$brand = $this->sanitizeString(Input::get('brand'));
			$model = $this->sanitizeString(Input::get('model'));
			$inventory = Inventory::where('brand',$brand)
									->where('model',$model)
									->first();
			if(count($inventory) > 0){
				$propertynumber = ItemProfile::where('inventory_id',$inventory->id)
												->whereNotIn('propertynumber',explode(',',$propertynumber))
												->select('propertynumber')
												->get();
				return json_encode($propertynumber);
			}
		}
	}

}
