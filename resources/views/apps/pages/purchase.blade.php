@extends('apps.layouts.main')
@section('header.title')
FiberTekno | Permintaan Eksternal
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
                        <i class="fa fa-database"></i>Data Permintaan Eksternal 
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{ route('request.create') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold">Permintaan Baru
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
                				<th>Ref No</th>
                                <th>Nama Supplier</th>
                                <th>Total Harga</th>
                				<th>Diminta Oleh</th>
                                <th>Disetujui Oleh</th>
                				<th>Tgl Dibuat</th>
                                <th>Status</th>
                				<th></th>
                			</tr>
                		</thead> 
                		<tbody>
                            @foreach($data as $key => $val)
                			<tr>
                				<td>{{ $key+1 }}</td>
                                <td>{{ $val->order_ref }}</td>
                                <td>{{ $val->Suppliers->name}}</td>
                                <td>{{ number_format($val->total,2,',','.')}}</td>
                                <td>{{ $val->created_by }}</td>
                                <td>{{ $val->updated_by }}</td>
                                <td>{{date("d F Y H:i",strtotime($val->created_at)) }}</td>
                                <td>
                                    @if(($val->status) == '458410e7-384d-47bc-bdbe-02115adc4449')
                                    <label class="badge badge-success">{{ $val->Statuses->name }}</label>
                                    @elseif(($val->status) == '8083f49e-f0aa-4094-894f-f64cd2e9e4e9')
                                    <label class="badge badge-warning">{{ $val->Statuses->name }}</label>
                                    @elseif(($val->status) == '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43')
                                    <label class="badge badge-info">{{ $val->Statuses->name }}</label>
                                    @else
                                    <label class="badge badge-danger">{{ $val->Statuses->name }}</label>
                                    @endif
                                </td>
                                <td>
                                    @if(($val->status) == '8083f49e-f0aa-4094-894f-f64cd2e9e4e9')
                                    <a class="btn btn-xs btn-info" title="Lihat PR" href="{{ route('request.show',$val->id) }}"><i class="fa fa-search"></i></a>
                                    {!! Form::open(['method' => 'POST','route' => ['request.approve', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmAccept()']) !!}
                                    {!! Form::button('<i class="fa fa-check"></i>',['type'=>'submit','class' => 'btn btn-xs btn-success','title'=>'Approve PR']) !!}
                                    {!! Form::close() !!}
                                    {!! Form::open(['method' => 'POST','route' => ['request.rejected', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Tolak PR']) !!}
                                    {!! Form::close() !!}
                                    @endif
                                    @if(($val->status) == '458410e7-384d-47bc-bdbe-02115adc4449')
                                    <a class="btn btn-xs btn-info" title="Lihat PO" href="{{ route('purchase.show',$val->id) }}"><i class="fa fa-search"></i></a>
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
    function ConfirmDelete()
    {
    var x = confirm("Pengajuan Akan Dibatalkan?");
    if (x)
        return true;
    else
        return false;
    }
</script>
<script>
    function ConfirmAccept()
    {
    var x = confirm("Pengajuan Akan Diproses?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection