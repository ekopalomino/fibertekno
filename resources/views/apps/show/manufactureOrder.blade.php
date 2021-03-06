@extends('apps.layouts.main')
@section('header.title')
FiberTekno | Manufacture Order Detail
@endsection
@section('header.plugin')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light portlet-fit portlet-datatable bordered">
				<div class="portlet-title">
					<div class="caption">
						
                    </div>
                    <div class="actions">
                        <div class="btn-group">
                            <a href=""><button id="sample_editable_1_new" class="btn red btn-outline sbold">Print MO</button></a>
                            <a href="{{ url()->previous() }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold">Tutup</button></a>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                	<div class="row">
                		<div class="col-md-6 col-sm-12">
                			<div>
                				<div class="portlet-title">
                                    <div class="caption">
                                        <img src="{{ asset('assets/fibertekno.jpg') }}" alt="" />
                                        <p style="line-height: 1;"><strong>PT. FIBER TEKNOLOGI INDONESIA</strong></p> 
                                        <p style="line-height: 1;">Kirana Two Office Tower Lt. 10A</p>
                                        <p style="line-height: 1;">Jl. Boulevard Timur No.88 </p>
                                        <p style="line-height: 1;">Jakarta Utara 14250</p>
                                        <p style="line-height: 1;">email : sales@fibertekno.co.id</p>
                                        <p style="line-height: 1;">Phone : 021 - 21484090</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div>
                                <div class="portlet-title">
                                    <div class="caption">
                                        <h2 align="right"><strong>MANUFACTURE ORDER</strong></h2>
                                        
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No MO</th>
                                                <th>Tanggal MO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$data->order_ref}}</td>
                                                <td>{{date("d F Y",strtotime($data->created_at)) }}</td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                    <table class="table table-bordered table-hover" style="width: 50%;background:#4B77BE;margin-top: -10px;">
                                        <thead>
                                            
                                        </thead>
                                    </table>
                                    <p style="line-height: 1;"><strong></strong></p>
                                    <p style="line-height: 1;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>KETERANGAN NAMA BARANG/JASA</th>
                                        <th>JML</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data->product_name }}</td>
                                        <td>{{ number_format($data->man_plan,0,',','.')}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            
                                        </td>
                                        <td colspan="4">
                                            <p style="line-height: 1;" align="center">PT. FIBER TEKNOLOGI INOVASI</p>
                                            <p align="center">
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                (DIAN)
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
<script src="{{ asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/ecommerce-orders-view.min.js') }}" type="text/javascript"></script>
@endsection