@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div class="container">
    <div class="row">
	<form action="{{url('selected')}}" method="POST" id="selected_members">
	<a class="btn btn-primary pull-right" href="{{url('csv')}}" style="margin-bottom:20px;">Export All as CSV</a>
	<button type="submit" class="btn btn-primary  pull-right" style="margin-right:10px;">Export Selected as CSV</button>
	<a class="btn btn-primary pull-right" href="{{url('contact')}}" style="margin-right:10px;margin-bottom:20px;"><i class="fa fa-plus"></i> Add New Contact</a>
	{{ csrf_field() }}
        <div class="col-md-10">
		
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
				<th>Check to Export</th>
            </tr>
        </thead>
        <tfoot>
		<?php $count = 1; ?>
		
        </tfoot>
        <tbody>
            @foreach($subscribers as $sub)
			
				<tr>
					<th>{{$count++}}</th>
					<th>{{$sub->full_name}}</th>
					<th>{{$sub->email}}</th>
					<th>{{$sub->phone}}</th>
					<th><input type="checkbox" value="{{$sub->email}}" name="email[]"></th>
				</tr>
			@endforeach	
        </form>
        </tbody>
    </table>
        </div>
    </div>
</div>
@endsection
@section('pageScript')
	<script>
		$(document).ready(function() {
		
		$('#example').DataTable();
		$('#selected_members').submit(function(e){
			
			if ($("#selected_members input:checkbox:checked").length > 0)
			{
				// any one is checked
				// alert('one checkbox has checked');
				
			}
			else
			{
				e.preventDefault();
				// sweetAlert("Oops...", "Please select atleast one subscriber to export!", "error");
				swal({
				  title: "Oops...",
				  type: "error",
				  text: "Please select atleast one subscriber to export!",
				  timer: 3000,
				  showConfirmButton: true
				});
			}
		});
} );
	</script>
@endsection