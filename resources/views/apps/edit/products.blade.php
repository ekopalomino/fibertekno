@extends('apps.layouts.main')
@section('header.title')
Fiber Tekno | Edit Product
@endsection
@section('content')
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> Edit Product Form 
            </div>
        </div>
        <div class="portlet-body form">
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
            {!! Form::model($data, ['method' => 'POST','route' => ['product.update', $data->id],'class' => 'form-horizontal','files' => 'true']) !!}
                @csrf
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Barcode</label>
                        <div class="col-md-4">
                            {!! Form::text('product_barcode', null, array('placeholder' => 'Product Barcode','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Name</label>
                        <div class="col-md-4">
                            {!! Form::text('name', null, array('placeholder' => 'Product Name','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Category</label>
                        <div class="col-md-4">
                            {!! Form::select('category_id', $categories,old('category_id'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Supplier</label>
                        <div class="col-md-4">
                            {!! Form::select('supplier_id', $vendors,old('supplier_id'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product UOM</label>
                        <div class="col-md-4">
                            {!! Form::select('uom_id', $uoms,old('uom_id'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Minimal Stock</label>
                        <div class="col-md-4">
                            {!! Form::text('min_stock', null, array('placeholder' => 'Product Minimal Stock','class' => 'form-control')) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Cost Price</label>
                        <div class="col-md-4">
                            {!! Form::text('base_price', null, array('placeholder' => 'Product Cost Price','class' => 'form-control')) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Sale Price</label>
                        <div class="col-md-4">
                            {!! Form::text('sale_price', null, array('placeholder' => 'Product Sale Price','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Image</label>
                        <div class="col-md-4">
                            {!! Form::file('image', null, array('placeholder' => 'Product Image','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Produk Manufaktur</label>
                        <div class="col-md-4">
                            {!! Form::checkbox('is_manufacture') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Produk Bisa Dijual Terpisah</label>
                        <div class="col-md-4">
                            {!! Form::checkbox('is_sale') !!}
                        </div>
                    </div>
                    <div class="form-actions right">
                        <a button type="button" class="btn default" href="{{ route('product.index') }}">Cancel</a>
                        <button type="submit" class="btn blue">
                        <i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection