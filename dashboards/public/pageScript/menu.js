$('#navbar-default-toggler').addClass('in');
$(window).scroll(function (event) {
	if($(window).width()<=784){
		return;
   	}
    var scroll = $(window).scrollTop();
	if(scroll>200){
		if($('.test-fix').css('display')=='none'){
			$('.test-fix').fadeIn(50);
		}
		if($('#menu-btn').children('i').hasClass('fa-rotate-90')){
			$('#navbar-default-toggler').addClass('in');
		}
		if($('#navbar-default-toggler').hasClass('in')){
			if(!$('#menu-btn').children('i').hasClass('fa-rotate-90')){
				$('#menu-btn').children('i').addClass('fa-rotate-90');
			}
		}
	}
    if(scroll>195)
	{
		if($('.test-fix').css('position')=='fixed'){
			return;
		}
		$('.test-fix').hide();
		$('.test-fix').css({'transition': 'all 0.4s linear'});
		$('.test-fix').css({'position':'fixed',
    'right':'0',
    'left':'0',
    'z-index':'1030',
    'top':'0','opacity':'1'});
	$('#logo-image').hide();
	$('#menu-btn').show();
	$('.test-fix').children('.container-fluid').children('.container').css({'padding':'0px','padding-top':'5px'});
	}
	else if(scroll<195){
		if($('.test-fix').css('position')!='fixed'){
			return;
		}
		$('.test-fix').css({'transition': 'all 0.4s linear'});
			$('.test-fix').css({'position':'',
    'right':'',
    'left':'',
    'z-index':'',
    'top':'','opacity':'1'});
	$('#logo-image').show();
	$('#menu-btn').hide();
	if($('#navbar-default-toggler').css('display')=='none'){
		$('#navbar-default-toggler').addClass('in');
	}
	$('.test-fix').children('.container-fluid').children('.container').css({'padding':'15px'});
	}
});