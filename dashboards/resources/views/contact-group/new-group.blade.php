@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div class="container" style="padding-left:20px;">
	
    <div class="col-md-8 ">
	<form action="{{url('post-contact-group')}}" method="POST">
	{{ csrf_field() }}
	@if (Session::has('message'))
	   <div class="alert alert-success" style="    margin: 12px 125px;">{{ Session::get('message') }}</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger"  style="    margin: 12px 125px;">{{ Session::get('error') }}</div>
	@endif
        <div class="col-md-10">
		<h3>New Contact Group</h3>
			<div class="form-group">
				<label for="pwd">Name*:</label>
				<input type="text" placeholder="Group Name" class="form-control" id="name" name="name" required>
			</div>
			<div class="form-group">
				<label for="pwd">Description:</label>
				<textarea placeholder="Group Description" class="form-control" id="description" name="description"></textarea>
			</div>
			  <button type="submit" class="btn btn-success">Create New Contact</button>
        </div>
    </div>
</div>
@endsection
