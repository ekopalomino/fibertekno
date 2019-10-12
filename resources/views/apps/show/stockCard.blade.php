@extends('apps.layouts.main')
@section('content')
<script type="text/javascript">
    $(document).on('ajaxComplete ajaxReady ready', function () {
        $('ul.pagination li a').off('click').on('click', function (e) {
            $("#modalMd").modal('show');
            $('#modalMdContent').load($(this).attr('href'));
            $('#modalMdTitle').html($(this).attr('title'));
            e.preventDefault();
        });
    });
</script>
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover" id="sample_2">
				<thead>
                	<tr>
                        <th>No</th>
                		<th>Tgl Transaksi</th>
                        <th>Tipe</th>
                		<th>No Ref</th>
                		<th>Brg Masuk</th>
                		<th>Brg Keluar</th>
                		<th>Sisa Brg</th>
                        <th>Gudang</th>
                        <th>Catatan</th>
                	</tr>
                </thead>
                <tbody>
                	@foreach($data as $key=>$val)
                	<tr>
	                	<td>{{ $key+1 }}</td>
	                	<td>{{date("d F Y H:i",strtotime($val->created_at)) }}</td>
                        <td>
                            @if($val->type == 1)
                            Adjustment
                            @elseif($val->type == 2)
                            Penjualan
                            @elseif($val->type == 3)
                            Pembelian
                            @elseif($val->type == 4)
                            Internal Transfer
                            @elseif($val->type == 5)
                            Pengiriman
                            @elseif($val->type == 6)
                            Penerimaan Brg
                            @elseif($val->type == 7)
                            Manufaktur
                            @endif
                        </td>
	                	<td>{{ $val->reference_id}}</td>
	                	<td>{{ number_format($val->incoming,2,',','.')}}</td>
	                	<td>{{ number_format($val->outgoing,2,',','.')}}</td>
	                	<td>{{ number_format($val->remaining,2,',','.')}}</td>
                        <td>{{ $val->Locations->name }}</td>
                        <td>{{ $val->notes }}</td>
	                </tr>
                	@endforeach
                </tbody>
            </table>
            <div class="row">
				<div class="col-md-12 text-center">
					<nav aria-label="Page navigation">				
						{{ $data->links() }}
					</nav>
				</div>
			</div>          
		</div>
	</div>
</div>       
@endsection