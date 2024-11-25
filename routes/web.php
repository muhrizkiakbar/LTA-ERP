<?php

use App\Http\Controllers\Approval\ReturnController as ApprovalReturnController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\Master\UomEntryController;
use App\Http\Controllers\Backend\Sync\MixController;
use App\Http\Controllers\Backend\Master\UsersController;
use App\Http\Controllers\Backend\Sync\PngController;
use App\Http\Controllers\Backend\Sync\TaaController;
use App\Http\Controllers\Backend\App\SalesOrderController;
use App\Http\Controllers\Backend\Interfacing\RtdxController;
use App\Http\Controllers\Backend\Master\CompanyApiController;
use App\Http\Controllers\Backend\Master\DiscountProgramController;
use App\Http\Controllers\WhatsappController;

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
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/approval/return/spv/{kd}', [ApprovalReturnController::class, 'spv']);
Route::get('/approval/return/spv_approve/{kd}', [ApprovalReturnController::class, 'spv_approve'])->name('approval.return.spv_approve');
Route::get('/approval/return/spv_reject/{kd}', [ApprovalReturnController::class, 'spv_reject'])->name('approval.return.spv_reject');

Route::get('/approval/return/sbh/{kd}', [ApprovalReturnController::class, 'sbh']);
Route::get('/approval/return/sbh_approve/{kd}', [ApprovalReturnController::class, 'sbh_approve'])->name('approval.return.sbh_approve');
Route::get('/approval/return/sbh_reject/{kd}', [ApprovalReturnController::class, 'sbh_reject'])->name('approval.return.sbh_reject');

Route::middleware(['auth'])->group(function () {
    Route::get('/backend', [BackendController::class, 'index'])->name('backend');
    Route::get('/backend/logout', [BackendController::class, 'logout'])->name('backend.logout');
		Route::get('/backend/history', [BackendController::class, 'history'])->name('backend.history');
    Route::post('/backend/searchSalesByCompany', [BackendController::class, 'searchSalesByCompany'])->name('backend.searchSalesByCompany');

		//Company Api Listing Url
		Route::get('/backend/company_api', [CompanyApiController::class, 'index'])->name('backend.company_api');
		Route::post('/backend/company_api/store', [CompanyApiController::class, 'store'])->name('backend.company_api.store');
		Route::post('/backend/company_api/edit', [CompanyApiController::class, 'edit'])->name('backend.company_api.edit');
		Route::put('/backend/company_api/update/{id}', [CompanyApiController::class, 'update'])->name('backend.company_api.update');
		Route::get('/backend/company_api/delete/{id}', [CompanyApiController::class, 'delete'])->name('backend.company_api.delete');

    Route::get('/backend/user', [UsersController::class, 'index'])->name('backend.user');
    Route::post('/backend/user/store', [UsersController::class, 'store'])->name('backend.user.store');
    Route::post('/backend/user/edit', [UsersController::class, 'edit'])->name('backend.user.edit');
    Route::put('/backend/user/update/{id}', [UsersController::class, 'update'])->name('backend.user.update');
    Route::get('/backend/user/delete/{id}', [UsersController::class, 'delete'])->name('backend.user.delete');
    // Add Sales For Fakturis
    Route::post('/backend/user/sales', [UsersController::class, 'sales'])->name('backend.user.sales');
    Route::post('/backend/user/sales_search', [UsersController::class, 'sales_search'])->name('backend.user.sales_search');
    Route::post('/backend/user/sales_store', [UsersController::class, 'sales_store'])->name('backend.user.sales_store');
    Route::get('/backend/user/sales_delete/{id}', [UsersController::class, 'sales_delete'])->name('backend.user.sales_delete');
    // Add Sales For SPV Collector
    Route::post('/backend/user/sales_collector', [UsersController::class, 'sales_collector'])->name('backend.user.sales_collector');
    Route::post('/backend/user/sales_collector_store', [UsersController::class, 'sales_collector_store'])->name('backend.user.sales_collector_store');
    Route::post('/backend/user/sales_collector_delete', [UsersController::class, 'sales_collector_delete'])->name('backend.user.sales_collector_delete');
    // Create Role Collector
    Route::post('/backend/user/collector', [UsersController::class, 'collector'])->name('backend.user.collector');
    Route::post('/backend/user/collector_update', [UsersController::class, 'collector_update'])->name('backend.user.collector_update');
    // Akun SAP
    Route::post('/backend/user/sap_lta', [UsersController::class, 'sap_lta'])->name('backend.user.sap_lta');
    Route::post('/backend/user/sap_taa', [UsersController::class, 'sap_taa'])->name('backend.user.sap_taa');

    Route::get('backend/sync/mix',[MixController::class, 'index'])->name('backend.sync.mix');
    Route::post('backend/sync/mix_sync',[MixController::class, 'sync'])->name('backend.sync.mix_sync');
    Route::post('backend/sync/mix_detail',[MixController::class, 'detail'])->name('backend.sync.mix_detail');
    Route::post('backend/sync/mix_push',[MixController::class, 'push'])->name('backend.sync.mix_push');
    Route::post('backend/sync/mix_close',[MixController::class, 'close'])->name('backend.sync.mix_close');

    Route::get('backend/sync/png',[PngController::class, 'index'])->name('backend.sync.png');
    Route::post('backend/sync/png_sync',[PngController::class, 'sync'])->name('backend.sync.png_sync');
    Route::post('backend/sync/png_detail',[PngController::class, 'detail'])->name('backend.sync.png_detail');
    Route::post('backend/sync/png_push',[PngController::class, 'push'])->name('backend.sync.png_push');
    Route::post('backend/sync/png_close',[PngController::class, 'close'])->name('backend.sync.png_close');

    Route::get('backend/sync/taa',[TaaController::class, 'index'])->name('backend.sync.taa');
    Route::post('backend/sync/taa_sync',[TaaController::class, 'sync'])->name('backend.sync.taa_sync');
    Route::post('backend/sync/taa_detail',[TaaController::class, 'detail'])->name('backend.sync.taa_detail');
    Route::post('backend/sync/taa_push',[TaaController::class, 'push'])->name('backend.sync.taa_push');
    Route::post('backend/sync/taa_close',[TaaController::class, 'close'])->name('backend.sync.taa_close');

    Route::get('backend/master/uom_entry',[UomEntryController::class, 'index'])->name('backend.master.uom_entry');
    Route::post('backend/master/uom_entry_sync',[UomEntryController::class, 'sync'])->name('backend.master.uom_entry_sync');

		Route::get('backend/master/discount_program/png',[DiscountProgramController::class, 'png'])->name('backend.master.discount_program.png');
		Route::post('backend/master/discount_program/png_sync',[DiscountProgramController::class, 'png_sync'])->name('backend.master.discount_program.png_sync');
		Route::get('backend/master/discount_program/lta',[DiscountProgramController::class, 'lta'])->name('backend.master.discount_program.lta');
		Route::post('backend/master/discount_program/lta_sync',[DiscountProgramController::class, 'lta_sync'])->name('backend.master.discount_program.lta_sync');

    Route::get('backend/app/sales',[SalesOrderController::class, 'index'])->name('backend.app.sales');
		Route::post('backend/app/sales/search',[SalesOrderController::class, 'search'])->name('backend.app.sales.search');
    Route::get('backend/app/sales/temp_table',[SalesOrderController::class, 'temp_table'])->name('backend.app.sales.temp_table');
    Route::post('backend/app/sales/temp_store',[SalesOrderController::class, 'temp_store'])->name('backend.app.sales.temp_store');
    Route::post('backend/app/sales/temp_delete',[SalesOrderController::class, 'temp_delete'])->name('backend.app.sales.temp_delete');
		Route::post('backend/app/sales/manual',[SalesOrderController::class, 'manual'])->name('backend.app.sales.manual');
		Route::get('backend/app/sales/detail/{DocNum}',[SalesOrderController::class, 'detail'])->name('backend.app.sales.detail');
		Route::post('backend/app/sales/selectDocument',[SalesOrderController::class, 'selectDocument'])->name('backend.app.sales.selectDocument');
		Route::post('backend/app/sales/lines_table',[SalesOrderController::class, 'lines_table'])->name('backend.app.sales.lines_table');
		Route::post('backend/app/sales/lines_store',[SalesOrderController::class, 'lines_store'])->name('backend.app.sales.lines_store');
		Route::post('backend/app/sales/lines_edit',[SalesOrderController::class, 'lines_edit'])->name('backend.app.sales.lines_edit');
		Route::post('backend/app/sales/lines_update',[SalesOrderController::class, 'lines_update'])->name('backend.app.sales.lines_update');
		Route::post('backend/app/sales/lines_delete',[SalesOrderController::class, 'lines_delete'])->name('backend.app.sales.lines_delete');
		Route::post('backend/app/sales/discount',[SalesOrderController::class, 'discount'])->name('backend.app.sales.discount');
		Route::post('backend/app/sales/discount_update',[SalesOrderController::class, 'discount_update'])->name('backend.app.sales.discount_update');
		
		// Search SO Create
		Route::post('/backend/app/sales/searchCustomerCreate', [SalesOrderController::class, 'searchCustomerCreate'])->name('backend.app.sales.searchCustomerCreate');
    Route::post('/backend/app/sales/selectCustomerCreate', [SalesOrderController::class, 'selectCustomerCreate'])->name('backend.app.sales.selectCustomerCreate');
    Route::post('/backend/app/sales/searchItemCreate', [SalesOrderController::class, 'searchItemCreate'])->name('backend.app.sales.searchItemCreate');
    Route::post('/backend/app/sales/selectItemCreate', [SalesOrderController::class, 'selectItemCreate'])->name('backend.app.sales.selectItemCreate');

		// Search SO Update
    Route::post('/backend/app/sales/searchItemUpdate', [SalesOrderController::class, 'searchItemUpdate'])->name('backend.app.sales.searchItemUpdate');
    Route::post('/backend/app/sales/selectItemUpdate', [SalesOrderController::class, 'selectItemUpdate'])->name('backend.app.sales.selectItemUpdate');

		//Interfacing - Storemaster
		Route::get('/backend/app/interfacing/storemaster', [RtdxController::class, 'storemaster'])->name('backend.app.interfacing.storemaster');
		Route::post('/backend/app/interfacing/storemaster_sync', [RtdxController::class, 'storemaster_sync'])->name('backend.app.interfacing.storemaster_sync');
		Route::post('/backend/app/interfacing/storemaster_view', [RtdxController::class, 'storemaster_view'])->name('backend.app.interfacing.storemaster_view');
		Route::post('/backend/app/interfacing/storemaster_export', [RtdxController::class, 'storemaster_export'])->name('backend.app.interfacing.storemaster_export');

		Route::get('/backend/whatsapp/monitor', [WhatsappController::class, 'monitor'])->name('backend.whatsapp.monitor');
});