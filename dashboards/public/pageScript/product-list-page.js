$('#list-add-cart').click(function(){
var counter = parseInt($('#notification-count').html());
	var keyArrayCart = [];
	var productId = $('#product_id').val();
var pathCart = projectPrefix+'/checkIfProductInCart/'+productId;
var resultCart = childFormSubmit(keyArrayCart,'GET',pathCart);
	if(resultCart!='exist'){
		$('#notification-count').html('');
		$('#notification-count').html((counter+1));
		
		//$('.cart-dropdown').append('');
		$('<li id="cart_li_'+productId+'"><div class="cart-dd-img"><img src="'+$('#product_image_src').val()+'" class="image-responsive"></div><div class="cart-dd-iname"><span style="display:block;font-size: 12px;"><strong>Name : '+$('#products_name_src').val().substring(0,25)+'</strong></span><span style="display:block;font-size: 12px;"><strong>Qty : '+$('#product_quantity').val()+'</strong></span></div><div onclick="removeFromCart(\''+productId+'\')" class="cart-dd-cutBtn pull-right">x</div></li>').insertBefore(".last-checkout-btn");
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