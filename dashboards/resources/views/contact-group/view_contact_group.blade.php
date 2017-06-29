
@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<div class="container" style="padding-left:40px;">
	<div class="row">
		<div class="col-md-10">
			<div style="margin-bottom:20px;">
				<h4>Group Name: <span style="font-size: 14px;">{{$cg->name}}</span> </h4>
				<h4>Group Description: <span style="font-size: 14px;">{{$cg->description}}</span> </h4>
			</div>
			<hr>
			<h3>{{ $cg->name }}'s Members</h3>
			@if(isset($cig_id))
			<table id="example" class="table table-responsive" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Action</th>
					</tr>
				</thead>
				<tfoot>
				<?php $count = 1; ?>
				
				</tfoot>
				<tbody>
					@foreach($cig_id as $cg)
					
						<tr id="row_{{$cg->sub_id}}">
							<td>{{$count++}}</td>
							<td>{{$cg->full_name}}</td>
							<td>{{$cg->email}}</td>
							<td>{{$cg->phone}}</td>
							<td>
								<a onclick="doDelete({{$cg->sub_id}},{{$cg->cg_id}})" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Remove</a>
							</td>
						</tr>
					@endforeach	
				</tbody>
			</table>
			@endif
		</div>
	</div>
</div>
@endsection
@section('pageScript')

	<script>
		$(document).ready(function() {
			 $('#example').DataTable();
		});
		function doDelete(id,cg_id){
			var url = '{{url('remove-contact')}}';
			// alert(url);
			$.ajax({
			type    : 'POST',
			data : {
			id : id,
			cg_id: cg_id
			},
			async:false,
			url: url,
			beforeSend: function(request) {
			return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
			},
			success: function(response) {
				
				if(response == 'success'){
					$("#row_"+id).remove();
					swal("Success!", "Subscriber has been removed from group!", "success")
				}
			
			},
			error:function(error) {
				alert("failed");
			}
			});
		}
		
	</script>
@endsection 