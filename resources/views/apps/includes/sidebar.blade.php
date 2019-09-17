<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
			<li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <li class="nav-item {{ set_active('dashboard.index') }}">
                <a href="{{ route('dashboard.index') }}" class="nav-link">
                    <i class="icon-home"></i>
                    <span class="title">Beranda</span>
                    <span class="selected"></span>
                </a>
            </li>
            @can('Can Access Settings')
            <li class="nav-item {{ set_active(['warehouse.index','uom-cat.index','uom-val.index','pay-method.index','pay-term.index']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">Konfigurasi Umum</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="" class="nav-link">
                            <span class="title">Data Perusahaan</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['warehouse.index']) }}">
                        <a href="{{ route('warehouse.index') }}" class="nav-link">
                            <span class="title">Data Gudang</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['uom-cat.index','uom-val.index']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">Satuan Ukur</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item {{ set_active(['uom-cat.index']) }}">
                                <a href="{{ route('uom-cat.index') }}" class="nav-link ">
                                    <span class="title">Kategori</span>
                                </a>
                            </li>
                            <li class="nav-item {{ set_active(['uom-val.index']) }}">
                                <a href="{{ route('uom-val.index') }}" class="nav-link ">
                                    <span class="title">Nilai Konversi</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ set_active(['pay-method.index','pay-term.index']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">Kategori Pembayaran</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item {{ set_active(['pay-method.index']) }}">
                                <a href="{{ route('pay-method.index') }}" class="nav-link ">
                                    <span class="title">Metode</span>
                                </a>
                            </li>
                            <li class="nav-item {{ set_active(['pay-term.index']) }}">
                                <a href="{{ route('pay-term.index') }}" class="nav-link ">
                                    <span class="title">Jangka Waktu</span>
                                </a>
                            </li>
                        </ul>
                    </li>                                    
                </ul>
            </li>
            @endcan
            @can('Can Access Users')
            <li class="nav-item {{ set_active(['user.index','user.profile','role.index','uker.index','user.log']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">Manajemen User</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ set_active(['user.index','user.profile']) }}">
                        <a href="{{ route('user.index') }}" class="nav-link ">
                            <span class="title">Daftar User</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['role.index']) }}">
                        <a href="{{ route('role.index') }}" class="nav-link ">
                            <span class="title">Hak Akses</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['uker.index']) }}">
                        <a href="{{ route('uker.index') }}" class="nav-link ">
                            <span class="title">Unit Kerja</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['user.log']) }}">
                        <a href="{{ route('user.log') }}" class="nav-link ">
                            <span class="title">Log Aktivitas</span>
                        </a>
                    </li>                                    
                </ul>
            </li>
            @endcan
            @can('Can Access Products')
            <li class="nav-item {{ set_active(['product-cat.index','product.index','product.create','product.edit','product-bom.index','product-bom.create','product.show','product.barcode']) }}">
            	<a href="javascript:;" class="nav-link nav-toggle">
            		<i class="icon-social-dropbox"></i>
            		<span class="title">Produk</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                	<li class="nav-item {{ set_active(['product.index','product.create','product.edit','product-bom.index','product-bom.create','product.show']) }}">
                		<a href="{{ route('product.index') }}" class="nav-link ">
                            <span class="title">Data Produk</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['product-cat.index']) }}">
                		<a href="{{ route('product-cat.index') }}" class="nav-link ">
                            <span class="title">Kategori Produk</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['product.barcode']) }}">
                        <a href="{{ route('product.barcode') }}" class="nav-link ">
                            <span class="title">Produk Barcode</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('Can Access Contact')
            <li class="nav-item {{ set_active(['customer.index','customer.create','customer.edit','customer.show','supplier.index','supplier.create','supplier.edit']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user-follow"></i>
                    <span class="title">Kontak</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ set_active(['customer.index','customer.create','customer.edit','customer.show']) }}">
                        <a href="{{ route('customer.index') }}" class="nav-link ">
                            <span class="title">Data Pelanggan</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['supplier.index','supplier.create','supplier.edit']) }}">
                        <a href="{{ route('supplier.index') }}" class="nav-link ">
                            <span class="title">Data Pemasok</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('Can Access Sales')
            <li class="nav-item {{ set_active(['sales.index','sales.create','sales.show']) }}">
            	<a href="javascript:;" class="nav-link nav-toggle">
            		<i class="icon-present"></i>
            		<span class="title">Penjualan</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                	<li class="nav-item {{ set_active(['sales.index','sales.create','sales.show']) }}">
                		<a href="{{ route('sales.index') }}" class="nav-link ">
                            <span class="title">Sales Orders</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('Can Access Purchasing')
            <li class="nav-item {{ set_active(['request.create','purchase.index','request.form']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-basket-loaded"></i>
                    <span class="title">Pembelian</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ set_active(['purchase.index','request.form']) }}">
                        <a href="{{ route('purchase.index') }}" class="nav-link">
                            <span class="title">Purchase Orders</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('Can Access Inventories')
            <li class="nav-item  {{ set_active(['inventory.index','inventory.adjust','internal.transfer','receipt.index','delivery.index','inventory.card']) }}">
            	<a href="javascript:;" class="nav-link nav-toggle">
            		<i class="icon-grid"></i>
            		<span class="title">Persediaan</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                	<li class="nav-item  {{ set_active(['inventory.index','inventory.card']) }}">
                		<a href="{{ route('inventory.index') }}" class="nav-link ">
                            <span class="title">Stok Produk</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['inventory.adjust']) }}">
                		<a href="{{ route('inventory.adjust') }}" class="nav-link ">
                            <span class="title">Penyesuaian</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['internal.transfer','receipt.index','delivery.index']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">Pergerakan Stok</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item {{ set_active(['receipt.index']) }}">
                                <a href="{{ route('receipt.index') }}" class="nav-link ">
                                    <span class="title">Penerimaan</span>
                                </a>
                            </li>
                            <li class="nav-item {{ set_active(['delivery.index']) }}">
                                <a href="{{ route('delivery.index') }}" class="nav-link ">
                                    <span class="title">Pengiriman</span>
                                </a>
                            </li>
                            <li class="nav-item {{ set_active(['internal.transfer']) }}">
                                <a href="{{ route('internal.transfer') }}" class="nav-link ">
                                    <span class="title">Mutasi Internal</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endcan
            @can('Can Access Manufactures')
            <li class="nav-item {{ set_active(['manufacture.index']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-puzzle"></i>
                    <span class="title">Manufaktur</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ set_active(['manufacture.index']) }}">
                        <a href="{{ route('manufacture.index') }}" class="nav-link ">
                            <span class="title">Manufacture Orders</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('Can Access Finance')
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-calculator"></i>
                    <span class="title">Finance</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">Invoice</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item  ">
                                <a href="" class="nav-link ">
                                    <span class="title">Invoice Data</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="" class="nav-link ">
                                    <span class="title">Invoice Approval</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endcan
            @can('disable')
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-notebook"></i>
                    <span class="title">Accounting</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="ui_colors.html" class="nav-link ">
                            <span class="title">General Ledger</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="ui_colors.html" class="nav-link ">
                            <span class="title">Trial Balance</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-notebook"></i>
                    <span class="title">Employees</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="ui_colors.html" class="nav-link ">
                            <span class="title">Employee List</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">Employee Request</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Leave</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Permit</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Attendance</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item  ">
                        <a href="ui_colors.html" class="nav-link ">
                            <span class="title">Payroll</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Reports</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">Graphics</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Sales</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Inventory</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Purchasing</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item  ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">Tables</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Sales</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Inventory</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="ui_colors.html" class="nav-link ">
                                    <span class="title">Purchasing</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>