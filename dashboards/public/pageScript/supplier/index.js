function createVariationRuntime(id)
{
	var ArrayofArray=[];
	var countries = '';
	$.each($("#"+id), function(){
		countries = $(this).val();
	});
	if(countries==null) {$('#runtime-variation').html(''); $('#runtime-variation').css('height','100%');$('#save-variation-btn').hide(); return; }
	$('#save-variation-btn').show();
	var childs = countries.toString().split(",");
	var parents = [];
	for(var i =0;i<childs.length;i++)
	{
		var op_group = $('#'+removeSpaceInString(childs[i])+'_parent').val();
		parents.push(op_group);
	}
	var employees ={};
	var disinctParent = jQuery.unique(parents);
	/*for(var i =0;i<childs.length;i++)
	{
		var op_group = $('#'+removeSpaceInString(childs[i])+'_parent').val();
		var parentKey = arraySearch(disinctParent,op_group);
		//ArrayofArray.push([[parentKey],[childs[i]]]);
	}*/
	for(var dis=0;dis<disinctParent.length;dis++)
	{
		var data=[];
		for(var disChild=0;disChild<childs.length;disChild++)
		{
			var op_group = $('#'+removeSpaceInString(childs[disChild])+'_parent').val();
			if(op_group==disinctParent[dis])
			{
				data.push(childs[disChild]);
			}
		}
		ArrayofArray.push(data);
	}
	GetVariation(ArrayofArray);
	
}
function saveVariation()
{
	return;
	var keyArray = [];	
$('#runtime-variation').children('div').each(function() {
	var $this = $(this);
		  	var dataArray = [];
		$this.find('input').each(function() {
		if($(this).val()!=""){
			dataArray.push($(this).val());
		}
	});
	if(dataArray.length>0)
	{
		keyArray.push(dataArray);
	}
});
var path = projectPrefix+'/'+getLocale()+'/supplier/product/saveVariation';
childFormSubmit(keyArray,'POST',path);
clearVariation();
}
function SelectAllVariation()
{
	$('#optgroup').multiSelect('select_all');
	createVariationRuntime("optgroup");
}
function clearVariation()
{
	$('#optgroup').multiSelect('deselect_all');
	createVariationRuntime("optgroup");
}
var idGen = 999999999;
function printVariation(arrayofOne,custom,counter)
{
	if(arrayofOne.length==0){
	{
		if($('#variation_type').val()=='2'){
			clearRows();
		}
		$('#variation_type').val('1');
		var autoId = idGen--;
		var additionalTD='';
		if(checkCheckBox('manageStock'))
		{
			$('#variation-table th:eq(4)').show();
			$('#variation-table td:eq(4)').show();
			additionalTD = '<td><input type="number" min="0" class="form-control input-sm" name="variationQty[]" id="variationQty_'+autoId+'" value="0" /></td>';
		}
		else
		{
			$('#variation-table th:eq(4)').hide();
			$('#variation-table tbody tr').find('td:eq(4)').hide();
			additionalTD='<td></td>';
		}
		var imageId = '#variationImage_'+autoId;
		
			var imageIcon = '<a href="javascript:void(0)" onclick="$(\''+imageId+'\').click();"><i class="fa fa-picture-o" aria-hidden="true"></i></a>';
	$('<tr><td><input type="text" class="form-control input-sm" name="variationName[]" id="variationName_'+autoId+'" value="" /></td><td><input type="text" class="form-control input-sm" name="variationSKU[]" id="variationSKU_'+autoId+'" value="" /></td><td><input type="text" class="form-control input-sm" name="variationCost[]" id="variationCost_'+autoId+'" value="" /></td><td><input type="text" class="form-control input-sm" name="variationPrice[]" id="variationPrice_'+autoId+'" value="" /></td>'+additionalTD+'<td><input type="text" class="form-control input-sm" name="variationStartDt[]" id="variationStartDt_'+autoId+'" value="" /></td><td><input type="text" class="form-control input-sm" name="variationEndDt[]" id="variationEndDt_'+autoId+'" value="" /></td><td>'+imageIcon+'<input style="display:none" id="variationImage_'+autoId+'" name="variationImage[]" type="file"></td></tr>').insertBefore('#lastChildTR');
	if(additionalTD=='<td></td>')
	{
		$('#variation-table th:eq(4)').hide();
			$('#variation-table tbody tr').find('td:eq(4)').hide();
	}
	 $('#variationStartDt_'+autoId).datepicker({
    format: 'dd-mm-yyyy'
 });$('#variationEndDt_'+autoId).datepicker({
    format: 'dd-mm-yyyy'
 });
 return;
	}
	}
	if(arrayofOne.length>0){
		var autoId = idGen--;
		if(!custom)
		{
			$('#variation_type').val('2');
			if($('#variation_type').val()=='2'){
				$('#variationSelected').val(getSelectedOptionGroup('optgroup'));
			}
			var additionalTD='';
			if(checkCheckBox('manageStock'))
			{
				$('#variation-table th:eq(4)').show();
				$('#variation-table td:eq(4)').show();
				additionalTD = '<td><input type="number" min="0" class="form-control input-sm" name="variationQty[]" id="variationQty_'+autoId+'" value="0" /></td>';
			}
			else
			{
				$('#variation-table th:eq(4)').hide();
				$('#variation-table tbody tr').find('td:eq(4)').hide();
				additionalTD='<td></td>';
			}
			var imageId = '#variationImage_'+autoId;
		
			var imageIcon = '<a href="javascript:void(0)" onclick="$(\''+imageId+'\').click();"><i class="fa fa-picture-o" aria-hidden="true"></i></a>';
			$('<tr><td><input type="text" class="form-control input-sm" name="variationName[]" id="variationName_'+autoId+'" value="'+arrayofOne+'" /></td><td><input type="text" class="form-control input-sm" name="variationSKU[]" id="variationSKU_'+autoId+'" value="" /></td><td><input type="text" class="form-control input-sm" name="variationCost[]" id="variationCost_'+autoId+'" value="" /></td><td><input type="text" class="form-control input-sm" name="variationPrice[]" id="variationPrice_'+autoId+'" value="" /></td>'+additionalTD+'<td><input type="text" class="form-control input-sm" name="variationStartDt[]" id="variationStartDt_'+autoId+'" value="" /></td><td><input type="text" class="form-control input-sm" name="variationEndDt[]" id="variationEndDt_'+autoId+'" value="" /></td><td>'+imageIcon+'<input style="display:none" id="variationImage_'+autoId+'" name="variationImage[]" type="file"></td></tr>').insertBefore('#lastChildTR');
			$('#variationStartDt_'+autoId).datepicker({format: 'dd-mm-yyyy'});$('#variationEndDt_'+autoId).datepicker({format: 'dd-mm-yyyy'});
			if(additionalTD=='<td></td>')
				{			
					$('#variation-table th:eq(4)').hide();
					$('#variation-table tbody tr').find('td:eq(4)').hide();
				}
		}
	}
}
function GetVariation(arrayOfArrays)
{
	//var arrayOfArrays = [["RED", "BLUE", "BLACK"], ["SMALL", "LARGE", "EXTRA SMALL"],["Round","Square","Triangle"]];
var combinations = [],
			comboKeys = [],
			numOfCombos = arrayOfArrays.length ? 1 : 0,
			arrayOfArraysLength = arrayOfArrays.length;
		
		for(var n = 0; n < arrayOfArraysLength; ++n) {
			if(Object.prototype.toString.call(arrayOfArrays[n]) !== '[object Array]') {
				throw new Error("combinations method was passed a non-array argument");
			}
			numOfCombos = numOfCombos*arrayOfArrays[n].length;
		}
		
		for(var x = 0; x < numOfCombos; ++x) {
			var carry = x,
				comboKeys = [],
				combo = [];
			
			for(var i = 0; i < arrayOfArraysLength; ++i) {
				comboKeys[i] = carry % arrayOfArrays[i].length;
				carry = Math.floor(carry / arrayOfArrays[i].length);
			}
			for(var i = 0; i < comboKeys.length; ++i) {
				combo.push(arrayOfArrays[i][comboKeys[i]]);
			}
			combinations.push(combo);
		}
		$('#runtime-variation').html('');
for(var q=0;q<combinations.length;q++)
{
	printVariation(combinations[q],false,q);
}
$(".btn-file").click(function() {
	//$('#'+$(this).data('click')).show();
//$('#'+$(this).data('click')).click();
});
}
function getChilds(id,obj)
{
	$('#selected-parent').val(id);
	$('.list-group-item').removeClass('active');
	$(obj).addClass('active');
	$(obj).parent('div').parent('div').parent('div').nextAll().remove();
	var cat_Level = parseInt($(obj).parent('div').parent('div').parent('div').attr('id'));
	//if(!cat_Level){cat_Level=0;alert('here');}
	cat_Level = getNum(cat_Level);
	var url = projectPrefix+"/"+getLocale()+"/supplier/category-fetch-child/"+id;
	var result = childFormSubmit(null,"GET",url);
	loadChilds(result,cat_Level);
}
$('#category-select-btn-next').click(function(){
if($('#selected-parent').val()=='0'||$('#selected-parent').val()==0){
showError('msg','Please Select any one category to go forward');	
	}
	else
	{
		var arrayofArray = {selectedParent : $('#selected-parent').val() , categoryLevel : $('#category-level').val()};
		var url = "/dev/"+getLocale()+"/supplier/home";
		console.log(childFormSubmit(arrayofArray,"POST",url));
	}
});

function clearSelection(){
  $('.list-group-item').removeClass('active');
  var removeBtn = $('#clear-btn-div');
  $('#parent_id_menu').html('');
  $('#parent_id_menu').append(removeBtn);
  getChilds('0');
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
	appender+='<div style="margin-top:10px;" class="col-md-4" id="'+cat_Level+'">';
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
	$('.list-group').slimScroll({
        height: '250px',
		width : '100%'
    });
}




