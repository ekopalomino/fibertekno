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

Route::get('/', function () {
    return view('apps.pages.login');
});

Auth::routes();
Route::group(['prefix' => 'apps', 'middleware' => ['auth']], function() {
    Route::resource('dashboard','Apps\DashboardController');
    /*-----------------------User Management-----------------------------*/
    Route::get('users','Apps\UserManagementController@userIndex')->name('user.index');
    Route::post('users/create','Apps\UserManagementController@userStore')->name('user.store');
    Route::get('users/edit/{id}','Apps\UserManagementController@userEdit')->name('user.edit');
    Route::get('users/show/{id}','Apps\UserManagementController@userShow')->name('user.show');
    Route::post('users/update/{id}','Apps\UserManagementController@userUpdate')->name('user.update');
    Route::post('users/suspend/{id}','Apps\UserManagementController@userSuspend')->name('user.suspend');
    Route::post('users/delete/{id}','Apps\UserManagementController@userDestroy')->name('user.destroy');
    Route::get('users/profile', 'Apps\UserManagementController@userProfile')->name('user.profile');
    Route::post('users/profile/avatar', 'Apps\UserManagementController@updateAvatar')->name('user.avatar');
    Route::post('users/profile/password', 'Apps\UserManagementController@updatePassword')->name('user.password');
    Route::get('users/roles','Apps\UserManagementController@roleIndex')->name('role.index');
    Route::post('users/roles/create','Apps\UserManagementController@roleStore')->name('role.store');
    Route::get('users/roles/edit/{id}','Apps\UserManagementController@roleEdit')->name('role.edit');
    Route::get('users/roles/show/{id}','Apps\UserManagementController@roleShow')->name('role.show');
    Route::post('users/roles/update/{id}','Apps\UserManagementController@roleUpdate')->name('role.update');
    Route::post('users/roles/delete/{id}','Apps\UserManagementController@roleDestroy')->name('role.destroy');
    Route::get('users/unit-kerja','Apps\UserManagementController@ukerIndex')->name('uker.index');
    Route::post('users/unit-kerja/create','Apps\UserManagementController@ukerStore')->name('uker.store');
    Route::get('users/unit-kerja/edit/{id}','Apps\UserManagementController@ukerEdit')->name('uker.edit');
    Route::get('users/unit-kerja/show/{id}','Apps\UserManagementController@ukerShow')->name('uker.show');
    Route::post('users/unit-kerja/update/{id}','Apps\UserManagementController@ukerUpdate')->name('uker.update');
    Route::post('users/unit-kerja/delete/{id}','Apps\UserManagementController@ukerDestroy')->name('uker.destroy');
    Route::get('users/log-activities','Apps\LogActivityController@index')->name('user.log');
    /*-----------------------End User Management-----------------------------*/

    /*-----------------------Config Management-----------------------------*/
    Route::get('settings/warehouse','Apps\ConfigurationController@warehouseIndex')->name('warehouse.index');
    Route::post('settings/warehouse/create','Apps\ConfigurationController@warehouseStore')->name('warehouse.store');
    Route::get('settings/warehouse/edit/{id}','Apps\ConfigurationController@warehouseEdit')->name('warehouse.edit');
    Route::post('settings/warehouse/update/{id}','Apps\ConfigurationController@warehouseUpdate')->name('warehouse.update');
    Route::post('settings/warehouse/delete/{id}','Apps\ConfigurationController@warehouseDestroy')->name('warehouse.destroy');
    Route::get('settings/payment-methods','Apps\ConfigurationController@methodIndex')->name('pay-method.index');
    Route::post('settings/payment-methods/create','Apps\ConfigurationController@methodStore')->name('pay-method.store');
    Route::get('settings/payment-methods/edit/{id}','Apps\ConfigurationController@methodEdit')->name('pay-method.edit');
    Route::post('settings/payment-methods/update/{id}','Apps\ConfigurationController@methodUpdate')->name('pay-method.update');
    Route::post('settings/payment-methods/delete/{id}','Apps\ConfigurationController@methodDestroy')->name('pay-method.destroy');
    Route::get('settings/payment-terms','Apps\ConfigurationController@termIndex')->name('pay-term.index');
    Route::post('settings/payment-terms/create','Apps\ConfigurationController@termStore')->name('pay-term.store');
    Route::get('settings/payment-terms/edit/{id}','Apps\ConfigurationController@termEdit')->name('pay-term.edit');
    Route::post('settings/payment-terms/update/{id}','Apps\ConfigurationController@termUpdate')->name('pay-term.update');
    Route::post('settings/payment-terms/delete/{id}','Apps\ConfigurationController@termDestroy')->name('pay-term.destroy');
    Route::get('settings/uom-category','Apps\ConfigurationController@uomcatIndex')->name('uom-cat.index');
    Route::post('settings/uom-category/create','Apps\ConfigurationController@uomcatStore')->name('uom-cat.store');
    Route::get('settings/uom-category/edit/{id}','Apps\ConfigurationController@uomcatEdit')->name('uom-cat.edit');
    Route::post('settings/uom-category/update/{id}','Apps\ConfigurationController@uomcatUpdate')->name('uom-cat.update');
    Route::post('settings/uom-category/delete/{id}','Apps\ConfigurationController@uomcatDestroy')->name('uom-cat.destroy');
    Route::get('settings/uom-value','Apps\ConfigurationController@uomvalIndex')->name('uom-val.index');
    Route::post('settings/uom-value/create','Apps\ConfigurationController@uomvalStore')->name('uom-val.store');
    Route::get('settings/uom-value/edit/{id}','Apps\ConfigurationController@uomvalEdit')->name('uom-val.edit');
    Route::post('settings/uom-value/update/{id}','Apps\ConfigurationController@uomvalUpdate')->name('uom-val.update');
    Route::post('settings/uom-value/delete/{id}','Apps\ConfigurationController@uomvalDestroy')->name('uom-val.destroy');
    Route::get('settings/delivery-services','Apps\ConfigurationController@deliveryServiceIndex')->name('delivery-service.index');
    Route::post('settings/delivery-services/create','Apps\ConfigurationController@deliveryServiceStore')->name('delivery-service.store');
    Route::get('settings/delivery-service/edit/{id}','Apps\ConfigurationController@deliveryServiceEdit')->name('delivery-service.edit');
    Route::post('settings/delivery-service/update/{id}','Apps\ConfigurationController@deliveryServiceUpdate')->name('delivery-service.update');
    Route::post('settings/delivery-service/delete/{id}','Apps\ConfigurationController@deliveryServiceDelete')->name('delivery-service.destroy');

    /*-----------------------End Config Management-----------------------------*/

    /*-----------------------Product Management--------------------------------*/
    Route::get('products/categories','Apps\ProductManagementController@categoryIndex')->name('product-cat.index');
    Route::post('products/categories/create','Apps\ProductManagementController@categoryStore')->name('product-cat.store');
    Route::get('products/categories/edit/{id}','Apps\ProductManagementController@categoryEdit')->name('product-cat.edit');
    Route::post('products/categories/update/{id}','Apps\ProductManagementController@categoryUpdate')->name('product-cat.update');
    Route::post('products/categories/delete/{id}','Apps\ProductManagementController@categoryDestroy')->name('product-cat.destroy');
    Route::get('products','Apps\ProductManagementController@productIndex')->name('product.index');
    Route::get('products/create','Apps\ProductManagementController@productCreate')->name('product.create');
    Route::post('products/store','Apps\ProductManagementController@productStore')->name('product.store');
    Route::get('products/show/{id}','Apps\ProductManagementController@productShow')->name('product.show');
    Route::get('products/show/print/{id}','Apps\ProductManagementController@productPdf')->name('product.pdf');
    Route::get('products/barcode','Apps\ProductManagementController@productBarcode')->name('product.barcode');
    Route::get('products/barcode/pdf','Apps\ProductManagementController@barcodePdf')->name('barcode.pdf');
    Route::get('products/edit/{id}','Apps\ProductManagementController@productEdit')->name('product.edit');
    Route::post('products/update/{id}','Apps\ProductManagementController@productUpdate')->name('product.update');
    Route::post('products/delete/{id}','Apps\ProductManagementController@productDestroy')->name('product.destroy');
    Route::get('products/bom','Apps\ProductManagementController@indexBom')->name('product-bom.index');
    Route::get('products/bom/create/{id}','Apps\ProductManagementController@createBom')->name('product-bom.create');
    Route::post('products/bom/store','Apps\ProductManagementController@storeBom')->name('product-bom.store');
    Route::get('products/bom/edit/{id}','Apps\ProductManagementController@editBom')->name('product-bom.edit');
    Route::post('products/bom/update/{id}','Apps\ProductManagementController@updateBom')->name('product-bom.update');
    Route::post('products/bom/delete/{id}','Apps\ProductManagementController@destroyBom')->name('product-bom.destroy');
    /*-----------------------End Product Management--------------------------------*/

    /*-----------------------Contact Management------------------------------------*/
    Route::get('customers','Apps\ContactManagementController@customerIndex')->name('customer.index');
    Route::get('customers/create','Apps\ContactManagementController@customerCreate')->name('customer.create');
    Route::post('customers/store','Apps\ContactManagementController@customerStore')->name('customer.store');
    Route::get('customers/edit/{id}','Apps\ContactManagementController@customerEdit')->name('customer.edit');
    Route::get('customers/show/{id}','Apps\ContactManagementController@customerShow')->name('customer.show');
    Route::post('customers/update/{id}','Apps\ContactManagementController@customerUpdate')->name('customer.update');
    Route::post('customers/delete/{id}','Apps\ContactManagementController@customerDestroy')->name('customer.destroy');
    Route::get('suppliers','Apps\ContactManagementController@supplierIndex')->name('supplier.index');
    Route::get('suppliers/create','Apps\ContactManagementController@supplierCreate')->name('supplier.create');
    Route::post('suppliers/store','Apps\ContactManagementController@supplierStore')->name('supplier.store');
    Route::get('suppliers/show/{id}','Apps\ContactManagementController@supplierShow')->name('supplier.show');
    Route::get('suppliers/edit/{id}','Apps\ContactManagementController@supplierEdit')->name('supplier.edit');
    Route::post('suppliers/update/{id}','Apps\ContactManagementController@supplierUpdate')->name('supplier.update');
    Route::post('suppliers/delete/{id}','Apps\ContactManagementController@supplierDestroy')->name('supplier.destroy');

    /*-----------------------Sales Management------------------------------------*/
    Route::get('sales','Apps\SalesManagementController@index')->name('sales.index');
    Route::get('sales/orders/create','Apps\SalesManagementController@create')->name('sales.create');
    Route::get('sales/product/find','Apps\SalesManagementController@searchProduct')->name('sales.product');
    Route::post('sales/orders/store','Apps\SalesManagementController@storeSales')->name('sales.store');
    Route::get('sales/orders/check-stocks/{id}','Apps\SalesManagementController@salesStock')->name('sales.stocks');
    Route::post('sales/orders/approve/{id}','Apps\SalesManagementController@processSales')->name('sales.approve');
    Route::post('sales/orders/rejected/{id}','Apps\SalesManagementController@rejectedSale')->name('sales.rejected');
    Route::get('sales/orders/pdf/{id}','Apps\SalesManagementController@salesPrint')->name('sales.pdf');
	Route::get('sales/orders/show/{id}','Apps\SalesManagementController@salesShow')->name('sales.show');
    /*-----------------------End Sales Management------------------------------------*/

    /*-----------------------Purchase Management------------------------------------*/
    Route::get('purchase','Apps\PurchaseManagementController@index')->name('purchase.index');
    Route::get('purchase/request/create','Apps\PurchaseManagementController@requestCreate')->name('request.create');
    Route::get('purchase/product/find','Apps\PurchaseManagementController@searchProduct')->name('purchase.product');
    Route::post('purchase/request/store','Apps\PurchaseManagementController@requestStore')->name('request.store');
    Route::get('purchase/request/show/{id}','Apps\PurchaseManagementController@requestShow')->name('request.show');
    Route::get('purchase/request/print/{id}','Apps\PurchaseManagementController@requestPrint')->name('request.print');
    Route::get('purchase/request/edit/{id}','Apps\PurchaseManagementController@requestForm')->name('request.form');
    Route::post('purchase/request/approve/{id}','Apps\PurchaseManagementController@requestApprove')->name('request.approve');
    Route::get('purchase/order/show/{id}','Apps\PurchaseManagementController@purchaseShow')->name('purchase.show');
    Route::post('purchase/orders/rejected/{id}','Apps\PurchaseManagementController@requestRejected')->name('request.rejected');
    Route::get('purchase/orders/print/{id}','Apps\PurchaseManagementController@purchasePrint')->name('purchase.print');
    /*-----------------------End Purchase Management------------------------------------*/

    /*-----------------------Inventory Management------------------------------------*/
    Route::get('inventories','Apps\InventoryManagementController@inventoryIndex')->name('inventory.index');
    Route::post('inventories/initial-stock','Apps\InventoryManagementController@initialStock')->name('initial.stock');
    Route::get('inventories/stockcard/{id}','Apps\InventoryManagementController@stockCard')->name('inventory.card');
    Route::get('inventories/stockcard/print/{id}','Apps\InventoryManagementController@stockPrint')->name('stock.pdf');
    Route::get('inventory/adjustment','Apps\InventoryManagementController@inventoryAdjustIndex')->name('inventory.adjust');
    Route::get('inventory/adjustment/{id}','Apps\InventoryManagementController@makeAdjust')->name('make.adjust');
    Route::post('inventory/adjustment/store/{id}','Apps\InventoryManagementController@storeAdjust')->name('store.adjust');
    Route::get('inventories/internal-transfer','Apps\InventoryManagementController@internTransfer')->name('transfer.index');
    Route::get('inventories/internal-transfer/create','Apps\InventoryManagementController@addTransfer')->name('add.transfer');
    Route::post('inventories/internal-transfer/store','Apps\InventoryManagementController@internStore')->name('store.transfer');
    Route::post('inventories/internal-transfer/approve/{id}','Apps\InventoryManagementController@transferApprove')->name('transfer.approve');
    Route::post('inventories/internal-transfer/accept/{id}','Apps\InventoryManagementController@transferAccept')->name('transfer.accept');
    Route::get('inventories/internal-transfer/view/{id}','Apps\InventoryManagementController@transferView')->name('transfer.view');
    Route::get('inventories/purchase-receipt','Apps\InventoryManagementController@receiptIndex')->name('receipt.index');
    Route::post('inventories/purchase-receipt/store','Apps\InventoryManagementController@purchaseReceipt')->name('receipt.store');
    Route::get('inventories/delivery-order','Apps\InventoryManagementController@deliveryIndex')->name('delivery.index');
    Route::post('inventories/delivery-order/create','Apps\InventoryManagementController@deliveryOrder')->name('delivery.process');
    Route::post('inventories/delivery-order/delivered/{id}','Apps\InventoryManagementController@deliveryDone')->name('delivery.done');
    Route::get('inventories/delivery-order/print/{id}','Apps\InventoryManagementController@deliveryPrint')->name('delivery.print');
    /*-----------------------End Inventory Management------------------------------------*/

    /*-----------------------Manufacture Management------------------------------------*/
    Route::get('manufactures/request','Apps\ManufactureManagementController@requestIndex')->name('manufacture-request.index');
    Route::get('manufactures/request/create','Apps\ManufactureManagementController@createRequest')->name('manufacture-request.create');
    Route::get('manufactures/request/product/find','Apps\ManufactureManagementController@manufactureProduct')->name('manufacture-request.product');
    Route::post('manufactures/request/store','Apps\ManufactureManagementController@storeRequest')->name('manufacture-request.store');
    Route::get('manufactures/request/store/{id}','Apps\ManufactureManagementController@checkStock')->name('manufacture-request.check');
    Route::post('manufactures/request/approve/{id}','Apps\ManufactureManagementController@approveRequest')->name('manufacture-request.approve');
    Route::get('manufactures','Apps\ManufactureManagementController@index')->name('manufacture.index');
    Route::post('manufactures/order/process/{id}','Apps\ManufactureManagementController@makeManufacture')->name('manufacture.process');
    Route::get('manufactures/order/done/{id}','Apps\ManufactureManagementController@manufactureDone')->name('manufacture.done');
    Route::post('manufactures/order/complete','Apps\ManufactureManagementController@process')->name('manufacture.complete');
    Route::get('manufactures/order/show/{id}','Apps\ManufactureManagementController@manufactureShow')->name('manufacture.show');
    Route::get('manufactures/order/transfer/{id}','Apps\ManufactureManagementController@manufactureTransfer')->name('manufacture.transfer');
    Route::post('manufactures/order/transfer/process/{id}','Apps\ManufactureManagementController@transferProcess')->name('transfer.process');
    /*-----------------------End Manufacture Management------------------------------------*/

    /*-----------------------Reports Management------------------------------------*/
    Route::get('reports/table/sales','Apps\ReportManagementController@saleTable')->name('sale.table');
    Route::post('reports/table/sales/view','Apps\ReportManagementController@reportSales')->name('sale-table.view');
    Route::get('reports/table/inventory','Apps\ReportManagementController@inventoryTable')->name('inventory.table');
    Route::post('reports/table/inventory/view','Apps\ReportManagementController@reportInventory')->name('inventory-table.view');
    Route::get('reports/table/purchase','Apps\ReportManagementController@purchaseTable')->name('purchase.table');
    /*-----------------------End Reports Management------------------------------------*/

    /*-----------------------Finance Management------------------------------------*/
    Route::get('invoices/index','Apps\InvoiceManagementController@index')->name('invoice.index');
    Route::post('invoices/create','Apps\InvoiceManagementController@invoiceStore')->name('invoice.store');
    Route::post('invoices/payment-receive/{id}','Apps\InvoiceManagementController@invoicePayment')->name('invoice.payment');
    Route::get('invoices/show/{id}','Apps\InvoiceManagementController@invoiceShow')->name('invoice.show');
    Route::get('invoices/print/{id}','Apps\InvoiceManagementController@invoicePrint')->name('invoice.print');
    /*-----------------------End Finance Management------------------------------------*/

    /*-----------------------Return Product Management------------------------------------*/
    Route::get('inventories/return-products','Apps\InventoryManagementController@returIndex')->name('retur.index');
    Route::get('inventories/return-products/{id}','Apps\InventoryManagementController@returForm')->name('retur.form');
    Route::post('inventories/return-products/store','Apps\InventoryManagementController@returStore')->name('retur.store');
});
