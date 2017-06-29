@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div class="container">
    <div class="row">
	{{ csrf_field() }}
		<div class="col-md-10">
			<h3 class="pull-left">Contact Groups</h3>
			<a class="btn btn-primary pull-right" href="{{url('new-contact-group')}}" style="margin-bottom:20px;"><i class="fa fa-plus"></i> New Contact Group</a>
		</div>
        <div class="col-md-10">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Description</th>
				<th>Action</th>
            </tr>
        </thead>
        <tfoot>
		<?php $count = 1; ?>
		
        </tfoot>
        <tbody>
            @foreach($group as $cg)
			
				<tr>
					<th>{{$count++}}</th>
					<th>{{$cg->name}}</th>
					<th>{{$cg->description}}</th>
					<th>
						<a href="{{url('view-contact-group').'/'.$cg->cg_id}}" class="btn btn-info btn-xs">
							Show
						</a>
					</th>
				</tr>
			@endforeach	
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
} );
	</script>
@endsection