/* Set rates + misc */
var taxRate = 0;
var shippingRate = 0; 
var fadeTime = 300;


/* Assign actions */
$('.product-quantity input').change( function() {
  updateQuantity(this);
});

$('.product-removal button').click( function() {
  removeItem(this);
});

/* Recalculate cart */
function recalculateCart()
{
  var subtotal = 0;
  
  /* Sum up row totals */
  $('.product').each(function () {
    subtotal += parseFloat($(this).children('.product-line-price').text());
  });
  
  /* Calculate totals */
  var tax = subtotal * taxRate;
  var shipping = (subtotal > 0 ? shippingRate : 0);
  var total = subtotal + tax + shipping;
  
  /* Update totals display */
  $('.totals-value').fadeOut(fadeTime, function() {
    $('#cart-subtotal').html(subtotal.toFixed(2));
    $('#cart-tax').html(tax.toFixed(2));
    $('#cart-shipping').html(shipping.toFixed(2));
    $('#cart-total').html(total.toFixed(2));
    if(total == 0){
      $('.checkout').fadeOut(fadeTime);
    }else{
      $('.checkout').fadeIn(fadeTime);
    }
    $('.totals-value').fadeIn(fadeTime);
  });
}


/* Update quantity */
function updateQuantity(quantityInput)
{
  /* Calculate line price */
  var productRow = $(quantityInput).parent().parent();
  var price = parseFloat(productRow.children('.product-price').text());
  var quantity = $(quantityInput).val();
  var linePrice = price * quantity;
  //firing ajax to set qty in cart
  var keyArrayDel = [];
	var id = $(quantityInput).data('qty');
	var pathDel = projectPrefix+'/'+getLocale()+'/customers/remove-item-cart/'+id;
  var result = childFormSubmit(keyArrayDel,'GET',pathDel);
  if(result=='success'){
  var keyArray = [];
keyArray.push(quantity);
keyArray.push(id);
var path = projectPrefix+'/'+getLocale()+'/customers/addItem';
var result = childFormSubmit(keyArray,'POST',path);
if(result=='success'){
	//showSuccess('msg','Cart updated successfully!');
  //END
  /* Update line price display and recalc cart totals */
  productRow.children('.product-line-price').each(function () {
    $(this).fadeOut(fadeTime, function() {
      $(this).text(linePrice.toFixed(2));
      recalculateCart();
      $(this).fadeIn(fadeTime);
    });
  });  
}
else{
	showError('msg','Some error occured please refresh and try again');
}
  }else{
	showError('msg','Some error occured please refresh and try again');  
  }
}

var timeLeft = 5;
/* Remove item from cart */
function removeItem(removeButton)
{
	var keyArray = [];
	var id = $(removeButton).data('delete');
	var path = projectPrefix+'/'+getLocale()+'/customers/remove-item-cart/'+id;
  var result = childFormSubmit(keyArray,'GET',path);
  if(result=='success'){
		var productRow = $(removeButton).parent().parent();
		productRow.slideUp(fadeTime, function() {
		productRow.remove();
		recalculateCart();
		var counter = parseInt($('#notification-count').html());
		$('#notification-count').html('');
		$('#notification-count').html((counter-1));
		$('#cart_li_'+id).remove();
		if ($(".product").length){}else{
			window.setInterval(function() {
			
			if(parseInt(timeLeft) == 0){
                RedirectPublic('product-public-list');
			}else{              
			timeLeft = (timeLeft-1);
			$('#msg').show();
				showSuccessPerm('msg','Redirecting you to public page in ' + timeLeft + ' .');
			}
    }, 1000); 
		}
	});
	showSuccess('msg','Product Removed Successfully!');
  }
  else{
	  showError('msg','Some error occured');
  }
}