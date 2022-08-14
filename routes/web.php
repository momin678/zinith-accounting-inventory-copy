<?php

use App\PurchaseReturn;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

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

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/', 'Auth\LoginController@loginf')->name('loginf');


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});

Route::get('/pdf/{id}', 'HomeController@pdf')->name('pdf');

Route::get('/search/ajax/{id}', 'HomeController@SearchAjax')->name('admin.masterAccSearchAjax');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/home', 'backend\ParentProfileController@index')->name('home');
Route::get('/mominul', function (Service $service) {
  die(get_class($service));
});
Route::group(['middleware' => 'auth'], function(){
  // dd(app());
  Route::get('/dashboard', 'backend\DashboardController@index');
  Route::get('/form-layout', 'backend\DashboardController@form_layout');
  Route::get('/form-input', 'backend\DashboardController@form_input');
  Route::resource('role', 'backend\RoleController');
  Route::resource('settings','backend\SettingController');

  // Project Details Starts
  Route::get('/project-details', 'backend\ProjectController@projectDetails')->name('projectDetails');
  Route::post('/project-details/Post', 'backend\ProjectController@projectDetailsPost')->name('projectDetailsPost');
  Route::get('/project-details/edit/{proj}', 'backend\ProjectController@projectEdit')->name('projectEdit');
  Route::post('/project-details/Post/edit/{proj}', 'backend\ProjectController@projectDetailsUpdate')->name('projectDetailsUpdate');
  Route::get('/project-details/delete/{proj}', 'backend\ProjectController@projectDelete')->name('projectDelete');
  Route::get('/project-details/form', 'backend\ProjectController@projectForm')->name('projectForm');
  // Project Details Ends

  // Bank Details Starts
  Route::get('/bank-details', 'backend\BankController@bankDetails')->name('bankDetails');
  Route::post('/bank-details/Post', 'backend\BankController@bankDetailsPost')->name('bankDetailsPost');
  Route::get('/bank-details/edit/{bank}', 'backend\BankController@bankEdit')->name('bankEdit');
  Route::post('/bank-details/Post/edit/{bank}', 'backend\BankController@bankDetailsUpdate')->name('bankDetailsUpdate');
  Route::get('/bank-details/delete/{bank}', 'backend\BankController@bankDelete')->name('bankDelete');
  Route::get('/bank-details/form/', 'backend\BankController@bankForm')->name('bankForm');
  // Bank Details Ends

  // Master Accounts Details Starts
  Route::get('/master-accounts-details', 'backend\MasterAccountController@masteAccDetails')->name('masteAccDetails');
  Route::post('/master-details/Post', 'backend\MasterAccountController@MasterDetailsPost')->name('masterDetailsPost');
  Route::get('/master-details/edit/{masterAcc}', 'backend\MasterAccountController@masterEdit')->name('masterEdit');
  Route::post('/master-details/Post/edit/{masterAcc}', 'backend\MasterAccountController@masterDetailsUpdate')->name('masterDetailsUpdate');
  Route::get('/master-details/delete/{masterAcc}', 'backend\MasterAccountController@masterDelete')->name('masterDelete');
  Route::get('/master-details/type', 'backend\MasterAccountController@mstAccType')->name('mstAccType');
  Route::post('/master-details/masterCatPost', 'backend\MasterAccountController@masterCatPost')->name('masterCatPost');
  Route::post('/master-details/masterAccType', 'backend\MasterAccountController@masterAccType')->name('masterAccType');
  Route::post('/master-details/findMastedCode', 'backend\MasterAccountController@findMastedCode')->name('findMastedCode');
  // Master Accounts Details Ends


  // Account Head Details Starts
  Route::get('/accounts-head-details', 'backend\MasterAccountController@accHeadDetails')->name('accHeadDetails');
  Route::post('/project-details/Post/{masterAcc}', 'backend\MasterAccountController@accHeahDetailsPost')->name('accHeahDetailsPost');
  Route::get('/findMasterAcc/{masterAcc}', 'backend\MasterAccountController@findMasterAcc')->name('findMasterAcc');
  Route::get('/editAccHead/{item}', 'backend\MasterAccountController@editAccHead')->name('editAccHead');
  Route::post('/project-details/edit/post/{account_head}', 'backend\MasterAccountController@accHeahEditPost')->name('accHeahEditPost');
  Route::get('/acount-head/delete/{account_head}', 'backend\MasterAccountController@deleteAcHead')->name('deleteAcHead');
  // Account Head Details Ends

  // Cost center Details Starts
  Route::get('/cost-center-details', 'backend\CostCenterController@costCenterDetails')->name('costCenterDetails');
  Route::post('/costCenter/Post/', 'backend\CostCenterController@costCenterPost')->name('costCenterPost');
  Route::get('/cost-center/edit/{costCenter}', 'backend\CostCenterController@costCenEdit')->name('costCenEdit');
  Route::post('/cost-center/Post/edit/{costCenter}', 'backend\CostCenterController@costCentersUpdate')->name('costCentersUpdate');
  Route::get('/cost-center/delete/{costCenter}', 'backend\CostCenterController@costCenDelete')->name('costCenDelete');
  Route::get('/cost-center/form/', 'backend\CostCenterController@costCenterForm')->name('costCenterForm');
  // Cost center Details Ends

  // Profit Ccenter center Details Starts
  Route::get('/profit-details', 'backend\ProfitCenterController@ProfitCenterDetails')->name('profitCenterDetails');
  Route::post('/profit-center/Post/', 'backend\ProfitCenterController@profitCenterPost')->name('profitCenterPost');
  Route::get('/profit-center/edit/{profitCenter}', 'backend\ProfitCenterController@profitCenEdit')->name('profitCenEdit');
  Route::post('/profit-center/Post/edit/{profitCenter}', 'backend\ProfitCenterController@profitCentersUpdate')->name('profitCentersUpdate');
  Route::get('/profit-center/delete/{profitCenter}', 'backend\ProfitCenterController@profitCenDelete')->name('profitCenDelete');
  Route::get('/profit-center/form', 'backend\ProfitCenterController@profitCenterForm')->name('profitCenterForm');
  // Profit Ccenter center Details Ends

  // Profit Ccenter center Details Starts
  Route::get('/party-info', 'backend\PartyInfoController@partyInfoDetails')->name('partyInfoDetails');
  Route::post('/party-info/Post/', 'backend\PartyInfoController@partyInfoPost')->name('partyInfoPost');
  Route::get('/party-info/edit/{pInfo}', 'backend\PartyInfoController@partyInfoEdit')->name('partyInfoEdit');
  Route::post('/party-info/Post/edit/{profitCenter}', 'backend\PartyInfoController@partyInfoUpdate')->name('partyInfoUpdate');
  Route::get('/party-info/delete/{pinfo}', 'backend\PartyInfoController@partyInfoDelete')->name('partyInfoDelete');
  Route::get('/party-info/form/', 'backend\PartyInfoController@partyInfoForm')->name('partyInfoForm');
  // Profit Ccenter center Details Ends

  // Tax Invoice Issue
  Route::get('/taxt-invoice-issue', 'backend\TaxInvoiceController@taxInvoIssue')->name('taxInvoIssue');
  Route::get('/taxt-invoice-issue/post', 'backend\TaxInvoiceController@taxInvoIssuepost')->name('taxInvoIssuepost');
  Route::post('select/item/by/term/', 'backend\TaxInvoiceController@selectItemByTerm')->name('selectItemByTerm');
  Route::post('party/info/by/term/', 'backend\TaxInvoiceController@partyInfoInvoice')->name('partyInfoInvoice');
  Route::post('find/date/', 'backend\TaxInvoiceController@findDate')->name('findDate');
  Route::post('find/item/', 'backend\TaxInvoiceController@findItem')->name('findItem');
  Route::get('temp/invoice/', 'backend\TaxInvoiceController@tempInvoice')->name('tempInvoice');
  Route::post('final/invoice/', 'backend\TaxInvoiceController@finalSaveInvoice')->name('finalSaveInvoice');
  Route::get('invoicess/', 'backend\TaxInvoiceController@invoicess')->name('invoicess');
  Route::get('invoicePrint/{invoice}', 'backend\TaxInvoiceController@invoicePrint')->name('invoicePrint');
  Route::get('itemDelete/{item}', 'backend\TaxInvoiceController@itemDelete')->name('itemDelete');
  Route::get('refresh_invoice/', 'backend\TaxInvoiceController@refresh_invoice')->name('refresh_invoice');
  Route::get('tax-invoice-view/{invoice}', 'backend\TaxInvoiceController@invoiceView')->name('invoiceView');
  Route::get('tax-invoice-edit/{invoice}', 'backend\TaxInvoiceController@invoiceEdit')->name('invoiceEdit');
  Route::post('tax-invoice-update/{invoice}', 'backend\TaxInvoiceController@finalSaveInvoiceUpdate')->name('finalSaveInvoiceUpdate');
  Route::post('find/item/id', 'backend\TaxInvoiceController@findItemId')->name('findItemId');
  Route::get('tax-invoice-list', 'backend\TaxInvoiceController@taxInvoiceList')->name('taxInvoiceList');

  // End Tax Invoice Issue

  //Stock Position
  Route::get('/stock-position', 'backend\StockPositionController@stockPosition')->name('stockPosition');
  Route::get('/stock-position/search-month', 'backend\StockPositionController@searchStockPosition')->name('searchStockPosition');
  //End Stock Position

  //Journal Entry
  Route::get('/journal-entry', 'backend\JournalEntryController@journalEntry')->name('journalEntry');
  Route::post('find-project', 'backend\JournalEntryController@findProject')->name('findProject');
  Route::post('find-cost-center', 'backend\JournalEntryController@findCostCenter')->name('findCostCenter');
  Route::post('find-cost-center/id', 'backend\JournalEntryController@findCostCenterId')->name('findCostCenterId');
  Route::post('party/info/by/term2/', 'backend\JournalEntryController@partyInfoInvoice2')->name('partyInfoInvoice2');
  Route::post('find-account-head', 'backend\JournalEntryController@findAccHead')->name('findAccHead');
  Route::post('find-account-head/id', 'backend\JournalEntryController@findAccHeadId')->name('findAccHeadId');
  Route::post('find-tax_rate', 'backend\JournalEntryController@findTaxRate')->name('findTaxRate');
  Route::post('find-findamount', 'backend\JournalEntryController@findamount')->name('findamount');
  Route::post('journal-entry/post', 'backend\JournalEntryController@journalEntryPost')->name('journalEntryPost');
  Route::get('journal-entry/edit/{journal}', 'backend\JournalEntryController@journalEdit')->name('journalEdit');
  Route::post('journal-entry/edit-post/{journal}', 'backend\JournalEntryController@journalEntryEditPost')->name('journalEntryEditPost');
  Route::get('/journal/delete/{journal}', 'backend\JournalEntryController@journalDelete')->name('journalDelete');
  Route::get('/journal-authorize', 'backend\JournalEntryController@journalAuthorize')->name('journalAuthorize');
  Route::get('/journal-entry/view/{journal}', 'backend\JournalEntryController@journalView')->name('journalView');
  Route::get('/journal-authorize/{journal}', 'backend\JournalEntryController@journalMakeAuthorize')->name('journalMakeAuthorize');
  Route::post('journal/auth-decline/{journal}', 'backend\JournalEntryController@journaAuthDecline')->name('journaAuthDecline');
  Route::get('/journal-approve/{journal}', 'backend\JournalEntryController@journalMakeApprove')->name('journalMakeApprove');
  Route::post('journal/approve-decline/{journal}', 'backend\JournalEntryController@journaApproveDecline')->name('journaApproveDecline');
  Route::get('/journal-approval', 'backend\JournalEntryController@journalApproval')->name('journalApproval');
  //End Journal Entry


  // Sale order receive
  Route::get('/sale-order', 'backend\SaleOrderController@saleOrderReceive')->name('saleOrderReceive');
  Route::post('party/info/by/term/for-sale', 'backend\SaleOrderController@SalePartyInfo')->name('SalePartyInfo');
  Route::post('find/date/for-sale', 'backend\SaleOrderController@findDateSale')->name('findDateSale');
  Route::post('find/item/for-sale', 'backend\SaleOrderController@findItemSale')->name('findItemSale');
  Route::post('find/item/id/for-sale', 'backend\SaleOrderController@findItemIdSale')->name('findItemIdSale');
  Route::get('temp/sale-order/', 'backend\SaleOrderController@tempSaleOrder')->name('tempSaleOrder');
  Route::get('itemDelete/for-sale/{item}', 'backend\SaleOrderController@itemDeleteSale')->name('itemDeleteSale');
  Route::post('final/sale-order/', 'backend\SaleOrderController@finalSaveSaleOrder')->name('finalSaveSaleOrder');
  Route::get('sale-order-print/{invoice}', 'backend\SaleOrderController@saleOrderPrint')->name('saleOrderPrint');
  Route::get('refresh/sale-order', 'backend\SaleOrderController@refresh_sale_order')->name('refresh_sale_order');
  Route::get('sale-order/view/{sale}', 'backend\SaleOrderController@saleOrderView')->name('saleOrderView');
  Route::get('sale-order-edit/{invoice}', 'backend\SaleOrderController@saleOrderEdit')->name('saleOrderEdit');
  Route::post('sale_order-update/{invoice}', 'backend\SaleOrderController@finalSaveSaleUpdate')->name('finalSaveSaleUpdate');
  Route::get('search-sale-order', 'backend\SaleOrderController@searchSO')->name('searchSO');
  // End sale order

  //Delivery Note
  Route::get('/delivery-note', 'backend\DeliveryNoteController@deliveryNote')->name('deliveryNote');
  Route::get('/sale-order-details/{sale}', 'backend\DeliveryNoteController@saleOrderDetails')->name('saleOrderDetails');
  Route::post('/assing-delivery-note/{sale}', 'backend\DeliveryNoteController@asignDeliveryNote')->name('asignDeliveryNote');
  Route::get('/delivery-note-update/{invoice}', 'backend\DeliveryNoteController@updateNote')->name('updateNote');
  Route::get('delivery-note-print/{invoice}', 'backend\DeliveryNoteController@deliveryNotePrint')->name('deliveryNotePrint');
  Route::post('/generate-delivery-note/{sale}', 'backend\DeliveryNoteController@generateDeliveryNote')->name('generateDeliveryNote');
  Route::get('delivery-note/search-sale-order', 'backend\DeliveryNoteController@searchSODNo')->name('searchSODNo');
  Route::get('delivery-note/search-sale-order/month', 'backend\DeliveryNoteController@searchSODNoMonth')->name('searchSODNoMonth');
  Route::get('delivery-note/search-sale-order/date', 'backend\DeliveryNoteController@searchSODNoDate')->name('searchSODNoDate');
  Route::get('delivery-note/search-sale-order/date-range', 'backend\DeliveryNoteController@searchSODNoDateRange')->name('searchSODNoDateRange');

  Route::get('delivery-note-list/', 'backend\DeliveryNoteController@dnList')->name('dnList');
  Route::get('delivery-note-details/{dNote}', 'backend\DeliveryNoteController@deliveryNoteDetails')->name('deliveryNoteDetails');


  //End delivery note

  //Sales Return
  Route::get('/sales-return', 'backend\SalesReturnController@salesReturn')->name('salesReturn');
  Route::post('find/invoice/for-return', 'backend\SalesReturnController@findInvoice')->name('findInvoice');
  Route::get('temp/sale-order-return/', 'backend\SalesReturnController@tempSaleOrderReturn')->name('tempSaleOrderReturn');
  Route::post('final/sale-return/', 'backend\SalesReturnController@finalSaveSaleReturn')->name('finalSaveSaleReturn');
  Route::get('sale-return-print/{invoice}', 'backend\SalesReturnController@saleReturnPrint')->name('saleReturnPrint');
  Route::get('sale-return-details/{invoice}', 'backend\SalesReturnController@saleReturnDetails')->name('saleReturnDetails');
  Route::get('refresh_sale-return/', 'backend\SalesReturnController@refresh_saleReturn')->name('refresh_saleReturn');

  //End Sales Return

    //Sale Tax invoice issue
  Route::get('tax-invoice-issue/sales', 'backend\TaxInvoiceController@salesTaxtInvoiceIssue')->name('salesTaxtInvoiceIssue');
  Route::get('tax-invoice-details/sale-order/{sale}', 'backend\TaxInvoiceController@saleOrderTaxInvoice')->name('saleOrderTaxInvoice');
  Route::get('/tax-invoice-generate/sale-order/{sale}', 'backend\TaxInvoiceController@genTaxInvoiceSO')->name('genTaxInvoiceSO');
                                      // *******
  Route::get('delivery-details/sale-order/{dnote}', 'backend\TaxInvoiceController@deliveryNotInvoice')->name('deliveryNotInvoice');
  Route::get('/tax-invoice-generate/delivery-note/{dnote}', 'backend\TaxInvoiceController@genTaxInvoiceDN')->name('genTaxInvoiceDN');
  Route::get('delivery-note/search', 'backend\TaxInvoiceController@searchDNo')->name('searchDNo');
  Route::get('delivery-note-search/month', 'backend\TaxInvoiceController@searchDNoMonth')->name('searchDNoMonth');
  Route::get('delivery-note/search/date', 'backend\TaxInvoiceController@searchDNoDate')->name('searchDNoDate');
  Route::get('delivery-note/search/date-range', 'backend\TaxInvoiceController@searchDNoDateRange')->name('searchDNoDateRange');
    //end sales tax invoice issue

  //   delivery note tax invoice

  // work start by mominul
  // mapping 
  Route::resource('mapping', 'backend\MappingController');
  Route::post('account-head', 'backend\MappingController@account_head')->name('account-head');
  Route::post('account-code', 'backend\MappingController@account_code')->name('account-code');
  // style
  Route::resource('style', 'backend\StyleController');    
  // brands
  Route::resource('brand', 'backend\BrandController');
  // group
  Route::resource('group', 'backend\GroupController');
  // list item
  Route::resource('item-list', 'backend\ItemListController');
  Route::post('vat-type-value', 'backend\ItemListController@vat_type_value')->name('vat-type-value');
  Route::post('item-type-no', 'backend\ItemListController@item_type_no')->name('item-type-no');
  Route::post('group-id', 'backend\ItemListController@group_id')->name('group-id');
  Route::post('brand-country', 'backend\ItemListController@brand_country')->name('brand-country');
  Route::get('item-delete/{id}', 'backend\ItemListController@item_delete')->name('item-list.item-delete');
  Route::post('excel-file-import', 'backend\ItemListController@import')->name('excel-file-import');
  Route::post('partyInfo_import-excel-file-import', 'backend\PartyInfoController@partyInfo_import')->name('partyInfo_import-excel-file-import');
  Route::post('style-id', 'backend\StyleController@style_id')->name('style-id');
  Route::post("item-barcode", "backend\ItemListController@item_barcode")->name("item-barcode");
  Route::post("item-barcode-check", "backend\ItemListController@item_barcode_check")->name("item-barcode-check");
  Route::post("item-name-auto-select", "backend\ItemListController@item_name_auto_select")->name("item-name-auto-select");
  Route::get("items-download", "backend\ItemListController@items_download")->name("items-download");
  // purchase requisition
  Route::resource('purchase-requisition', 'backend\PurchaseRequisitionController');
  Route::post("one-po-item-delete", "backend\PurchaseDetailController@one_po_item_delete")->name("one-po-item-delete");
  Route::post("one-po-item-edit", "backend\PurchaseTempController@one_po_item_edit")->name("one-po-item-edit");
  Route::get("editor-pr-process/{id}", "backend\PurchaseRequisitionController@editor_pr_process")->name("editor-pr-process");
  Route::get('authorize-requisition', 'backend\PurchaseRequisitionController@authorize_requisition')->name('authorize-requisition');
  Route::get('authorize-requisition-details/{id}', 'backend\PurchaseRequisitionController@authorize_requisition_details')->name('authorize-requisition-details');
  Route::get('authorize-pr-submit/{id}', 'backend\PurchaseRequisitionController@authorize_pr_submit')->name('authorize-pr-submit');
  Route::get('approve-requisition', 'backend\PurchaseRequisitionController@approve_requisition')->name('approve-requisition');
  Route::get('approve-requisition-details/{id}', 'backend\PurchaseRequisitionController@approve_requisition_details')->name('approve-requisition-details');
  Route::get('approve-pr-submit/{id}', 'backend\PurchaseRequisitionController@approve_pr_submit')->name('approve-pr-submit');
  Route::post('item-list-get', 'backend\PurchaseRequisitionController@item_list_get')->name('item-list-get');
  Route::post('purchase-requisition-item-store', 'backend\PurchaseRequisitionDetailController@purchase_requisition_item_store')->name('purchase-requisition-item-store');
  Route::get('purchase-requisitin-aprove/{id}', 'backend\PurchaseRequisitionController@purchase_requisitin_aprove')->name('purchase-requisitin-aprove');
  Route::get('pr-item-list-delete/{id}', 'backend\PurchaseRequisitionDetailController@pr_item_delete')->name('pr-item-list-delete');
  Route::post('item-qty-get', 'backend\PurchaseRequisitionDetailController@item_qty_get')->name('item-qty-get');
  Route::post('project-name-get', 'backend\PurchaseRequisitionDetailController@project_name_get')->name('project-name-get');
  Route::get("authorize-pr-rejected/{id}", "backend\PurchaseRequisitionController@authorize_pr_rejected")->name("authorize-pr-rejected");
  Route::get("approver-pr-rejected/{id}", "backend\PurchaseRequisitionController@approver_pr_rejected")->name("approver-pr-rejected");
  Route::post("authorize-pr-reviece", "backend\PurchaseRequisitionController@authorize_pr_reviece")->name("authorize-pr-reviece");
  Route::post("approve-pr-reviece", "backend\PurchaseRequisitionController@approve_pr_reviece")->name("approve-pr-reviece");
  Route::get("rejected-requisition", "backend\PurchaseRequisitionController@rejected_requisition")->name("rejected-requisition");
  Route::get("revise-requisition-authorize", "backend\PurchaseRequisitionController@reviece_requisition_authorize")->name("revise-requisition-authorize");
  Route::get("revise-requisition-editor", "backend\PurchaseRequisitionController@reviece_requisition_editor")->name("revise-requisition-editor");
  Route::get('pr-print/{id}', 'backend\PurchaseRequisitionController@pr_print')->name('pr-print');
  Route::post("delete-previouse-pr-item", "backend\PurchaseRequisitionController@delete_previouse_pr_item")->name("delete-previouse-pr-item");
  Route::post("delete-previouse-pr-item-one", "backend\PurchaseRequisitionDetailController@delete_previouse_pr_item_one")->name("delete-previouse-pr-item-one");
  Route::post("pr-filter", "backend\PurchaseRequisitionController@pr_filter")->name("pr-filter");
  // Item purchases
  Route::resource('purchase-temp', 'backend\PurchaseTempController');
  Route::resource('purchase', 'backend\PurchaseController');
  Route::get("purchase-process/{id}", "backend\PurchaseController@purchase_process")->name("purchase-process");
  Route::get("po-generation-approval-list", "backend\PurchaseController@po_generation_approval_list")->name("po-generation-approval-list");
  Route::get("approve-po-submit/{id}", "backend\PurchaseController@approve_po_submit")->name("approve-po-submit");
  Route::post("approve-po-reviece", "backend\PurchaseController@approve_po_reviece")->name("approve-po-reviece");
  Route::get("po-generation-approval-details/{id}", "backend\PurchaseController@po_generation_approval_details")->name("po-generation-approval-details");
  Route::get("po-generation-revise-list", "backend\PurchaseController@po_generation_revise_list")->name("po-generation-revise-list");
  Route::post('purchase-temp-trasfer', 'backend\PurchaseController@purchase_temp_trasfer')->name('purchase-temp-trasfer');
  Route::resource('item-purchase', 'backend\PurchaseDetailController');
  Route::post("po-filter", "backend\PurchaseController@po_filter")->name("po-filter");
  Route::post('all-po-item-delete', 'backend\PurchaseDetailController@all_po_item_delete')->name('all-po-item-delete');
  Route::get('purchase-print/{id}', 'backend\PurchaseDetailController@purchase_print')->name('purchase-print');
  Route::post('supplier-information', 'backend\PurchaseDetailTempController@supplier_information')->name('supplier-information');
  Route::post('temp-po-item-store', 'backend\PurchaseTempController@temp_po_item_store')->name('temp-po-item-store');
  Route::get('temp-item-list-delete/{id}', 'backend\PurchaseDetailTempController@temp_item_delete')->name('temp-item-list-delete');
  Route::post("new-supplier", "backend\PurchaseController@new_supplier")->name("new-supplier");
  // Goods Receive
  Route::resource('goods-received', 'backend\GoodsReceivedController');
  Route::get('gr-details/{id}', 'backend\GoodsReceivedController@gr_details')->name('gr-details');
  Route::get("gr-print/{id}", "backend\GoodsReceivedController@gr_print")->name("gr-print");
  Route::post("gr-list-show", "backend\GoodsReceivedController@gr_list_show")->name("gr-list-show");
  // purchase returm
  Route::resource("purchase-return", 'backend\PurchaseReturnController');
  Route::get('pt-details/{id}', 'backend\PurchaseReturnController@pt_details')->name('pt-details');
  Route::get('pr-return/{id}', 'backend\PurchaseReturnController@pt_return')->name('pr-return');
  Route::get("purchase-return-authorize", "backend\PurchaseReturnController@purchase_return_authorize")->name("purchase-return-authorize");
  Route::get("purchase-return-approval", "backend\PurchaseReturnController@purchase_return_approval")->name("purchase-return-approval");
  Route::get("pt-authorize-details/{id}", "backend\PurchaseReturnController@pt_authorize_details")->name("pt-authorize-details");
  Route::post("authorize-pt-reviece", "backend\PurchaseReturnController@authorize_pt_reviece")->name("authorize-pt-reviece");
  Route::get("purchase-return-revise", "backend\PurchaseReturnController@purchase_return_revise")->name("purchase-return-revise");
  Route::post("purchase-return-item-store", "backend\PurchaseReturnDetailController@purchase_return_item_store")->name("purchase-return-item-store");
  Route::post("pt-item-barcode", "backend\PurchaseReturnDetailController@pt_item_barcode")->name('pt-item-barcode');
  Route::get("temp-return-item-list-delete/{id}", "backend\PurchaseReturnDetailController@temp_return_item_list_delete")->name("temp-return-item-list-delete");
  Route::get("authorize-pt-rejected/{id}", "backend\PurchaseReturnController@authorize_pt_rejected")->name("authorize-pt-rejected");
  Route::get("pt-rejected-list-authorize", "backend\PurchaseReturnController@pt_rejected_list_authorize")->name("pt-rejected-list-authorize");
  Route::get("pt-rejected-list-editor", "backend\PurchaseReturnController@pt_rejected_list_editor")->name("pt-rejected-list-editor");
  Route::get("authorize-pt-submit/{id}", "backend\PurchaseReturnController@authorize_pt_submit")->name("authorize-pt-submit");
  Route::get("pt-approval-details/{id}", "backend\PurchaseReturnController@pt_approval_details")->name("pt-approval-details");
  Route::post("approval-pt-reviece", "backend\PurchaseReturnController@approval_pt_reviece")->name("approval-pt-reviece");
  Route::get("revise-pt-authorize-list", "backend\PurchaseReturnController@revise_pt_authorize_list")->name("revise-pt-authorize-list");
  Route::get("approval-pt-rejected/{id}", "backend\PurchaseReturnController@approval_pt_rejected")->name("approval-pt-rejected");
  Route::get("pt-approval-process/{id}", "backend\PurchaseReturnController@pt_approval_process")->name("pt-approval-process");
  Route::get("pt-print/{id}", "backend\PurchaseReturnController@pt_print")->name("pt-print");
  Route::post("pt-filter", "backend\PurchaseReturnController@pt_filter")->name("pt-filter");
  // payment-voucher
  Route::resource("payment-voucher", "backend\PaymentVoucherController");
  Route::get("pv-process/{id}", "backend\PaymentVoucherController@pv_process")->name("pv-process");
  Route::get("pv-create/{id}", "backend\PaymentVoucherController@pv_create")->name("pv-create");
  Route::get("pending-pv-authorize", "backend\PaymentVoucherController@pending_pv_authorize")->name("pending-pv-authorize");
  Route::get("pv-authorize-view/{id}", "backend\PaymentVoucherController@pv_authorize_view")->name("pv-authorize-view");
  Route::post("revise-pv-submit-authorizer", "backend\PaymentVoucherController@revise_pv_submit_authorizer")->name("revise-pv-submit-authorizer");
  Route::get("rejected-pv-authorizer/{id}", "backend\PaymentVoucherController@rejected_pv_authorizer")->name("rejected-pv-authorizer");
  Route::get("rejected-pv-all-editor", "backend\PaymentVoucherController@rejected_pv_all_editor")->name("rejected-pv-all-editor");
  Route::get("pv-approve-authorizer/{id}", "backend\PaymentVoucherController@pv_approve_authorizer")->name("pv-approve-authorizer");
  Route::get("pending-pv-approval", "backend\PaymentVoucherController@pending_pv_approval")->name("pending-pv-approval");
  Route::get("pv-approval-view/{id}", "backend\PaymentVoucherController@pv_approval_view")->name("pv-approval-view");
  Route::post("revise-pv-submit-approval", "backend\PaymentVoucherController@revise_pv_submit_approval")->name("revise-pv-submit-approval");
  Route::get("revise-pv-all-editor", "backend\PaymentVoucherController@revise_pv_all_editor")->name("revise-pv-all-editor");
  Route::get("revise-pv-authorizer-approver", "backend\PaymentVoucherController@revise_pv_authorizer_approver")->name("revise-pv-authorizer-approver");
  Route::get("rejected-pv-approver/{id}", "backend\PaymentVoucherController@rejected_pv_approver")->name("rejected-pv-approver");
  Route::get("rejected-pv-authorize", "backend\PaymentVoucherController@rejected_pv_authorize")->name("rejected-pv-authorize");
  Route::get("pv-approve-approval/{id}", "backend\PaymentVoucherController@pv_approve_approval")->name("pv-approve-approval");
  Route::get("approve-pv-view/{id}", "backend\PaymentVoucherController@approve_pv_view")->name("approve-pv-view");
  Route::get("pv-pdf-print/{id}", "backend\PaymentVoucherController@pv_pdf_print")->name("pv-pdf-print");
  Route::post("pv-filter", "backend\PaymentVoucherController@pv_filter")->name("pv-filter");
  // Receipt Voucher
  Route::resource("receipt-voucher", "backend\ReceiptVoucherController");
  Route::get("invoice-details/{id}", "backend\ReceiptVoucherController@invoice_details")->name("invoice-details");
  Route::get("receipt-voucher-create/{id}", "backend\ReceiptVoucherController@receipt_voucher_create")->name("receipt-voucher-create");
  Route::get("receipt-voucher-process/{id}", "backend\ReceiptVoucherController@receipt_voucher_process")->name("receipt-voucher-process");
  Route::get("receipt-voucher-print/{id}", "backend\ReceiptVoucherController@receipt_voucher_print")->name("receipt-voucher-print");
  Route::get("receipt-voucher-approval-list", "backend\ReceiptVoucherController@receipt_voucher_approval_list")->name("receipt-voucher-approval-list");
  Route::get("approve-rv-details/{id}", "backend\ReceiptVoucherController@approve_rv_details")->name("approve-rv-details");
  Route::post("approve-rv-reviece", "backend\ReceiptVoucherController@approve_rv_reviece")->name("approve-rv-reviece");
  Route::get("approver-rv-approve/{id}", "backend\ReceiptVoucherController@approver_rv_approve")->name("approver-rv-approve");
  Route::get("rv-revise-list", "backend\ReceiptVoucherController@rv_revise_list")->name("rv-revise-list");
  Route::get("rv-revise-update/{id}", "backend\ReceiptVoucherController@rv_revise_update")->name("rv-revise-update");
  Route::post("rv-revise-update-complete/{id}", "backend\ReceiptVoucherController@rv_revise_update_complete")->name("rv-revise-update-complete");
  Route::post("rv-filter", "backend\ReceiptVoucherController@rv_filter")->name("rv-filter");
  // purchase summary report
  Route::resource("purchase-summary", "backend\PurchaseSummaryController");
  // work end by mominul
  
  // Shagor: Invoice Posting
  Route::get('invoice-posting', 'backend\InvoicePosting@index')->name('invoice_posting');
  Route::get('invoice-posting/{id}', 'backend\InvoicePosting@invoice_post_form')->name('invoice_post_form');
  Route::post('invoice-posting-submit','backend\InvoicePosting@invoice_posting_submit')->name('invoice-posting-submit');
  Route::get('ip-details/{id}', 'backend\InvoicePosting@ip_details')->name('ip_details');
  Route::get("ip-print/{id}", "backend\InvoicePosting@ip_print")->name("ip-print");
});
