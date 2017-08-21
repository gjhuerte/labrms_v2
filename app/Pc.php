<?php

namespace App;

use Auth;
use DB;
use App\ItemProfile;
use App\Pc;
use App\Ticket;
use Illuminate\Database\Eloquent\Model;

class Pc extends \Eloquent{
	//Database driver
	/*
		1 - Eloquent (MVC Driven)
		2 - DB (Directly query to SQL database, no model required)
	*/
	//The table in the database used by the model.
	protected $table = 'pc';
	protected $primaryKey = 'id';
	public $timestamps = false;
	public $fillable = ['oskey','mouse','keyboard_id','systemunit_id','monitor_id','avr_id'];
	//Validation rules!
	public static $rules = array(
		'Operating System Key' => 'required|min:2|max:50|unique:pc,oskey',
		'avr' => 'exists:itemprofile,propertynumber',
		'Monitor' => 'exists:itemprofile,propertynumber',
		'System Unit' => 'required|exists:itemprofile,propertynumber',
		'Keyboard' => 'exists:itemprofile,propertynumber',
		'Mouse' => 'exists:Supply,brand'
	);

	public static $updateRules = array(
		'Operating System Key' => 'min:2|max:50',
	);

	public function roominventory()
	{
		return $this->hasOne('App\RoomInventory','room_id','systemunit_id');
	}

	public function systemunit()
	{
		return $this->belongsTo('App\ItemProfile','systemunit_id','id');
	}

	public function monitor()
	{
		return $this->belongsTo('App\ItemProfile','monitor_id','id');
	}
	public function keyboard()
	{
		return $this->belongsTo('App\ItemProfile','keyboard_id','id');
	}

	public function avr()
	{
		return $this->belongsTo('App\ItemProfile','avr_id','id');
	}

	public function software()
	{
		return $this->belongsToMany('App\Software','pc_software','pc_id','software_id')
					->withPivot('softwarelicense_id')
					->withTimestamps();
	}

	public function ticket()
	{
		return $this->belongsToMany('App\Ticket','pc_ticket','pc_id','ticket_id');
	}

    public static function separateArray($value)
    {
        return explode(',', $value);
    }

    public static function assemble($systemunit,$monitor,$avr,$keyboard,$oskey,$mouse)
    {
		$_systemunit = ItemProfile::propertyNumber($systemunit)->first();
		$_monitor = ItemProfile::propertyNumber($monitor)->first();
		$_avr = ItemProfile::propertyNumber($avr)->first();
		$_keyboard = ItemProfile::propertyNumber($keyboard)->first();

		/*
		*
		*	Get the id of the object
		*	Assign to the variable
		*
		*/
		$systemunit = Pc::getID($_systemunit);
		$monitor =Pc::getID($_monitor);
		$avr = Pc::getID($_avr);
		$keyboard = Pc::getID($_keyboard);

		/*
		*
		*	Transaction used to prevent error on saving
		*
		*/
		DB::transaction(function() use ($mouse,$systemunit,$monitor,$avr,$keyboard,$oskey,$mouse,$_systemunit,$_monitor,$_avr,$_keyboard){
			Supply::releaseForWorkstation($mouse);
			/*
			*
			*	Create a new pc record
			*	All validation must occur before this point
			*	No more validation at this point
			*
			*/
			$pc = new Pc;
			$pc->systemunit_id = $systemunit;
			$pc->monitor_id = $monitor;
			$pc->avr_id = $avr;
			$pc->keyboard_id = $keyboard;
			$pc->oskey = $oskey;
			$pc->mouse = $mouse;
			$pc->save();

			/*
			*
			*	Create a workstation ticket
			*	The current person who assembles the workstation will receive the ticket
			*	Details are autogenerated by the system
			*
			*/
			$ticketname = 'Workstation Assembly';
			$details = "Workstation assembled with the following propertynumber: $_systemunit->propertynumber for System Unit, $_monitor->propertynumber for Monitor, $_keyboard->propertynumber for Keyboard, $_avr->propertynumber for AVR and a $mouse as mouse brand";
			$staffassigned = Auth::user()->id;
			$author = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
			Ticket::generatePcTicket($pc->id,'Receive',$ticketname,$details,$author,$staffassigned,null,'Closed');
		});
    }

    /**
    *
    *	@param $object accepts object collection
    *	get the id from object
    *	returns null if no id
    *
    */
    public static function getID($object)
    {
    	try
    	{
    		$object = $object->id;
    		return $object;
    	} 
    	catch (Exception $e) 
    	{
    		return null;
    	}
    }

    /**
    *
    *	@param $propertynumber of item
    *	@return null or pc details
    *
    */
    public static function isPc($propertynumber)
    {
		    
		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if propertynumber exists
		|
		|--------------------------------------------------------------------------
		|
		*/
    	$propertynumber = ItemProfile::propertyNumber($propertynumber)->first();

    	if(count($propertynumber) > 0) 
    	{
		    
			/*
			|--------------------------------------------------------------------------
			|
			| 	get property number id
			|
			|--------------------------------------------------------------------------
			|
			*/
    		$id = Pc::getID($propertynumber);
		    
			/*
			|--------------------------------------------------------------------------
			|
			| 	query if id is in pc
			|
			|--------------------------------------------------------------------------
			|
			*/
	    	$pc = Pc::where('systemunit_id', '=', $id)
	    		->orWhere('monitor_id','=',$id)
	    		->orWhere('avr_id','=',$id)
	    		->orWhere('keyboard_id','=',$id)
	    		->first();
		    
			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if pc exists 
			|	If existing return id
			|	return null if not
			|
			|--------------------------------------------------------------------------
			|
			*/
	    	if(count($pc) > 0 )
	    	{
	    		return $pc;
	    	}
	    	else
	    	{
	    		return null;
	    	}
    	} 
    	else 
    	{
		    
			/*
			|--------------------------------------------------------------------------
			|
			| 	If it doesnt exists return null
			|
			|--------------------------------------------------------------------------
			|
			*/
    		return null;
    	}
    }

    /**
    *
    *	@param $id accepts pc id
    *	@param $status accepts status to set 'for repair' 'working' 'condemned'
    *	@return pc information
    *
    */
    public function setItemStatus($id,$status)
    {
    	$pc = Pc::find($id);
 
		/*
		|--------------------------------------------------------------------------
		|
		| 	System Unit
		|
		|--------------------------------------------------------------------------
		|
		*/
    	try
    	{
    		ItemProfile::setItemStatus($pc->systemunit_id,$status);
    	}catch(Exception $e){}

 		/*
		|--------------------------------------------------------------------------
		|
		| 	Monitor
		|
		|--------------------------------------------------------------------------
		|
		*/
    	try
    	{
    		ItemProfile::setItemStatus($pc->monitor_id,$status);
    	}catch(Exception $e){}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Keyboard
		|
		|--------------------------------------------------------------------------
		|
		*/
    	try
    	{
    		ItemProfile::setItemStatus($pc->keyboard_id,$status);
    	}catch(Exception $e){}

		/*
		|--------------------------------------------------------------------------
		|
		| 	AVR
		|
		|--------------------------------------------------------------------------
		|
		*/
    	try
    	{
    		ItemProfile::setItemStatus($pc->avr_id,$status);
    	}catch(Exception $e){}

		/*
		|--------------------------------------------------------------------------
		|
		| 	PC Information
		|
		|--------------------------------------------------------------------------
		|
		*/
    	return $pc;
    }

    /**
    *
    *	@param $pc is a comma separated id of each pc
    *	@param room accepts room name
    *
    */
    public static function setPcLocation($pc,$room)
    {

		$pc = Pc::find($pc);
		ItemProfile::setLocation($pc->systemunit,$room);
		ItemProfile::setLocation($pc->monitor,$room);
		ItemProfile::setLocation($pc->avr,$room);
		ItemProfile::setLocation($pc->keyboard,$room);

		/*
		*
		*	create a transfer ticket
		*
		*/
		$details = "Pc location has been set to $room";
		$staffassigned = Auth::user()->id;
		$author = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
		Ticket::generatePcTicket(
					$pc->id,
					'Transfer',
					'Set Item Location',
					$details,
					$author,
					$staffassigned,
					null,
					'Closed'
				);
    }
}
