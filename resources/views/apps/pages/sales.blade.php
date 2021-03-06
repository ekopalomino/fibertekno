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
                                <td>{{ $val->product_name }}</td>
                                <td>Rp {{ number_format($val->sale_price,2,',','.')}}</td>
                                <td>{{ $val->warehouse_name }}</td>
                                <td>{{ number_format($val->closing_amount,2,',','.')}}</td>
                                <td>
                                    @if( ($val->closing_amount) == '0')
                                        <label class="label label-sm label-danger">No Stock</label>
                                    @elseif(($val->closing_amount) <= ($val->min_stock))
                                        <label class="label label-sm label-warning">Low On Stock</label>
                                    @elseif(($val->closing_amount) >= ($val->min_stock))
                                        <label class="label label-sm label-success  ">Stock Normal</label>
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
                        @can('Can Create Sales')
                        @if(!$inventories->isEmpty())
                        <div class="form-group">
                            <a href="{{ route('sales.create') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold"> New Sales Order
                            </button></a>
                        </div>
                        @endif
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
                        @endcan
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
                				<th>Tgl Pesanan</th>
                                <th>Tgl Selesai</th>
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
                                <td>
                                    @if( ($sale->status_id) == '8083f49e-f0aa-4094-894f-f64cd2e9e4e9')
                                    <label class="label label-sm label-warning">{{ $sale->Statuses->name }}</label>
                                    @elseif( ($sale->status_id) == '805ec360-ebe1-4872-9798-a69dbac86a29')
                                    <label class="label label-sm label-success">{{ $sale->Statuses->name }}</label>
                                    @elseif( ($sale->status_id) == 'af0e1bc3-7acd-41b0-b926-5f54d2b6c8e8')
                                    <label class="label label-sm label-danger">{{ $sale->Statuses->name }}</label>
                                    @elseif( ($sale->status_id) == 'c2fdba02-e765-4ee8-8c8c-3073209ddd26')
                                    <label class="label label-sm label-warning">{{ $sale->Statuses->name }}</label>
                                    @elseif( ($sale->status_id) == 'eca81b8f-bfb9-48b9-8e8d-86f4517bc129')
                                    <label class="label label-sm label-info">{{ $sale->Statuses->name }}</label>
                                    @elseif( ($sale->status_id) == 'c2fdba02-e765-4ee8-8c8c-3073209ddd26')
                                    <label class="label label-sm label-warnng">{{ $sale->Statuses->name }}</label>
                                    @elseif( ($sale->status_id) == '6d32841b-2606-43a5-8cf7-b77291ddbfbb')
                                    <label class="label label-sm label-success">{{ $sale->Statuses->name }}</label>
                                    @elseif( ($sale->status_id) == '8447cd63-c7e7-4b26-81fc-d2eb3aceec97')
                                    <label class="label label-sm label-danger">{{ $sale->Statuses->name }}</label>
                                    @elseif( ($sale->status_id) == '458410e7-384d-47bc-bdbe-02115adc4449')
                                    <label class="label label-sm label-success">{{ $sale->Statuses->name }}</label>
                                    @endif
                                </td>
                                <td>{{date("d F Y H:i",strtotime($sale->sale_date)) }}</td>
                                <td>
                                    @if(!empty($sale->closing_date))
                                    {{date("d F Y H:i",strtotime($sale->closing_date)) }}
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-info" title="Lihat PO" href="{{ route('sales.show',$sale->id) }}"><i class="fa fa-search"></i></a>
                                    @if(($sale->status_id) == '8083f49e-f0aa-4094-894f-f64cd2e9e4e9')
                                    @can('Can Edit Sales')
                                    <a class="btn btn-xs btn-info" title="Edit" href="{{ route('sales.edit',$sale->id) }}"><i class="fa fa-edit"></i></a>
                                    @endcan
                                    @can('Can Accept Sales')
                                    {!! Form::open(['method' => 'POST','route' => ['sales.approve', $sale->id],'style'=>'display:inline','onsubmit' => 'return ConfirmAccept()']) !!}
                                    {!! Form::button('<i class="fa fa-check"></i>',['type'=>'submit','class' => 'btn btn-xs btn-success','title'=>'Approve Sale']) !!}
                                    {!! Form::close() !!}
                                    {!! Form::open(['method' => 'POST','route' => ['sales.rejected', $sale->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-remove"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Cancel Sale']) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                    @endif
                                    @if(($sale->status_id) == '805ec360-ebe1-4872-9798-a69dbac86a29')
                                    {!! Form::open(['method' => 'POST','route' => ['sales.close', $sale->id],'style'=>'display:inline','onsubmit' => 'return ConfirmClose()']) !!}
                                    {!! Form::button('<i class="fa fa-lock"></i>',['type'=>'submit','class' => 'btn btn-xs btn-success','title'=>'Close Sale']) !!}
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
<script>
    function ConfirmClose()
    {
    var x = confirm("Penjualan Akan Ditutup?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection