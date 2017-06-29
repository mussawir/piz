@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div class="container" style="padding-left:20px;">
	
    <div class="row" id="">
	<form action="{{url('new-contact')}}" method="POST" id="form_contact">
	{{ csrf_field() }}
	@if (Session::has('message'))
	   <div class="alert alert-success" style="    margin: 12px 125px;">{{ Session::get('message') }}</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger"  style="    margin: 12px 125px;">{{ Session::get('error') }}</div>
	@endif
        <div class="col-md-6">
		<div id="contact_section"></div>
		<h3>New Contact</h3>
			<div class="form-group">
				<label for="email">Email address:</label>
				<input placeholder="Email" type="email" class="form-control" id="email" name="email" required>
			</div>
			<div class="form-group">
				<label for="pwd">Full Name:</label>
				<input placeholder="Name" type="text" class="form-control" id="name" name="name" required>
			</div>
			<div class="form-group">
				<label for="pwd">Phone:</label>
				<input placeholder="Phone" type="number" class="form-control" id="phone" name="phone" >
			</div>
			  <button type="submit" class="btn btn-success">Create New Contact</button>
        </div>
		<div class="col-md-4">
		<h3>Select Contact Group</h3>
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>Name</th>
						<th>Check to select</th>
					</tr>
				</thead>
				<tfoot>
				<?php $count = 1; ?>
				
				</tfoot>
				<tbody>
					@foreach($cg as $sub)
						<tr>
							<th>{{$count++}}</th>
							<th>{{$sub->name}}</th>
							<th>
								<input type="checkbox" value="{{$sub->cg_id}}" name="cg_id[]" id="cg_id">
							</th>
						</tr>
					@endforeach	
				</form>
				</tbody>
			</table>
        </div>
		</form>
    </div>
</div>
@endsection
@section('pageScript')
	<script>
		$(document).ready(function() {
		
		$('#example').DataTable();
		$("#form_contact").submit(function(e) {

			//prevent Default functionality
			

			if ($("#form_contact input:checkbox:checked").length > 0)
			{
				// any one is checked
				// alert('one checkbox has checked');
				
			}
			else
			{
				e.preventDefault();
			   $('#contact_section').html('<div class="alert alert-danger">Please select one group.</div>').delay(400).fadeOut();
			}

		});
		
} );
	</script>
@endsection