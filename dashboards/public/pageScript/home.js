var urlPrefix = $('#laravel_lang').val();
function saveData()
{
var lang = $('#lang').val();
var message = $('#message').val();
var url='';
{

$.ajax({
type: "POST",
data: {
lang : lang,
message : message
},
async:false,
url: projectPrefix+"/"+lang+"/admin/saveMessage",
beforeSend: function (request) {
return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
},
success: function (result) {
//$(elm).closest('tr').remove();
$('#message').val('');
$('.msg').html('<div class="alert alert-success"><strong>Success! </strong>Success</div>').show().delay(5000).hide('slow');
$('#myTable tr:last').after('<tr><td>'+message+'</td><td>'+result+'</td></tr>');
},
error:function (error) {
$('.msg').html('<div class="alert alert-danger"><strong>Some error occur. Please try again.</strong></div>').show().delay(5000).hide('slow');
}
});
return false;
}
return false;
}
