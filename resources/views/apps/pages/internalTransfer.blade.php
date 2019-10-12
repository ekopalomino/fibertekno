@extends('apps.layouts.main')
@section('header.title')
FiberTekno | Mutasi Barang
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
                        <i class="fa fa-database"></i>Data Mutasi Internal 
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{ route('add.transfer') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold"> Buat Mutasi
                            </button></a>
                        </div>
                    </div>
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>No Mutasi</th>
                                <th>Gudang Asal</th>
                                <th>Gudang Tujuan</th>
                                <th>Tgl Dibuat</th>
                                <th>Tgl Diterima</th>
                                <th>Status</th>
                                <th>Pengirim</th>
                                <th>Penerima</th>
                                <th></th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key=>$val)
                            <tr>      
                                <td>{{ $key+1 }}</td>
                                <td>{{ $val->order_ref }}</td>
                                <td>{{ $val->From->name }}</td>
                                <td>{{ $val->To->name }}</td>
                                <td>{{date("d F Y H:i",strtotime($val->created_at)) }}</td>
                                <td>
                                    @if($val->updated_at != $val->created_at)
                                    {{date("d F Y H:i",strtotime($val->updated_at)) }}
                                    @endif
                                </td>
                                <td>
                                    @if( ($val->status_id) == 'ffa20f52-a023-4333-b945-a46d04de961c')
                                    <label class="badge badge-danger">{{ $val->Statuses->name }}</label>
                                    @elseif(($val->status_id) == '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43')
                                    <label class="badge badge-success">{{ $val->Statuses->name }}</label>
                                    @endif
                                </td>
                                <td>{{ $val->created_by }}</td>
                                <td>
                                    @if(!empty($val->updated_by))
                                    {{ $val->updated_by }}
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-info modalMd" href="#" value="{{ action('Apps\InventoryManagementController@transferView',['id'=>$val->id]) }}" title="Detail Mutasi" data-toggle="modal" data-target="#modalMd"><i class="fa fa-search"></i></a>
                                    {!! Form::open(['method' => 'POST','route' => ['transfer.approve', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmApprove()']) !!}
                                    {!! Form::button('<i class="fa fa-check"></i>',['type'=>'submit','class' => 'btn btn-xs btn-success','title'=>'Setuju Mutasi']) !!}
                                    {!! Form::close() !!}
                                    @if(Auth::user()->warehouse_id == $val->to_id)
                                    {!! Form::open(['method' => 'POST','route' => ['transfer.accept', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmReceive()']) !!}
                                    {!! Form::button('<i class="fa fa-check"></i>',['type'=>'submit','class' => 'btn btn-xs btn-success','title'=>'Terima Mutasi']) !!}
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
    function ConfirmReceive()
    {
    var x = confirm("Mutasi Barang Diterima?");
    if (x)
        return true;
    else
        return false;
    }
</script>
<script>
    function ConfirmApprove()
    {
    var x = confirm("Mutasi Barang Disetujui?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection