@extends('layouts.marketing')

@section('content')
	<div class="container text-center">
		<div class="template-page row">
			<h3>Select Template</h3>
			<div class=" col-md-3">
				<div class="templates col-md-12">
				<a href="{{url('email-marketing')}}?template=ecommerce">
					<img src="http://pageiz.com/dashboards/Images/template.png" class="img-responsive" >
				</a>	
					<h4>Ecommerce Template</h4>
				</div>
				
			</div>
			<div class=" col-md-3">
			<div class="templates col-md-12">
				<a href="{{url('email-marketing')}}?template=text">
				<img src="http://pageiz.com/dashboards/Images/template1.png" class="img-responsive">
				</a>
				<h4>Simple Text</h4>
			</div>
			</div>
			<div class=" col-md-3">
				<div class="templates col-md-12">
				<a href="{{url('email-marketing')}}?template=simple">
				<img src="http://pageiz.com/dashboards/Images/template2.png"  class="img-responsive">
				</a>
				<h4>Simple Template</h4>
				</div>
			</div>
			<div class=" col-md-3">
				<div class="templates col-md-12">
				<a href="{{url('email-marketing')}}?template=customize">
				<img src="http://pageiz.com/dashboards/Images/template3.png" class="img-responsive">
				</a>
				<h4>Customize Template</h4>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('pageScript')
	<script>
	$(document).ready(function(){
		$('nav.navbar.navbar-default.sidebar').css('top','0');
	});
	</script>
@endsection