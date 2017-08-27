<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function(){

	Route::resource('dashboard','DashboardController',array('only'=>array('index')));

	Route::prefix('reservation')->group(function(){

		Route::get('create',[
			'as' => 'reservation.create',
			'uses' => 'ReservationController@create'
		]);

		Route::post('/',[
			'as' => 'reservation.store',
			'uses' => 'ReservationController@store'
		]);
	});

	Route::get('profile',['as'=>'profile.index','uses'=>'SessionsController@show']);

	Route::get('settings',['as'=>'settings.edit','uses'=>'SessionsController@edit']);

	Route::post('settings',['as'=>'settings.update','uses'=>'SessionsController@update']);

	Route::get('logout','SessionsController@destroy');

	Route::get('get/purpose/all',[
		'route'=>'purpose.all',
		'uses'=>'PurposeController@getAllPurpose'
	]);

});

/*
|--------------------------------------------------------------------------
| Ajax Request by all users
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth'])->group(function(){


});

/*
|--------------------------------------------------------------------------
| Administrator's access only
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth','laboratorystaff'])->group(function(){

	Route::prefix('reservation')->group(function(){
		Route::get('/',[
			'as' => 'reservation.index',
			'uses' => 'ReservationController@index'
		]);

		Route::post('{reservation}/approve',[
			'as' => 'reservation.approve',
			'uses' => 'ReservationController@approve'
		]);
	});

	/*
	|--------------------------------------------------------------------------
	| Account Maintenance
	|--------------------------------------------------------------------------
	|
	*/
	//Display all accounts
	Route::resource('account','AccountsController');

	//display all deleted accounts
	//for account restoration
	Route::get('account/view/deleted',[
			'as'=>'account.retrieveDeleted',
			'uses'=>'AccountsController@retrieveDeleted'
		]);

	//restore account
	Route::delete('account/view/deleted/{id}',[
			'as'=>'account.restore',
			'uses'=>'AccountsController@restore'
		]);

	Route::put('account/access/update',[
			'as' => 'account.accesslevel.update',
			'uses' => 'AccountsController@changeAccessLevel'
 		]);

});


/*
|--------------------------------------------------------------------------
| Staff's access only
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth','laboratorystaff'])->group(function () {


	/*
	|--------------------------------------------------------------------------
	| Room Category
	|--------------------------------------------------------------------------
	|
	*/
	Route::prefix('room')->group(function(){
		Route::get('category',[
			'as' => 'room.category.index',
			'uses' => 'RoomCategoryController@index'
		]);
		Route::post('category',[
			'as' => 'room.category.store',
			'uses' => 'RoomCategoryController@store'
		]);
		Route::put('category/{room}',[
			'as' => 'room.category.update',
			'uses' => 'RoomCategoryController@update'
		]);
		Route::delete('category/{room}',[
			'as' => 'room.category.destroy',
			'uses' => 'RoomCategoryController@destroy'
		]);
	});
	/*
	|--------------------------------------------------------------------------
	| Software Types
	|--------------------------------------------------------------------------
	|
	*/
	Route::prefix('software')->group(function(){
		Route::get('type',[
			'as' => 'software.type.index',
			'uses' => 'SoftwareTypesController@index'
		]);
		Route::post('type',[
			'as' => 'software.type.store',
			'uses' => 'SoftwareTypesController@store'
		]);
		Route::put('type/{software}',[
			'as' => 'software.type.update',
			'uses' => 'SoftwareTypesController@update'
		]);
		Route::delete('type/{software}',[
			'as' => 'software.type.destroy',
			'uses' => 'SoftwareTypesController@destroy'
		]);
	});

	/*
	|--------------------------------------------------------------------------
	| Room Maintenance
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('room','RoomsController');

	/*
	|--------------------------------------------------------------------------
	| display all room for update
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('room/view/update',[
			'as' => 'room.view.update',
			'uses' => 'RoomsController@updateView'
	]);

	/*
	|--------------------------------------------------------------------------
	| display all rooms for delete
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('room/view/delete',[
			'as' => 'room.view.delete',
			'uses' => 'RoomsController@deleteView'
	]);

	/*
	|--------------------------------------------------------------------------
	| display deleted rooms
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('room/view/restore',[
		'as'=>'room.view.restore',
		'uses'=>'RoomsController@restoreView'
	]);

	/*
	|--------------------------------------------------------------------------
	| restore
	|--------------------------------------------------------------------------
	|
	*/
	Route::put('room/view/restore/{id}',[
		'as'=>'room.restore',
		'uses'=>'RoomsController@restore'
	]);

	/*
	|--------------------------------------------------------------------------
	| Inventory Maintenance
	|--------------------------------------------------------------------------
	|
	*/
	Route::prefix('inventory')->group(function(){
		Route::get('item',[
			'as' => 'inventory.item.index',
			'uses' => 'ItemInventoryController@index'
		]);
		Route::get('item/create',[
			'as' => 'inventory.item.create',
			'uses' => 'ItemInventoryController@create'
		]);
		Route::post('item',[
			'as' => 'inventory.item.store',
			'uses' => 'ItemInventoryController@store'
		]);
		Route::get('item/{item}',[
			'as' => 'inventory.item.show',
			'uses' => 'ItemInventoryController@show'
		]);
		Route::get('item/{item}/edit',[
			'as' => 'inventory.item.edit',
			'uses' => 'ItemInventoryController@edit'
		]);
		Route::put('item/{item}',[
			'as' => 'inventory.item.update',
			'uses' => 'ItemInventoryController@update'
		]);
		Route::patch('item/{item}',[
			'as' => 'inventory.item.update',
			'uses' => 'ItemInventoryController@update'
		]);
		Route::delete('item/{item}',[
			'as' => 'inventory.item.destroy',
			'uses' => 'ItemInventoryController@destroy'
		]);

		/*
		|--------------------------------------------------------------------------
		| import view function (not yet working)
		|--------------------------------------------------------------------------
		|
		*/
		Route::get('item/view/import',[
			'as'=> 'inventory.item.view.import',
			'uses' => 'ItemInventoryController@importView'
		]);

		/*
		|--------------------------------------------------------------------------
		| import function (not yet working)
		|--------------------------------------------------------------------------
		|
		*/
		Route::post('item/view/import',[
			'as'=> 'inventory.item.import',
			'uses' => 'ItemInventoryController@import'
		]);

		/*
		|--------------------------------------------------------------------------
		| software list
		|--------------------------------------------------------------------------
		|
		*/
		Route::resource('inventory/software','SoftwareInventoryController');
	});

	Route::prefix('item/profile')->group(function(){

		/*
		|--------------------------------------------------------------------------
		| assign to a location
		|--------------------------------------------------------------------------
		|
		*/
		Route::post('assign',[
			'as' => 'item.profile.assign',
			'uses' => 'ItemsController@assign'
		]);

		/**
		|--------------------------------------------------------------------------
		| accepts id
		| history information (ticket)
		|--------------------------------------------------------------------------
		|
		*/
		Route::get('history/{id}','ItemsController@history');
	});

	/*
	|--------------------------------------------------------------------------
	| Ticket Resolve
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/resolve',[
		'as' => 'ticket.resolve',
		'uses' => 'TicketsController@resolve'
	]);

	/*
	|--------------------------------------------------------------------------
	| ticket per workstation
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/workstation/{id}','TicketsController@getPcTicket');

	/*
	|--------------------------------------------------------------------------
	| transfer ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/transfer/{id}',[
		'as' => 'ticket.transfer',
		'uses' => 'TicketsController@transfer'
	]);

	/*
	|--------------------------------------------------------------------------
	| ticket history
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/history/{id}',[
		'as' => 'ticket.history.view',
		'uses' => 'TicketsController@showHistory'
	]);


	/*
	|------------------c--------------------------------------------------------
	| maintenance ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/maintenance',[
		'uses'=>'TicketsController@maintenanceView'
	]);

	/*
	|--------------------------------------------------------------------------
	| maintenance ticket function
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/maintenance',[
		'as'=>'ticket.maintenance',
		'uses'=>'TicketsController@maintenance'
	]);

	/*
	|--------------------------------------------------------------------------
	| Reopen Ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/{id}/reopen','TicketsController@reOpenTicket');

	/*
	|--------------------------------------------------------------------------
	| Ticket list
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('ticket','TicketsController',[
		'except'=>array('show')
	]);

	/*
	|--------------------------------------------------------------------------
	| Reports
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('report','ReportsController@index');
    // ... another resource ...

	/*
	|--------------------------------------------------------------------------
	| Item Profiling
	|--------------------------------------------------------------------------
	|
	*/
	Route::prefix('item')->group(function(){
		Route::get('profile',[
			'as' => 'item.profile.index',
			'uses' => 'ItemsController@index'
		]);
		Route::get('profile/create',[
			'as' => 'item.profile.create',
			'uses' => 'ItemsController@create'
		]);
		Route::post('profile',[
			'as' => 'item.profile.store',
			'uses' => 'ItemsController@store'
		]);
		Route::get('profile/{profile}',[
			'as' => 'item.profile.show',
			'uses' => 'ItemsController@show'
		]);
		Route::get('profile/{profile}/edit',[
			'as' => 'item.profile.edit',
			'uses' => 'ItemsController@edit'
		]);
		Route::put('profile/{profile}',[
			'as' => 'item.profile.update',
			'uses' => 'ItemsController@update'
		]);
		Route::delete('profile/{profile}',[
			'as' => 'item.profile.destroy',
			'uses' => 'ItemsController@destroy'
		]);
	});

	/*
	|--------------------------------------------------------------------------
	| Room Inventory
	|--------------------------------------------------------------------------
	|
	*/
	Route::prefix('inventory')->group(function(){

		/*
		|--------------------------------------------------------------------------
		| room inventory assignment restful route
		|--------------------------------------------------------------------------
		|
		*/
		Route::get('room/assign',[
			'as' => 'inventory.room.assign',
			'uses' => 'RoomInventoryAssignmentController@index'
		]);

		/*
		|--------------------------------------------------------------------------
		| room inventory profile restful route
		|--------------------------------------------------------------------------
		|
		*/
		// Route::resource('inventory/room/profile','RoomInventoryProfileController');

		/*
		|--------------------------------------------------------------------------
		| room inventory restful route
		|--------------------------------------------------------------------------
		|
		*/
		Route::get('room',[
			'as' => 'inventory.room.index',
			'uses' => 'RoomInventoryController@index'
		]);
		Route::post('room',[
			'as' => 'inventory.room.store',
			'uses' => 'RoomInventoryController@store'
		]);
	});

	/*
	|--------------------------------------------------------------------------
	| Software
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('software','SoftwareController',[
			'except'=>array('show')
	]);

	/*
	|--------------------------------------------------------------------------
	| software license restful route
	|--------------------------------------------------------------------------
	|
	*/
	Route::prefix('software')->group(function(){
		Route::get('license',[
			'as' => 'software.license.index',
			'uses' => 'SoftwareLicenseController@index'
		]);
		Route::get('license/create',[
			'as' => 'software.license.create',
			'uses' => 'SoftwareLicenseController@createx'
		]);
		Route::post('license',[
			'as' => 'software.license.store',
			'uses' => 'SoftwareLicenseController@store'
		]);
		Route::get('license/{license}',[
			'as' => 'software.license.show',
			'uses' => 'SoftwareLicenseController@show'
		]);
		Route::get('license/{license}/edit',[
			'as' => 'software.license.edit',
			'uses' => 'SoftwareLicenseController@edit'
		]);
		Route::put('license/{license}',[
			'as' => 'software.license.update',
			'uses' => 'SoftwareLicenseController@update'
		]);
		Route::patch('license/{license}',[
			'as' => 'software.license.update',
			'uses' => 'SoftwareLicenseController@update'
		]);
		Route::delete('license/{license}',[
			'as' => 'software.license.destroy',
			'uses' => 'SoftwareLicenseController@destroy'
		]);
	});

	/*
	|--------------------------------------------------------------------------
	| software restoration table
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('software/view/restore',[
		'as'=>'software.view.restore',
		'uses'=>'SoftwareController@restoreView'
	]);


	/*
	|--------------------------------------------------------------------------
	| display software information
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('software/details/{id}',[
		'as' => 'software.details.view',
		'uses' => 'SoftwareController@show'
	]);

	/*
	|--------------------------------------------------------------------------
	| software restore function
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('software/view/restore/{id}',[
		'as'=>'software.restore',
		'uses'=>'SoftwareController@restore'
	]);

	/*
	|--------------------------------------------------------------------------
	| assign software to a room
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('software/room/assign',[
		'as' => 'software.room.assign',
		'uses' => 'SoftwareController@assignSoftwareToRoom'
	]);

	/*
	|--------------------------------------------------------------------------
	| remove software from a room
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('software/room/remove/{id}/{room}',[
		'as' => 'software.room.remove',
		'uses' => 'SoftwareController@removeSoftwareFromRoom'
	]);

	/*
	|--------------------------------------------------------------------------
	| Workstation deployment
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('workstation/deploy',[
		'as' => 'workstation.deploy',
		'uses' => 'WorkstationController@deploy'
	]);

	/*
	|--------------------------------------------------------------------------
	| transfer workstation
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('workstation/transfer',[
		'as' => 'workstation.transfer',
		'uses' => 'WorkstationController@transfer'
	]);

	/*
	|--------------------------------------------------------------------------
	| workstation restful routing
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('workstation', 'WorkstationController');

	/*
	|--------------------------------------------------------------------------
	| workstation per software
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('workstation/view/software','WorkstationSoftwareController@index');

	/*
	|--------------------------------------------------------------------------
	| assign software to a workstation
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('workstation/software/{id}/assign','WorkstationSoftwareController@create');

	/*
	|--------------------------------------------------------------------------
	| remove software from workstation display
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('workstation/software/{id}/remove','WorkstationSoftwareController@destroyView');

	/*
	|--------------------------------------------------------------------------
	| remove software from workstation function
	|--------------------------------------------------------------------------
	|
	*/
	Route::delete('workstation/software/{id}/remove',[
		'as' => 'workstation.software.destroy',
		'uses' => 'WorkstationSoftwareController@destroy'
	]);

	/*
	|--------------------------------------------------------------------------
	| assign software to a workstation function
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('workstation/software/{id}/assign',[
			'as' => 'workstation.software.assign',
			'uses' => 'WorkstationSoftwareController@store'
	]);

	/*
	|--------------------------------------------------------------------------
	| assign software and license to a workstation
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('workstation/software/{id}/license/update',[
			'as' => 'workstation.software.assign',
			'uses' => 'WorkstationSoftwareController@update'
	]);

	/*
	|--------------------------------------------------------------------------
	| Software
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('item/type','ItemTypesController');

	/*
	|--------------------------------------------------------------------------
	| deleted item type table
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('item/type/view/restore',[
		'as' => 'item.type.view.restore',
		'uses' => 'ItemTypesController@restoreView'
	]);

	/*
	|--------------------------------------------------------------------------
	| restore item type
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('item/type/view/restore/{id}',[
		'as' => 'item.type.restore',
		'uses' => 'ItemTypesController@restore'
	]);

	/*
	|--------------------------------------------------------------------------
	| Maintenance Activities
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('maintenance/activity','MaintenanceActivityController');

	/*
	|--------------------------------------------------------------------------
	| Items for reservation
	|--------------------------------------------------------------------------
	|
	*/
	Route::prefix('reservation/items')->group(function(){
		Route::get('list',[
			'as' => 'reservation.items.list.index',
			'uses' => 'ReservationItemsController@index'
		]);
		Route::get('list/create',[
			'as' => 'reservation.items.list.create',
			'uses' => 'ReservationItemsController@create'
		]);
		Route::post('list',[
			'as' => 'reservation.items.list.store',
			'uses' => 'ReservationItemsController@store'
		]);
		Route::get('list/{list}',[
			'as' => 'reservation.items.list.show',
			'uses' => 'ReservationItemsController@show'
		]);
		Route::get('list/{list}/edit',[
			'as' => 'reservation.items.list.edit',
			'uses' => 'ReservationItemsController@edit'
		]);
		Route::put('list/{list}',[
			'as' => 'reservation.items.list.update',
			'uses' => 'ReservationItemsController@update'
		]);
		Route::delete('list/{list}',[
			'as' => 'reservation.items.list.destroy',
			'uses' => 'ReservationItemsController@destroy'
		]);
	});

	/*
	|--------------------------------------------------------------------------
	| Purpose
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('purpose','PurposeController');

	/*
	|--------------------------------------------------------------------------
	| Supplies
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('supplies','SuppliesController');

	/*
	|--------------------------------------------------------------------------
	| Schedule
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('schedule','LaboratoryScheduleController');

	/*
	|--------------------------------------------------------------------------
	| Event
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('event','SpecialEventController');
});


/*
|--------------------------------------------------------------------------
| Ajax Request made by all user only
|--------------------------------------------------------------------------
|
*/

Route::middleware(['auth','laboratorystaff'])->group(function () {

	/*
	|--------------------------------------------------------------------------
	| get item information
	| returns pc info if linked to pc
	| returns item information if not
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/item/information/{propertynumber}",[
		'as' => 'item.information',
		'uses' => 'ItemsAjaxController@getItemInformation'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all item types
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/type/all',[
		'as' => 'item.type.all',
		'uses'=>'ItemTypesAjaxController@getAllItemTypes'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all equipment item type
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/inventory/item/type/equipment',[
		'as' => 'inventory.item.type.equipment',
		'uses'=>'ItemTypesAjaxController@getItemTypesForEquipmentInventory'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all supply
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/inventory/item/type/supply',[
		'as' => 'inventory.item.type.supply',
		'uses'=>'ItemTypesAjaxController@getItemTypesForSuppliesInventory'
	]);

	/*
	|--------------------------------------------------------------------------
	| change user password
	| used in Change Password -> account update
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('account/password/reset','AccountsController@resetPassword');
	
	/*
	|--------------------------------------------------------------------------
	| activate account
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('account/activate/{id}','AccountsController@activateAccount');

	/*
	|--------------------------------------------------------------------------
	| returns a list of A.R. based on 'id' given
	| used in Select Box -> Item Profile
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/receipt/all',[
		'as'=>'item.profile.receipt.all',
		'uses'=>'ItemsAjaxController@getAllReceipt'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all license types for the software
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/license/all',[
		'as'=>'software.license.all',
		'uses'=>'SoftwareAjaxController@getAllLicenseTypes'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all license for the software
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/{id}/license/all',[
		'uses'=>'SoftwareLicenseAjaxController@getSoftwareLicense'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all brands
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/brand/all',[
		'as' => 'inventory.item.brand.all',
		'uses' => 'ItemsAjaxController@getItemBrands'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all models
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/model/all',[
		'as' => 'inventory.item.model.all',
		'uses' => 'ItemsAjaxController@getItemModels'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/propertynumber/all',[
		'as' => 'item.profile.propertynumber.all',
		'uses' => 'ItemsAjaxController@getAllPropertyNumber'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all property number on server
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/propertynumber/server',[
		'as' => 'inventory.item.propertynumber.server',
		'uses' => 'ItemsAjaxController@getPropertyNumberOnServer'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all unassigned system unit
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/systemunit/unassigned',[
		'as' => 'item.profile.systemunit.unassigned',
		'uses' => 'ItemsAjaxController@getUnassignedSystemUnit'
	]);


	/*
	|--------------------------------------------------------------------------
	| return all item brands on inventory
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/inventory/item/brand','ItemInventoryController@getBrands');

	/*
	|--------------------------------------------------------------------------
	| return all models on inventory
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/inventory/item/model','ItemInventoryController@getModels');


	/*
	|--------------------------------------------------------------------------
	| reutrn all system unit property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/systemunit/propertynumber','ItemsAjaxController@getSystemUnitList');


	/*
	|--------------------------------------------------------------------------
	| return all monitor property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/monitor/propertynumber','ItemsAjaxController@getMonitorList');


	/*
	|--------------------------------------------------------------------------
	| return all avr property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/avr/propertynumber','ItemsAjaxController@getAVRList');


	/*
	|--------------------------------------------------------------------------
	| return all keyboard property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/item/profile/keyboard/propertynumber",'ItemsAjaxController@getKeyboardList');

	/*
	|--------------------------------------------------------------------------
	| return all mouse brands
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/supply/mouse/brand",'SupplyAjaxController@getMouseBrandList');

	/*
	|--------------------------------------------------------------------------
	| return all supply brands
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/supply/brand",'SuppliesController@getBrandList');

	/*
	|--------------------------------------------------------------------------
	| return supply item type base on brand
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/supply/{itemtype}/{brand}","SuppliesController@getSupplyInformation");

	/*
	|--------------------------------------------------------------------------
	| return unassigned monitor
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/monitor/unassigned',[
		'as' => 'item.profile.monitor.unassigned',
		'uses' => 'ItemsAjaxController@getUnassignedMonitor'
	]);

	/*
	|--------------------------------------------------------------------------
	| return unassigned avr
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/avr/unassigned',[
		'as' => 'item.profile.avr.unassigned',
		'uses' => 'ItemsAjaxController@getUnassignedAVR'
	]);

	/*
	|--------------------------------------------------------------------------
	| return unassigned keyboard
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/keyboard/unassigned',[
		'as' => 'item.profile.keyboard.unassigned',
		'uses' => 'ItemsAjaxController@getUnassignedKeyboard'
	]);

	/*
	|--------------------------------------------------------------------------
	| check if existing
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/{itemtype}/{brand}/{model}',[
		'as' => 'item.profile.checkifexisting',
		'uses' => 'ItemsAjaxController@checkifexisting'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all undeployed workstation
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/workstation/tableform/undeployed',[
		'as' => 'workstation.tableform.undeployed',
		'uses' => 'WorkstationAjaxController@getUndeployedWorkstationInTableForm'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all workstation in table
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/workstation/tableform/all/location',[
		'as' => 'workstation.tableform.all.location',
		'uses' => 'WorkstationAjaxController@getAllWorkstationInTableFormWithLocation'
	]);


	/*
	|--------------------------------------------------------------------------
	| get all maintenance activities
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/maintenance/activity/all',[
		'as' => 'maintenance.activity.all',
		'uses' => 'MaintenanceActivityAjaxController@getAllEquipmentSupport'
	]);


	/*
	|--------------------------------------------------------------------------
	| get all ticket types
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/ticket/type/all',[
		'as' => 'ticket.type.all',
		'uses' => 'TicketTypeAjaxController@getAllTicketTypes'
	]);

	/*
	|--------------------------------------------------------------------------
	| get ticket history
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/ticket/history/{id}',[
		'as' => 'ticket.history',
		'uses' => 'TicketsController@showHistory'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all preventive maintenance
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/maintenance/activity/preventive',[
		'as' => 'ticket.type.preventive',
		'uses' => 'MaintenanceActivityAjaxController@getPreventiveEquipmentSupport'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all corrective maintenance action
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/maintenance/activity/corrective',[
		'as' => 'ticket.type.corrective',
		'uses' => 'MaintenanceActivityAjaxController@getCorrectiveEquipmentSupport'
	]);

	/*
	|--------------------------------------------------------------------------
	| get room inventory details
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/room/inventory/details/{id}',[
		'as' => 'room.inventory.profile',
		'uses' => 'RoomInventoryProfileController@getItemsAssigned'
	]);

	/*
	|--------------------------------------------------------------------------
	| get room name from id
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/room/name/{id}',[
		'as' => 'room.name',
		'uses' => 'RoomsAjaxController@getRoomName'

	]);

	/*
	|--------------------------------------------------------------------------
	| get all software installed on a workstation
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/installed/{id}',[
		'as' => 'workstation.pc.software',
		'uses' => 'WorkstationSoftwareAjaxController@getSoftwareInstalled'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all reservation item list
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/reservation/items/list/all',[
		'as' => 'reservation.items.list.all',
		'uses' => 'ReservationItemsAjaxController@getAllReservationItemList'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all item types for reservation
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/reservation/item/type/all',[
		'as' => 'reservation.item.type.all',
		'uses' => 'ReservationItemsAjaxController@getAllReservationItemType'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all reservation items brand
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/reservation/item/brand/all',[
		'as' => 'reservation.item.brand.all',
		'uses' => 'ReservationItemsAjaxController@getAllReservationItemBrand'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all reservation items model
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/reservation/item/model/all',[
		'as' => 'reservation.item.model.all',
		'uses' => 'ReservationItemsAjaxController@getAllReservationItemModel'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all property number of an item
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/reservation/item/propertynumber/all',[
		'as' => 'reservation.item.propertynumber.all',
		'uses' => 'ReservationItemsAjaxController@getAllReservationItemPropertyNumber'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all items for reservation
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('update/reservation/items/list/status/{id}',[
		'as' => 'update.reservation.items.list.status',
		'uses' => 'ReservationItemsAjaxController@updateReservationItemListStatus'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all software names
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/all/name',[
		'as' => 'software.all.name',
		'uses' => 'SoftwareAjaxController@getAllSoftwareName'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all software license key
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/license/{id}/key',[
		'as' => 'software.license.all.key',
		'uses' => 'SoftwareLicenseAjaxController@getAllSoftwareLicenseKey'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all accounts
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/account/all',[
			'as' => 'account.all',
			'uses' => 'AccountsAjaxController@getAllAccount'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all laboratory staff
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/account/laboratory/staff/all',[
			'as' => 'account.laboratory.staff.all',
			'uses' => 'AccountsAjaxController@getAllLaboratoryUsers'
	]);

	/*
	|--------------------------------------------------------------------------
	| get status of certain property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/{propertynumber}/status',[
		'as' => 'item.information.status',
		'uses' => 'ItemsAjaxController@getStatus'
	]);

	/*
	|--------------------------------------------------------------------------
	| get tag information for ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/ticket/tag',[
		'uses' => 'TicketsController@getTagInformation'
	]);
});

/*
|--------------------------------------------------------------------------
| Accessible urls student and faculty only
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth','laboratoryuser'])->group(function(){


	/*
	|------------------c--------------------------------------------------------
	| complaint ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/complaint',[
		'uses'=>'TicketsController@complaintViewForStudentAndFaculty'
	]);

	/*
	|--------------------------------------------------------------------------
	| ticket function
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/complaint',[
		'as'=>'ticket.complaint',
		'uses'=>'TicketsController@complaint'
	]);

});

/*
|--------------------------------------------------------------------------
| page not found
|--------------------------------------------------------------------------
|
*/
Route::get('pagenotfound',['as'=>'pagenotfound','uses'=>'HomeController@pagenotfound']);


/*
|--------------------------------------------------------------------------
| help
|--------------------------------------------------------------------------
|
*/
Route::get('help',[
		'as' => 'help.index',
		'uses' => 'HelpController@index'
]);

/*
|--------------------------------------------------------------------------
| Main Menu
|--------------------------------------------------------------------------
|
*/
Route::middleware(['session_start'])->group(function () {

	/*
	|--------------------------------------------------------------------------
	| homepage
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('/','HomeController@index');

	/*
	|--------------------------------------------------------------------------
	| login
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('login', ['as'=>'login.index','uses'=>'SessionsController@create']);
	Route::post('login', ['as'=>'login.store','uses'=>'SessionsController@store']);

	/*
	|--------------------------------------------------------------------------
	| reset
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('reset',['as'=>'reset','uses'=>'SessionsController@getResetForm']);
	Route::post('reset',['as'=>'reset.store','uses'=>'SessionsController@reset']);
});