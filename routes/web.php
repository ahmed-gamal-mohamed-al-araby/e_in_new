<?php

// use App\Http\Controllers\Supplier\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Deduction\InvoiceDeductionController;
use App\Http\Middleware\EnsureTaxesShowReportOnly;

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

// optimize-clear
Route::get('/optimize-clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "Application - optimize is cleared";
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Auth::routes();

        Route::group(['middleware' => 'taxesShowReportOnly'], function () {

            // Country
            Route::group(['namespace' => 'Country', 'middleware' => 'auth'], function () {
                Route::resource('/country', 'CountryController');

                // ajax select box
                Route::get('country/getcitiesFromCountry/{id}', 'CountryController@citiesOfcountry')->name('citiesOfcountry');
            });

            // City
            Route::group(['namespace' => 'City', 'middleware' => 'auth'], function () {
                Route::resource('/city', 'CityController');
            });

            // Client
            Route::group(['namespace' => 'Client', 'middleware' => 'auth'], function () {

                // businessClients
                Route::get('businessClients/archive', 'businessController@archive_index')->name('businessClients.archive_businessClients');
                Route::get('businessClients/company_search', 'businessController@company_search')->name('businessClients.search');
                Route::resource('/businessClients', 'businessController');
                Route::get('businessClients/profile/{id}', 'businessController@get_profile')->name('businessClients.profile');
                Route::get('businessClients/pagination/fetch_data', 'businessController@fetch_data')->name('businessClients.pagination.fetch_data');
                Route::post('businessClients/client_archive', 'businessController@businessClients_archive')->name('businessClients.client_archive');
                Route::post('businessClients/client_restore', 'businessController@businessClients_restore')->name('businessClients.client_restore');
                Route::get('businessClients/business-client-approve/{id}', 'businessController@showForApprove')->name('show_business_client_approve');
                Route::put('businessClients/business-client-approve/{id}', 'businessController@approved')->name('business_client_approved');
                Route::post('/clients/getBusinessOrPersonClientDataByName', 'ClientController@getBusinessOrPersonClientDataByName')->name('getBusinessOrPersonClientDataByName');
                Route::post('businessClients/permanent-delete', 'businessController@permanent_delete')->name('businessClient.permanent_delete');

                // person Client
                Route::resource('/personClient', 'personController');
                Route::get('personClient/personClient-approve/{id}', 'personController@showForApprove')->name('show_person_Client_approve');
                Route::get('/personClient/{id}/approve_edit', 'personController@approve_edit')->name('person_client.approve_edit');
                Route::put('personClient/personClient-approve/{id}', 'personController@approved')->name('person_client_approved');

                // foreigner Client
                Route::resource('/foreignerClient', 'foreignerController');
                Route::get('foreignerClient/foreignerClient-approve/{id}', 'foreignerController@showForApprove')->name('show_foreigner_client_approve');
                Route::get('/foreignerClient/{id}/approve_edit', 'foreignerController@approve_edit')->name('business_client.approve_edit');
                Route::put('foreignerClient/foreignerClient-approve/{id}', 'foreignerController@approved')->name('foreigner_client_approved');

                // PO clients
                Route::get('/clients/getClientsFromclientType', 'ClientController@getClientsFromclientType')->name('getClientsFromclientType');
                Route::POST('/clients/getBusinessOrPersonClientData', 'ClientController@getBusinessOrPersonClientData')->name('getBusinessOrPersonClientData');

                // Document clients
                Route::get('/clients/getDocumentForeignerPurchaseOrder/{id}', 'ClientController@getDocumentForeignerPurchaseOrder');
                Route::POST('/clients/getDocumentBusinessOrPersonClientData', 'ClientController@getDocumentBusinessOrPersonClientData');
                Route::post('/clients/getBusinessOrPersonClientDataByName', 'ClientController@getBusinessOrPersonClientDataByName')->name('getBusinessOrPersonClientDataByName');
                // Client documents
                Route::get('/clients/related-documents', 'ClientController@related_document_show')->name('client.documents_view');
                Route::Post('/clients/related-documents', 'ClientController@related_document')->name('client.documents_data');
            });

            // Products
            Route::group(['namespace' => 'Product', 'middleware' => 'auth'], function () {
                Route::resource('/product', 'ProductsController');
                Route::get('product/product-approve/{id}', 'ProductsController@showForApprove')->name('show_product_approve');
                Route::get('/product/{id}/approve_edit', 'ProductsController@approve_edit')->name('product.approve_edit');
                Route::put('product/product-approve/{id}', 'ProductsController@approved')->name('product_approved');
            });

            // Company
            Route::group(['namespace' => 'Company', 'middleware' => 'auth'], function () {
                Route::resource('/company', 'companyController');
            });

            // Deduction
            Route::group(['namespace' => 'Deduction', 'middleware' => 'auth'], function () {
                Route::resource('/deduction', 'DeductionController');
            });

            // Banks
            Route::group(['namespace' => 'Bank', 'middleware' => 'auth'], function () {
                Route::resource('/bank', 'BankController')->except('destroy');
                Route::get('bank/bank-approve/{id}', 'BankController@showForApprove')->name('show_bank_approve');
                Route::put('bank/bank-approve/{id}', 'BankController@approved')->name('bank_approved');
            });

            // Letters
            Route::group(['namespace' => 'Letters', 'middleware' => 'auth'], function () {
                Route::resource('/letter_guarantee', 'LettersGuaranteeController');
                Route::resource('/letter_guarantee_request', 'LettersGuaranteeRequestController');
                Route::get('/get_supply_order_ajax', 'LettersGuaranteeRequestController@get_supply_order')->name("getSupplyOrder");
                Route::get('/get_supply_order_data-ajax', 'LettersGuaranteeRequestController@get_supply_order_data')->name("get_supply_order_data");
                Route::get('/extend_raise/{id}', 'LettersGuaranteeController@extend_raise')->name("letter_guarantee.extend_raise");
                Route::get('/letter_guarantee_create_from_request/{id}', 'LettersGuaranteeController@letter_guarantee_create_from_request')->name("letter_guarantee_request.letter_guarantee_create_from_request");
                Route::get('/letter_guarantee_request/print/{id}', 'LettersGuaranteeRequestController@print')->name("letter_guarantee_request.print");
                Route::put('/extend_raise_store/{id}', 'LettersGuaranteeController@extend_raise_store')->name("letter_guarantee.extend_raise_store");
                Route::put('/bank_commissions', 'LettersGuaranteeController@bank_commissions')->name("letter_guarantee.bank_commissions");
                Route::put('/letter_guarantee_answered', 'LettersGuaranteeController@letter_guarantee_answered')->name("letter_guarantee_answered.store");
                Route::post('/importLettersGuarantee', 'LettersGuaranteeController@import_letters_guarantee')->name("letter_guarantee.import");
                Route::post('/importLettersGuaranteeChanging', 'LettersGuaranteeController@import_letters_guarantee_changing')->name("letter_guarantee_changing.import");


                Route::get('/select2-autocomplete-ajax', 'LettersGuaranteeController@dataAjax');

            });

            // Warranty Checks
            Route::group(['namespace' => 'WarrantyChecks', 'middleware' => 'auth'], function () {
                Route::resource('/warranty_checks', 'WarrantyChecksController');
                Route::post('/import_warranty_checks', 'WarrantyChecksController@import_warranty_checks')->name("warranty_checks.import");


                // Route::get('/select2-autocomplete-ajax', 'WarrantyChecksController@dataAjax');

            });

            // Purchase Order
            Route::group(['namespace' => 'PO', 'middleware' => 'auth'], function () {
                Route::get('/purchaseorders/related-documents', 'PurchaseOrderController@related_document_show')->name('purchaseorders.related_document.show');
                Route::Post('/purchaseorders/related-documents', 'PurchaseOrderController@related_document')->name('purchaseorders.related_document.filter');
                Route::get('/purchaseorders/check_purchase_order_reference/{purchase_order_reference}', 'PurchaseOrderController@check_purchase_order_reference');
                Route::Post('/purchaseorders/check_purchase_order_reference', 'PurchaseOrderController@check_purchase_order_reference');
                Route::get('/purchaseorders/{PO_id}/edit/add-items-via-excel', 'PurchaseOrderController@showAddItemsViaExcel')->name('purchaseorders.show_edit_po_add_items_via_excel');
                Route::post('/purchaseorders/edit/add-items-via-excel', 'PurchaseOrderController@addItemsViaExcel')->name('purchaseorders.edit_po_add_items_via_excel');
                Route::delete('purchaseorders/destroy', 'PurchaseOrderController@destroy')->name('purchaseorder.destroy');

                Route::resource('/purchaseorders', 'PurchaseOrderController');

                Route::get('/purchaseorders/purchaseorder-approve/{id}', 'PurchaseOrderController@showForApprove')->name('show_purchaseorder_approve');
                Route::put('/purchaseorders/purchaseorder-approve/{id}', 'PurchaseOrderController@approved')->name('purchaseorder_approved');
                Route::get('getProductData/{id}', 'PurchaseOrderController@returnProductData');


                Route::get('/downloadExcel', 'PurchaseOrderController@downloadExcel');
                Route::post('/purchaseorders/fileStore', 'PurchaseOrderController@storeFile')->name('poFile.store');
                Route::get('/getBankData/{id}', 'PurchaseOrderController@returnBankData');
                Route::get('/getPOData/{purchase_order_reference}', 'PurchaseOrderController@returnPOData');
                Route::post('/getProductFromPurchaseOrder', 'PurchaseOrderController@returnPOProductData');
                Route::post('/getFullProductDataFromPurchaseOrder', 'PurchaseOrderController@returnPOProductFullData');
                // confirm update item quantity
                Route::post('/confirmUpdateItemQuantity', 'PurchaseOrderController@confirmUpdateItemQuantity')->name('confirm_update_item_quantity');
                // confirm update item quantity
                Route::get('/getSelectedItemFromPurchaseOrder/{item_id}', 'PurchaseOrderController@returnSelectedItemData');
                Route::get('/purchaseorder/archive', 'PurchaseOrderController@archiveIndex')->name('purchaseorders.archive_purchaseorders');
                Route::get('/purchaseorder/waiting-approve', 'PurchaseOrderController@waitingApproveIndex')->name('purchaseorders.wating_approve');
                Route::get('/purchaseorder/pagination/fetch_data', 'PurchaseOrderController@fetch_data')->name('purchaseorders.pagination.fetch_data');
                Route::post('/purchaseorder/purchaseorder_archive', 'PurchaseOrderController@purchaseorder_archive')->name('purchaseorder.purchaseorder_archive');
                Route::post('/purchaseorder/purchaseorder_restore', 'PurchaseOrderController@purchaseorder_restore')->name('purchaseorder.purchaseorder_restore');
                Route::post('department/permanent-delete', 'PurchaseOrderController@permanent_delete')->name('purchaseorder.permanent_delete');
                Route::get('/poItems/show/{id}', 'PoItemController@show')->name('item.show');
                Route::post('/poItems/storeIndividualItem', 'PoItemController@storeIndividualItem')->name('item.storeIndividualItem');
                Route::get('/poItems/{id}/edit', 'PoItemController@edit')->name('item.edit');
                Route::get('/poItems/get-available-quantity/{POItemId}', 'PoItemController@getAvailableQuantity')->name('item.get-available-quantity');
                Route::put('/poItems/update', 'PoItemController@update')->name('item.update');
                Route::get('/getPoItemById/{id}', 'PoItemController@getPoItemById')->name('item.getPoItemById');
                Route::delete('/poItems/destroy', 'PoItemController@destroy')->name('item.destroy');
                Route::post('/getDocumentFromPurchaseOrder', 'PurchaseOrderController@getDocumentFromPurchaseOrder')->name('getDocumentsFromPurchaseOrder');
                Route::post('/getDocumentFromPurchaseOrderByPOID', 'PurchaseOrderController@getDocumentFromPurchaseOrderByPOID')->name('getDocumentFromPurchaseOrderByPOID');
            });


            // Document Routes
            Route::group(['namespace' => 'Document', 'middleware' => 'auth'], function () {
                Route::post('documents/check_document_number', 'DocumentController@check_document_number');
                Route::get('documents/index_document_request', 'DocumentController@index_document_request')->name('documents.index_document_request');
                Route::get('documents/newDocument', 'DocumentController@newDocument')->name('documents.newDocument');
                Route::get('documents/newDocument2', 'DocumentController@newDocument2')->name('documents.newDocument2');
                Route::get('documents/sent-document', 'DocumentController@indexOFSentDocument')->name('documents.indexOFSentDocument');
                Route::post('documents/documentSubmission', 'DocumentController@documentSubmission')->name('documents.documentSubmission');
                Route::post('documents/submitToServer', 'DocumentController@submitToAPI')->name('documents.submitToServer');
                Route::post('documents/submitToServer2', 'DocumentController@submitToAPI2')->name('documents.submitToServer2');
                Route::post('documents/virtualSubmit', 'DocumentController@virtualSubmit')->name('documents.virtualSubmit');
                Route::delete('document/destroy', 'DocumentController@destroy')->name('document.destroy');
                Route::get('documents/archive', 'DocumentController@indexOFarchiveDocument')->name('documents.indexOFarchiveDocument');
                Route::get('documents/waiting-approve', 'DocumentController@indexOWaitingApproveDocument')->name('documents.waiting_approve');
                Route::delete('document/document_archive/{id}', 'DocumentController@document_archive')->name('document.document_archive');
                Route::delete('document/document_restore/{id}', 'DocumentController@document_restore')->name('document.document_restore');
                Route::get('documents/print/{id}', 'DocumentController@print')->name('documents.print');
                Route::get('documents/document-approve/{id}', 'DocumentController@showForApprove')->name('show_document_approve');
                Route::put('documents/document-approve/{id}', 'DocumentController@approved')->name('document_approved');
                Route::post('documents/submitmultidocuments', 'DocumentController@submitMultiDocuments')->name('documents.submitMultiDocuments');
                Route::get('documents/getRecentDocuments', 'DocumentController@getRecentDocuments')->name('documents.getRecentDocuments');
                Route::get('documents/getRecentDocumentsFromServer', 'DocumentController@getRecentDocumentsFromServer')->name('documents.getRecentDocumentsFromServer');
                Route::put('documents/cancelOrRejectDocument', 'DocumentController@cancelOrRejectDocument')->name('documents.cancelOrRejectDocument');
                Route::get('documents/getRecentDocumentsReceived', 'DocumentController@getRecentDocumentsReceived')->name('documents.getRecentDocumentsReceived');
                Route::get('documents/getRecentDocumentsReceivedFromServer', 'DocumentController@getRecentDocumentsReceivedFromServer')->name('documents.getRecentDocumentsReceivedFromServer');
                Route::get('documents/create-special', 'DocumentController@create_special')->name('documents.create_special');
                Route::post('documents/store-special', 'DocumentController@store_special')->name('documents.store_special');
                Route::get('/documents/test', 'DocumentController@test')->name('documents.test');
                Route::post('documents/import-delete', 'DocumentController@importExcelForCreateSpecial')->name('importExcelForCreateSpecial');
                Route::resource('/documents', 'DocumentController')->except('destroy');
            });

            // Report Routes
            Route::group(['namespace' => 'Report', 'middleware' => 'auth'], function () {
                Route::get('/reports/vattaxreport', 'ReportController@createvattaxreport')->name('reports.vattaxreport')->withoutMiddleware([EnsureTaxesShowReportOnly::class]);
                Route::post('/reports/getDocumentsToVatTaxReport', 'ReportController@getDocumentsToVatTaxReport')->name('reports.getDocumentsToVatTaxReport')->withoutMiddleware([EnsureTaxesShowReportOnly::class]);;
                Route::get('/reports/purchaserrderreport', 'ReportController@createporeport')->name('reports.createpurchaseorderreport');
                Route::get('/reports/getClientsFromclientType', 'ReportController@getClientsFromclientType')->name('getClientsFromclientType');
                Route::get('/reports/getALLClientTax', 'ReportController@getALLClientsTax');
                Route::get('/reports/getALLClientsViaClientType', 'ReportController@getALLClientsViaClientType');
                Route::post('/reports/getPurchaseOrderData', 'ReportController@getPurchaseOrderData')->name('getPurchaseOrderData');
                Route::post('/reports/getPurchaseOrdersForClient', 'ReportController@getPurchaseOrdersForClient')->name('getPurchaseOrdersForClient');
                Route::post('/reports/getDocumentsRelatedTopurchaseOrder', 'ReportController@getDocumentsRelatedTopurchaseOrder')->name('getDocumentsRelatedTopurchaseOrder');
                Route::post('/reports/getEstimatedPurchaseOrderData', 'ReportController@getEstimatedPurchaseOrderData')->name('getEstimatedPurchaseOrderData');
                Route::post('/reports/get-documents-belong-to-purchaseOrder', 'ReportController@getDocumentsBelongToPurchaseOrder')->name('get_documents_belong_to_purchaseOrder');

                Route::get('/reports/deduction-report', 'ReportController@deductionReportView')->name('reports.deduction_report_view');
                Route::post('/reports/deduction-report', 'ReportController@deductionReportData')->name('reports.deduction_report_get_data');

                Route::get('/reports/deduction-report-all', 'ReportController@deductionReportAllView')->name('reports.deduction_report_view_All');
                Route::post('/reports/deduction-report-all', 'ReportController@deductionReportAllData')->name('reports.deduction_report_get_data_All');
              
                Route::get('/reports/deductions-report-all', 'ReportController@deductionsReportAllView')->name('reports.deductions_report_view_All');
                Route::post('/reports/deductions-report-all', 'ReportController@deductionsReportAllData')->name('reports.deductions_report_get_data_All');

                Route::post('/reports/approve', "ReportController@mulityApprovePayment")->name('mulity_approve_payment');

                Route::get('/reports/general', 'ReportController@general')->name('reports.general');
                Route::get('/reports/tax-rate-letter-report', 'ReportController@taxRateLetter')->name('reports.tax_rate_letter_report');
                Route::get('/reports/primary-delivery-status', 'ReportController@primaryDeliveryStatus')->name('reports.primary_delivery_status');
                Route::get('/reports/final-delivery-status', 'ReportController@finalDeliveryStatus')->name('reports.final_delivery_status');
                Route::get('/reports/social-insurance-status', 'ReportController@socialInsuranceStatus')->name('reports.social_insurance_status');
                Route::get('/reports/labor-insurance-status', 'ReportController@laborInsuranceStatus')->name('reports.labor_insurance_status');
                Route::get('/reports/tax-exemption-certificate-status', 'ReportController@taxExemptionCertificateStatus')->name('reports.tax_exemption_certificate_status');
                Route::get('/reports/received_final-performance-bond-status', 'ReportController@receivedFinalPerformanceBondStatus')->name('reports.received_final_performance_bond_status');

                Route::get('/reports/client-analysis', 'ReportController@clientAnaylsisReportView')->name('reports.client_analysis_view');
                Route::post('/reports/client-analysis', 'ReportController@clientAnaylsisReportData')->name('reports.client_analysis_get_data');

                Route::get('/reports/client-balances', 'ReportController@clientBalancesReport')->name('reports.client_balances_view');
                Route::post('/reports/client-balances', 'ReportController@clientBalancesReportData')->name('reports.client_balances_get_data');

                Route::get('/reports/payment_date', 'ReportController@paymentDateReport')->name('reports.payment_date_view');
                Route::post('/reports/payment_date', 'ReportController@paymentDateReportData')->name('reports.payment_date_get_data');

                Route::get('/reports/collections', 'ReportController@collectionsReport')->name('reports.collections_view');
                Route::post('/reports/collections', 'ReportController@collectionsReportData')->name('reports.collections_get_data');

                Route::get('/reports/daily-client-balances', 'ReportController@dailyClientBalancesReport')->name('reports.daily_client_balances_view');
                Route::post('/reports/daily-client-balances', 'ReportController@dailyClientBalancesReportData')->name('reports.daily_client_balances_get_data');
                // The financial position of the supply order
                Route::get('/reports/financialposition-of-supplyorder', 'ReportController@financialPositionOfSupplyOrder')->name('reports.financialposition_of_supplyorder');
                Route::post('/reports/financialposition-of-supplyorder', 'ReportController@financialPositionOfSupplyOrderData')->name('reports.financialposition_of_supplyorder_get_data');
                Route::get('/reports/getClientsPo', 'ReportController@getClientsPo')->name('getClientsPo');
                //end The financial position of the supply order
                Route::get('/report_account_number', "ReportController@reportAccountNumber")->name('reports.report_account_number')->middleware('auth');
                Route::post('/report_account_number', "ReportController@reportAccountNumberAjax")->name('report_account_number_ajax')->middleware('auth');

                Route::get('/reports/client-document-balances', 'ReportController@clientDocumentBalancesReport')->name('reports.client_document_balances_view');
                Route::post('/reports/client-document-balances', 'ReportController@clientDocumentBalancesReportData')->name('reports.client_document_balances_get_data');

                // valid letters guarantee report
                Route::get('/reports/valid-letters-guarantee', 'ReportController@validLettersGuaranteeReportView')->name('reports.valid_letters_guarantee_view');
                Route::post('/reports/valid-letters-guarantee', 'ReportController@validLettersGuaranteeReportData')->name('reports.valid_letters_guarantee_get_data');
                // applicant letters guarantee report
                Route::get('/reports/applicant-letters-guarantee', 'ReportController@applicantLettersGuaranteeReportView')->name('reports.applicant_letters_guarantee_view');
                Route::post('/reports/applicant-letters-guarantee', 'ReportController@applicantLettersGuaranteeReportData')->name('reports.applicant_letters_guarantee_get_data');
                // checks issued clients report
                Route::get('/reports/checks_issued_clients', 'ReportController@vchecksIssuedClientsReportView')->name('reports.checks_issued_clients_view');
                Route::post('/reports/checks_issued_clients', 'ReportController@vchecksIssuedClientsReportData')->name('reports.checks_issued_clients_get_data');
            });

            // Notifications
            Route::group(['namespace' => 'Notification', 'middleware' => 'auth'], function () {
                Route::get('/notification', 'NotificationController@index')->name('notification.index');
                Route::post('/notification', 'NotificationController@reply')->name('notification.reply');
                Route::get('/notification/viewed', 'NotificationController@viewed')->name('notification.viewed');
                Route::get('/notification/view-status/{id}', 'NotificationController@changeViewStatus')->name('notification.view_status');
                Route::get('/notification/filtration', 'NotificationController@filtrationView')->name('notification.filtrationView');
                Route::post('/notification/filtration', 'NotificationController@filtration')->name('notification.filtration');
                Route::get('/notification/archive', 'NotificationController@archiveView')->name('notification.archiveView');
                Route::post('/notification/archive', 'NotificationController@archive')->name('notification.archive');
            });

            // users
            Route::group(['namespace' => 'User', 'middleware' => 'auth'], function () {
                Route::resource('/users', 'UserController')->middleware('auth');
                Route::get('/reset-password/{id?}', 'UserController@showResetPassword')->middleware('auth')->name('users.show_reset_password')->withoutMiddleware([EnsureTaxesShowReportOnly::class]);;
                Route::put('/reset-password/{id}', 'UserController@resetPassword')->middleware('auth')->name('users.reset_password')->withoutMiddleware([EnsureTaxesShowReportOnly::class]);;
                Route::get('/users/profile/{id}', 'UserController@getProfile')->name('users.profile')->middleware('auth');
            });

            // Payment
            Route::group(['namespace' => 'Payment', 'middleware' => 'auth'], function () {

                // Related
                Route::get('payment/related', 'PaymentsController@related')->name('payment.related.index');
                Route::post('payment/related', 'PaymentsController@get_related')->name('payment.related.get_data');

                Route::get('payment', 'PaymentsController@index')->name('payment.index');
                Route::get('payment/pagination/fetch_data', 'PaymentsController@fetch_data')->name('payments.pagination.fetch_data');
                Route::get('payment/{id}', 'PaymentsController@show')->name('payment.show');
                // Route::delete('payment/{payment}', 'PaymentsController@destroy')->name('payment.destroy');
                Route::post('payment/permanent-delete', 'PaymentsController@permanentDelete')->name('payment.permanent_delete');
                Route::get('payment/{payment}/edit', 'PaymentsController@edit')->name('payment.edit');
                Route::put('payment/{payment}', 'PaymentsController@update')->name('payment.update');

                // Purchase order
                Route::get('payment/purchaseorder/cashe_cheque-bankTransfer', 'PaymentsController@purchaseOrder_create_payment_cashe_cheque_or_bank')->name('payment.purchaseorder.cashe_cheque_bankTransfer');
                Route::get('payment/purchaseorder/deduction', 'PaymentsController@purchaseOrder_create_payment_deduction')->name('payment.purchaseorder.deduction');
                Route::post('payment/purchaseorder/cashe_cheque-bankTransfer', 'PaymentsController@purchaseOrder_store_payment_cashe_cheque_or_bank')->name('payment.purchaseorder.cashe_cheque_bankTransfer.store');
                Route::post('payment/purchaseorder/deduction', 'PaymentsController@purchaseOrder_store_payment_deduction')->name('payment.purchaseorder.deduction.store');
                Route::post('payment/purchaseorder/payment-details', 'PaymentsController@purchaseOrderPaymentDetails')->name('payment.purchaseorder.payment_details');

                // Document
                Route::get('payment/document/cashe_cheque-bankTransfer', 'PaymentsController@document_create_payment_cashe_cheque_or_bank')->name('payment.document.cashe_cheque_bankTransfer');
                Route::get('payment/document/deduction', 'PaymentsController@document_create_payment_deduction')->name('payment.document.deduction');
                Route::post('payment/document/cashe_cheque-bankTransfer', 'PaymentsController@document_store_payment_cashe_cheque_or_bank')->name('payment.document.cashe_cheque_bankTransfer.store');
                Route::post('payment/document/special', 'PaymentsController@document_store_payment')->name('payment.document.store');
                Route::get('payment/document/multi-document', 'PaymentsController@multi_document_payment')->name('payment.document.store.multi_document');

                // Payment file
                Route::post('/payment/fileStore', 'PaymentsController@storeFile')->name('poFile.store');

                Route::post('payment/document/deduction', 'PaymentsController@document_store_payment_deduction')->name('payment.document.deduction.store');
                Route::post('payment/document/payment-details', 'PaymentsController@documentPaymentDetails')->name('payment.document.payment_details');
                Route::delete('payment/{payment}', 'PaymentsController@destroy')->name('payment.destroy');

            });

            // Home
            Route::get('/', 'HomeController@index')->name('home');
            Route::get('/home', 'HomeController@index');


        });
    }
);

