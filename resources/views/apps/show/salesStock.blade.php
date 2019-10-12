@extends('apps.layouts.main')
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover" id="sample_2">
				<thead>
                	<tr>
                		<th>No</th>
                		<th>Produk</th>
                		<th>Permintaan</th>
                        <th>Tersedia</th>
                		<th>Satuan</th>
                        <th>Manufaktur</th>
                        <th>Status</th>
                	</tr>
                </thead>
                <tbody> 
                	@foreach($details as $key=>$val)
                	<tr>
                        <td>{{ $key+1 }}</td>
	                	<td>{{ $val->Products->name }}</td>                                                  
	                	<td>{{ number_format($val->quantity,0,',','.')}}</td>
	                	<td>{{ number_format($val->closing_amount,2,',','.')}}</td>
                        <td>{{ $val->Uoms->name}}</td>
                        <td>
                            @if($val->is_manufacture == 1)
                            <label class="badge badge-success">Ya</label>
                            @else
                            <label class="badge badge-danger">Tidak</label>
                            @endif
                        </td>
	                	<td>
                            @if(($val->quantity) > ($val->closing_amount))
                            <label class="badge badge-danger">Stok Tidak Cukup</label>
                            @else      
                            <label class="badge badge-success">Stok Cukup</label>
                            @endif
                        </td>
	                </tr>
                	@endforeach
                </tbody>
            </table>
            @if(($val->quantity) > ($val->closing_amount))
            @else
            {!! Form::open(['method' => 'POST','route' => ['sales.approve', $val->sales_id],'style'=>'display:inline','onsubmit' => 'return ConfirmAccept()']) !!}
            {!! Form::button('<i class="fa fa-check"></i>',['type'=>'submit','class' => 'btn btn-xs btn-success','title'=>'Approve Sale']) !!}
            {!! Form::close() !!}
            @endif  
		</div>
	</div>
</div>       
@endsection