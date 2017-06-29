<div class="test-fix">
					<div class="container-fluid" style="background:#4e4747;">
						<div class="container" style="padding: 15px;">
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-3">
										<img id="logo-image" class="img-responsive" src="{{ URL::asset('Images/LOGO/logo-png.png') }}" />
										<button onclick="$(this).children('i').toggleClass('fa-rotate-90');" id="menu-btn" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-default-toggler">
											<i class="fa fa-bars" aria-hidden="true"></i>
										</button>
									</div>
									<div class="col-md-4 padding-between">
										<div id="imaginary_container"> 
											<div class="input-group stylish-input-group">
											<form action="http://pifastore.my/dev/product-public-list" method="get" id="searchForm">
												<input name="q" id="q" type="text" class="form-control"  placeholder="Search for products , brands , categories , suppliers" />
												<span class="input-group-addon">
													<button type="submit" style="color: white;">
														<span class="glyphicon glyphicon-search"></span>
													</button>  
												</span>
											</form>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="col-md-2 col-xs-4 padding-between">
												<span><i style="font-size:30px" class="fa fa-shopping-cart icon-hover cart-dropdown-hover" aria-hidden="true">
												<div class="cart-dropdown-div">
										<ul class="cart-dropdown">
										<?php print isset($cartData)?$cartData:''; ?>
										<li class="last-checkout-btn">
										<button style="width: 100%;" class="btn btn-md btn-success" onclick="Redirect('customers/cart')">Checkout</button>
										</li>
										</ul>
										</div>
												</i></span>
												<span class="label" id="notification-count">{{isset($productCount)?$productCount:0}}</span>
										</div>
										<div class="col-md-10">
											<!--<div class="col-md-12">-->
												<div class="col-md-6 col-xs-4 padding-between">
													<span><i style="font-size:30px" class="fa fa-shield icon-hover" aria-hidden="true"></i></span>
													<span style="color:white;font-size:12px;position:relative;bottom:6px; left:3px;">100% Buyer Protection</span>
													
												</div>
												<div class="col-md-6 col-xs-4 padding-between">
													<span><i style="font-size:30px" class="fa fa-money icon-hover" aria-hidden="true"></i></span>
													<span style="color:white;font-size:12px;position:relative;bottom:6px; left:3px;">Cash On Delivery</span>
												</div>
												
											<!--</div>-->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<nav id="navbar-default-toggler" class="navbar navbar-default">
					<div class="container">
    <div class="navbar-header">
    	<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	
	<div class="collapse navbar-collapse js-navbar-collapse">
		<ul class="nav navbar-nav">
		@php
			$categories = DB::table('categories')->where('parent_id','=','0')->orderBy('sortOrder', 'asc')->limit(8)->get();
		@endphp
		<?php if($categories){ ?>
		<?php foreach($categories as $items){?>
			<li id="navbar-styling" class="dropdown mega-dropdown">
		<a id="navbar-styling-anchor" href="#" class="dropdown-toggle" data-toggle="dropdown">
		<span id="navbar-styling-span">{{$items->cat_name}}</span>
		<span class="caret"></span></a>	
				<ul class="dropdown-menu mega-dropdown-menu">
					<li class="col-sm-3">
						<ul>
							<li class="dropdown-header">{{$items->cat_name}}</li>                            
                            <li class="divider"></li>
                            <li><a class="publicmenu-li-anchor" onclick="RedirectPublic('product-public-list?category={{$items->cat_id}}');" href="javascript:void(0)" >View all Collection <span class="glyphicon glyphicon-chevron-right pull-right"></span></a></li>
						</ul>
					</li>
					@php
					$categoriesChild = DB::table('categories')->select('cat_id','cat_name')->where('parent_id','=',$items->cat_id)->orderBy('sortOrder', 'asc')->limit(6)->get();
					@endphp
					<?php foreach(array_chunk($categoriesChild,2) as $childEcho){ ?>
					<li class="col-sm-3">
						<ul>
					<?php foreach($childEcho as $test){
					?>
					<li onclick="RedirectPublic('product-public-list?category={{$test->cat_id}}');" class="dropdown-header">{{$test->cat_name}}</li>
					@php
					$categoriesChild3 = DB::table('categories')->select('cat_id','cat_name')->where('parent_id','=',$test->cat_id)->orderBy('sortOrder', 'asc')->limit(5)->get();
					@endphp
					<?php if($categoriesChild3){ 
					foreach($categoriesChild3 as $child3){
					?>
						<li><a class="publicmenu-li-anchor" onclick="RedirectPublic('product-public-list?category={{$child3->cat_id}}');" href="javascript:void(0)">{{$child3->cat_name}} <span class="glyphicon glyphicon-chevron-right pull-right"></span></a></li>
					<?php } ?>
					<?php } ?>
					<li class="divider"></li>
					<?php } ?>	
					</ul>
					</li>
					<?php } ?>
					
				</ul>					
			</li>
		<?php } ?>
		<?php } ?>
		</ul>
	</div>
	</div>
  </nav>
	</div>