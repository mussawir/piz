$('#modal-login-btn').click(function(){
var keyArray = [];
keyArray.push($('#modal-login-email').val());
keyArray.push($('#modal-login-password').val());
var path = projectPrefix+'/authenticate/modal';
var result = childFormSubmit(keyArray,'POST',path);

if(result=='success'){
	ReloadPage();
}
else{
	$('#modal-login').modal('hide');
	showError('msg','Please Use Correct credentials');
	$('#modal-login-password').val('');
	$('#modal-login-email').val('');
}
});
$('#modal-register-btn').click(function(){
var keyArray = [];
keyArray.push($('#modal-register-email').val());
keyArray.push($('#modal-register-password').val());
keyArray.push($('#modal-register-name').val());
var path = projectPrefix+'/authenticate/register';
var result = childFormSubmit(keyArray,'POST',path);

if(result=='success'){
	ReloadPage();
}
else{
	$('#modal-login').modal('hide');
	showError('msg','Please Use Correct credentials');
	$('#modal-register-password').val('');
	$('#modal-register-email').val('');
	$('#modal-register-name').val('');
}
});

$('#cart-btn-product').click(function(){
	var counter = parseInt($('#notification-count').html());
	var keyArrayCart = [];
	var productId = $('#product_id').val();
var pathCart = projectPrefix+'/checkIfProductInCart/'+productId;
var resultCart = childFormSubmit(keyArrayCart,'GET',pathCart);
	if(resultCart!='exist'){
		$('#notification-count').html('');
		$('#notification-count').html((counter+1));
		var proName = $('#product_name').val();
		$('<li id="cart_li_'+productId+'"><div class="cart-dd-img"><img src="'+$('#main-product-thumb').attr('src')+'" class="image-responsive"></div><div class="cart-dd-iname"><span style="display:block;font-size: 12px;"><strong>Name : '+proName.substring(0,25)+'</strong></span><span style="display:block;font-size: 12px;"><strong>Qty : '+$('#product_quantity').val()+'</strong></span></div><div onclick="removeFromCart(\''+productId+'\')" class="cart-dd-cutBtn pull-right">x</div></li>').insertBefore(".last-checkout-btn");
	}
	var keyArray = [];
keyArray.push($('#product_quantity').val());
keyArray.push($('#product_id').val());
var path = projectPrefix+'/'+getLocale()+'/customers/addItem';
var result = childFormSubmit(keyArray,'POST',path);

if(result=='success'){
	showSuccess('msg','Product added into cart successfully!');
}
else{
	showError('msg','Some error occured please refresh and try again');
}
});
function getVariation(){
	var appender = '';
$("select[name^='variations']").each(function(i, obj) {
    appender+=$(this).val();
appender+='_'
});
appender = appender.slice(0,-1).replace(/\s/g, "");
$('#main-product-thumb').attr('src',$('#'+appender+'_image').val());
$('#product_SKU').html('').html($('#'+appender+'_SKU').val());
$('#product_var_quantity').html('').html($('#'+appender+'_qty').val());
$('#variation_selected').val($('#'+appender+'_id').val());
}