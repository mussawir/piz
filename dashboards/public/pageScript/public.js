var urlPrefix = $('#laravel_lang').val()==''?'en':$('#laravel_lang').val();
var projectPrefix = '/dev';
function getLocale()
{
	return $('#laravel_lang').val()==''?'en':$('#laravel_lang').val();
}
function removeSpaceInString(str)
{
	var result = str.replace(/\s/g, '');
	return result;
}
function removeFromCart(productId){
	var keyArray = [];
	var path = projectPrefix+'/'+getLocale()+'/customers/remove-item-cart/'+productId;
	var result = childFormSubmit(keyArray,'GET',path);
	if(result=='success'){
		$('#cart_li_'+productId).remove();
		showSuccess('msg','Product removed Successfully!');
		var counter = parseInt($('#notification-count').html());
		$('#notification-count').html('');
		$('#notification-count').html((counter-1));
	}
	else{
		showError('msg','Some Error occured');
	}
}
function isOdd(num) { return num % 2;}
function getNum(val) {
   if (isNaN(val)) {
     return 0;
   }
   return val;
}
function ReloadPage(){
	location.reload();
}
function resetToolTip()
{
	$('[data-toggle="tooltip"]').tooltip();
}
function checkCheckBox(id)
{
	return $('#'+id).prop('checked');
}
function RedirectPublic(url){
	//http://pifastore.my/dev/product/5#
	var host = window.location.protocol;
	var site = window.location.hostname;
	window.location = host+'//'+site+''+projectPrefix+'/'+url;
}
function Redirect(url){
	//http://pifastore.my/dev/product/5#
	var host = window.location.protocol;
	var site = window.location.hostname;
	window.location = host+'//'+site+''+projectPrefix+'/'+getLocale()+'/'+url;
}
function readURL(input,imgId) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#'+imgId).attr('src', e.target.result);
					$('#'+imgId).show();
		        }
		        
		        reader.readAsDataURL(input.files[0]);
				return input.files[0].name;
		    }
			else
			{
				$('#'+imgId).attr('src', '');
					$('#'+imgId).hide();
			}
		}
function showError(id,msg){
	$('#'+id).html('');
	$('#'+id).show();
	$('#'+id).html('<div class="alert alert-danger fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>Error!</strong> '+msg+'.</div>').delay(1000).fadeOut();
}
function showSuccess(id,msg){
	$('#'+id).html('');
	$('#'+id).show();
	$('#'+id).html('<div class="alert alert-success"><strong>Success! </strong>'+msg+'</div>').delay(1000).fadeOut();
}
function showSuccessPerm(id,msg){
	$('#'+id).html('');
	$('#'+id).show();
	$('#'+id).html('<div class="alert alert-success"><strong>Success! </strong>'+msg+'</div>');
}
function arraySearch(arr,val) {
    for (var i=0; i<arr.length; i++)
        if (arr[i] === val)                    
            return i;
    return false;
  }
function setLocale()
{
var lang = $('#localeLang').val();
var url='';
{

$.ajax({
type: "GET",
async:false,
url: projectPrefix+"/setLanguage/"+lang,
beforeSend: function (request) {
return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
},
success: function (result) {
	location.reload();
	$('#myTable tbody').html('');
	$('#myTable tbody').html(result);
$('.msg').html('<div class="alert alert-success"><strong>Success! </strong>Language Set to '+lang+'</div>').show().delay(5000).hide('slow');

},
error:function (error) {
$('.msg').html('<div class="alert alert-danger"><strong>Some error occur. Please try again.</strong></div>').show().delay(5000).hide('slow');
}
});
return false;
}
return false;
}
function childFormSubmit(ArrayofArrays,method,url)
{
	var returnResult;
	$.ajax({
			type    : method,
			data    : { ArrayofArrays: ArrayofArrays},
			async:false,
			url     : url,
			beforeSend: function (request) {
			return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
			},
			success: function (result) {
				returnResult = result;
			},
			error:function (error) {
				returnResult = error;  
			}
			});
			return returnResult;
			//return false;
}
function getSelectedOptionGroup(id){
		var selected = [];
//var id= '#optgroup';
$(id+' option:selected').each(function() {
if(jQuery.inArray($(this).closest('optgroup').prop('label'), selected)== -1){
selected.push($(this).closest('optgroup').prop('label'));
}
});
return selected;
}

		$(document).on('submit', '#CrudForm', function() {
			var submitBtn = $("input[type=submit]",$('#CrudForm'));
			submitBtn.hide();
			var parentDiv = submitBtn.parent();
			parentDiv.append('<button id="loaderBtn" class="btn btn-success pull-right"><i class="fa fa-circle-o-notch fa-spin"></i></button>');			
			
			
			var seconds = new Date().getTime() / 1000;
			var formData = new FormData($(this)[0]);
			//if (typeof beforeSubmit == 'function') {beforeSubmit();}else{}
			$.ajax({
			type    : $(this).attr('method'),
			data    : formData,
			async: true,
			contentType: false,
			processData: false,
			url     : $(this).attr('action'),
			beforeSend: function (request) {
			if (typeof beforeSubmit == 'function') {beforeSubmit();}else{}
			return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
			},
			success: function (result) {
				submitBtn.show();
				$('#loaderBtn').remove();
				showSuccess('msg',result);
				$('#CrudForm')[0].reset();
				 $("html, body").animate({ scrollTop: 0 }, "slow");
				 seconds = new Date().getTime() / 1000;
				 if (typeof afterSubmit == 'function') {afterSubmit();}else{}
			},
			error:function (error) {
				showError('msg','Some Error Occured');
				submitBtn.show();	
				$('#loaderBtn').remove();				
				$("html, body").animate({ scrollTop: 0 }, "slow");
				 seconds = new Date().getTime() / 1000;
			}
			});
			return false;
    });
function trackingImbert(success,obj){
	$('#tracking-table tbody').html('').html(success);
	$(obj).children('#track-loader-modal').hide();
}
function TrackMyOrder(obj){
	$(obj).children('#track-loader-modal').show();
	var trackingId = $('#tracking-modal-tracking-id').val();
	var keyArray = [];
	keyArray.push(trackingId);
	var path = projectPrefix+'/'+getLocale()+'/customers/track-order';
	childFormSubmitAsync(keyArray,'POST',path,trackingImbert,obj);
}
function childFormSubmitAsync(ArrayofArrays,method,url,func,btnObj)
{
	var returnResult;
	$.ajax({
			type    : method,
			data    : { ArrayofArrays: ArrayofArrays},
			async:true,
			url     : url,
			beforeSend: function (request) {
			return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
			},
			success: function (result) {
				func(result,btnObj);
			},
			error:function (error) {
				showError('msg','Some Error Occured');
			}
			});
			//return returnResult;
			//return false;
}
$(".cart-dropdown-hover")
  .mouseenter(function() {
    $('.cart-dropdown').show();
  })
  .mouseleave(function() {
    $('.cart-dropdown').hide();
  });
/*END*/