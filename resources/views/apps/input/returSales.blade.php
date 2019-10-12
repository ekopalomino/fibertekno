@extends('apps.layouts.main')
@section('content')
<div class="page-content">
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
	<div class="row">
		<div class="col-md-12">
            {!! Form::open(array('route' => 'retur.store','method'=>'POST')) !!}
            @csrf
            <table class="table table-striped table-bordered table-hover" id="sample_2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Pengiriman</th>
                        <th>Retur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $key=>$val)
                    <tr>
                        {{ Form::hidden('sales_id[]', $val->sales_id) }}
                        <td>{{ $key+1 }}</td>
                        <td>{{ $val->Products->name }}{{ Form::hidden('product_id[]', $val->product_id) }}</td>
                        <td>{!! Form::text('delivery[]', $val->quantity, array('placeholder' => 'Pengiriman','class' => 'form-control','readonly' => 'true')) !!}</td>
                        <td>{!! Form::text('retur[]', null, array('placeholder' => 'Retur','class' => 'form-control')) !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Tempat Penyimpanan</p>
            {!! Form::select('warehouse_id', [null=>'Please Select'] + $locations,[], array('class' => 'form-control')) !!}
            {{ Form::hidden('sales', $val->sales_id) }}
            <div class="modal-footer">
                <button type="close" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button id="register" type="submit" class="btn green">Save changes</button>
            </div>
            {!! Form::close() !!}
		</div>
	</div>
</div>       
@endsection
