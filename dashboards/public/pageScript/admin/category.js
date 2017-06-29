function getChilds(id,obj)
{
	$('#selected-parent').val(id);
	$('.list-group-item').removeClass('active');
	$(obj).addClass('active');
	$(obj).parent('div').parent('div').nextAll().remove();
	var cat_Level = parseInt($(obj).parent('div').parent('div').attr('id'));
	//if(!cat_Level){cat_Level=0;alert('here');}
	cat_Level = getNum(cat_Level);
	var url = projectPrefix+"/"+getLocale()+"/admin/category-fetch-child/"+id;
	var result = childFormSubmit(null,"GET",url);
	loadChilds(result,cat_Level);
}
function clearSelection(){
  $('.list-group-item').removeClass('active');
  var removeBtn = $('#clear-btn-div');
  $('#parent_id_menu').html('');
  $('#parent_id_menu').append(removeBtn);
  getChilds('0');
}
function afterSubmit()
{
	clearSelection();
}
function loadChilds(result,cat_Level)
{
	if(result!=""){
		var catLevel = parseInt($('#category-level').val());
		cat_Level = parseInt(cat_Level+1);
		if($("#"+cat_Level).length == 0) {
			$('#category-level').val(cat_Level);
		}
		else{
			$("#"+cat_Level).remove();
			$('#category-level').val(cat_Level);
		}
	var appender = '';
	appender+='<div class="col-md-3" id="'+cat_Level+'">';
	appender+='<div class="col-md-12 list-group">';
	var resultingContent = result.split('|');
	for(var i = 0;i<resultingContent.length;i++){
		//if(!=='undefined'){
		if(typeof resultingContent[i].split(',')[1] != 'undefined'){
		appender+='<a href="#/" class="list-group-item" onclick="getChilds('+resultingContent[i].split(',')[0]+',this);">'+resultingContent[i].split(',')[1]+'</a>';
		}
	}
	appender+='</div></div>';
	$('#parent_id_menu').append(appender);
	}
}