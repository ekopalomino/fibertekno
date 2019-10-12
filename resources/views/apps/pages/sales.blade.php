@extends('apps.layouts.main')
@section('header.title')
FiberTekno | Sales Management
@endsection
@section('header.styles')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
	<div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-database"></i>Data Persediaan Barang 
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="sample_2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Harga Per Unit</th>
                                <th>Gudang Penyimpanan</th>
                                <th>Jumlah Stok</th>
                                <th>Status Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventories as $key => $val)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $val->Products->name }}</td>
                                <td>Rp {{ number_format($val->sale_price,2,',','.')}}</td>
                                <td>{{ $val->Locations->name }}</td>
                                <td>{{ number_format($val->closing_amount,2,',','.')}}</td>
                                <td>
                                    @if( ($val->closing_amount) == '0')
                                        <label class="badge badge-danger">No Stock</label>
                                    @elseif(($val->closing_amount) <= ($val->min_stock))
                                        <label class="badge badge-warning">Low On Stock</label>
                                    @elseif(($val->closing_amount) >= ($val->min_stock))
                                        <label class="badge badge-success">Stock Normal</label>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
		<div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-database"></i>Sales Order Data 
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{ route('sales.create') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold"> New Sales Order
                            </button></a>
                        </div>
                        @if (count($errors) > 0) 
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                        </div>
                        @endif
                    </div>
                	<table class="table table-striped table-bordered table-hover" id="sample_1">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>SO Ref</th>
                                <th>Customer</th>
                                <th>Jumlah Brg</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                				<th>Dibuat Oleh</th>
                                <th>Disetujui Oleh</th>
                				<th>Tgl Dibuat</th>
                				<th></th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($sales as $key => $sale)
                			<tr>
                				<td>{{ $key+1 }}</td>
                                <td>{{ $sale->order_ref }}</td>
                                <td>{{ $sale->Customers->name}}</td>
                                <td>{{ number_format($sale->quantity,2,',','.')}}</td>
                                <td>{{ number_format($sale->total,2,',','.')}}</td>
                                <td><label class="badge badge-success">{{ $sale->Statuses->name }}</label></td>
                                <td>{{ $sale->created_by }}</td>
                                <td>{{ $sale->updated_by }}</td>
                                <td>{{date("d F Y H:i",strtotime($sale->created_at)) }}</td>
                                <td>
                                    @if($sale->status_id != 'af0e1bc3-7acd-41b0-b926-5f54d2b6c8e8')
                                    <a class="btn btn-xs btn-info" title="Edit" href="{{ route('sales.show',$sale->id) }}"><i class="fa fa-search"></i></a>
                                    <a class="btn btn-xs btn-success modalMd" href="#" value="{{ action('Apps\SalesManagementController@salesStock',['id'=>$sale->id]) }}" title="Check Stock {{$sale->order_ref}}" data-toggle="modal" data-target="#modalMd"><i class="fa fa-edit"></i></a>
                                    {!! Form::open(['method' => 'POST','route' => ['sales.rejected', $sale->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Reject Sale']) !!}
                                    {!! Form::close() !!}
                                    @endif
                                </td>
                			</tr>
                            @endforeach
                		</tbody>
                	</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer.plugins')
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/table-datatables-buttons.min.js') }}" type="text/javascript"></script>
<script>
    function ConfirmAccept()
    {
    var x = confirm("Penjualan Akan Diproses?");
    if (x)
        return true;
    else
        return false;
    }
</script>
<script>
    function ConfirmDelete()
    {
    var x = confirm("Penjualan Akan Dibatalkan?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection