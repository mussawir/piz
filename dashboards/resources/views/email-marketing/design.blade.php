@extends('layouts.marketing')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="dragsidebar col-md-3 col-xs-3">
				<div class="row">
					<div class="boxes-parent col-md-6 text-center col-xs-12">
						<div id="heading" class="box_heading boxes-child col-md-12">
							<img src="/dashboards/public/Images/text-icon.png">
							<h5>Heading</h5>
						</div>
					</div>
					<div class="boxes-parent col-md-6 text-center col-xs-12">
						<div id="image" class="box_image boxes-child col-md-12">
							<img src="/dashboards/public/Images/image-icon.png">
							<h5>Image</h5>
						</div>
					</div>
					<div class="boxes-parent col-md-6 text-center col-xs-12">
						<div id="divider" class="box_divider boxes-child col-md-12">
							<img src="/dashboards/public/Images/devide-icon.png">
							<h5>Devider</h5>
						</div>
					</div>
					<div class="boxes-parent col-md-6 text-center col-xs-12">
						<div id="button" class="box_button boxes-child col-md-12">
							<img src="/dashboards/public/Images/button-icon.png">
							<h5>Button</h5>
						</div>
					</div>
					<div class="boxes-parent col-md-6 text-center col-xs-12">
						<div id="description" class="box_description boxes-child col-md-12">
							<img src="/dashboards/public/Images/desc-icon.png">
							<h5>Description</h5>
						</div>
					</div>
					<div class="boxes-parent col-md-6 text-center col-xs-12">
						<div id="social" class="box_social boxes-child col-md-12">
							<img src="/dashboards/public/Images/social-icon.png">
							<h5>Social</h5>
							
						</div>
					</div>
					<div class="boxes-parent col-md-6 text-center col-xs-12">
						<div id="show_footer" class="box_footer boxes-child col-md-12">
							<img src="/dashboards/public/Images/footer.png">
							<h5>Footer</h5>
							
						</div>
					</div>
					<div class="boxes-parent col-md-6 text-center col-xs-12">
						<div id="two_column" class="box_two_column boxes-child col-md-12">
							<img src="/dashboards/public/Images/image-text.png">
							<h5>Two Column</h5>
						</div>
					</div>
				</div>
			</div>
			<div class="tempalate col-md-6 col-xs-6">
				<div class="col-md-12 text-center" id="sortable">
					@if(isset($_GET['template']))
						@if($_GET['template'] == 'ecommerce')
							<div class="image" id="image_0"> 
								<img src="http://pageiz.com/dashboards/public/Images/image.png" class="img-responsive" style="margin:auto;">
								<span class="image-handle fa fa-arrows"></span>
								<span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="image-delete fa fa-trash"></span>
								<span onclick="showimage(0)" data-toggle="tooltip" title="Edit" class="image-edit fa fa-pencil"></span> 
							</div>
							<div class="divider"> 
							<hr> 
							<span class="divider-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="divider-delete fa fa-trash"></span>
							</div>
							<div class="two-column" id="twocolumn_0"> 
							<div class="first" style="height: 100%; width: 49%; float: left; text-align: center;padding: 0px 23px;"> <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=Insert%20Image&w=250&h=200" style="max-width:100%"> 
							<h3 style="font-weight:300;">Hello World</h3> 
							<p style="word-wrap: break-word;text-align:justify;font-weight:300;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, 
							</p> 
							<a href="">Read More ></a> 
							</div> 
							<div class="second" style="height: 100%; width: 49%; float: right; text-align: center;padding: 0px 23px;"> <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=Insert%20Image&w=250&h=200" style="max-width:100%"> 
							<h3 style="font-weight:300;">Hello World</h3> 
							<p style="word-wrap: break-word;text-align:justify;font-weight:300;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, </p> 
							<a href="">Read More ></a> 
							</div> 
							<span class="twocolumn-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="twocolumn-delete fa fa-trash"></span> 
							<span onclick="showtwocolumn(0)" data-toggle="tooltip" title="Edit" class="twocolumn-edit fa fa-pencil"></span> 
							</div>
							<div id="footer" class="footer_'+footer+'"> 
							<div id="para">
							<p>Customer Care,</p> 
							<p>Your Company</p> 
							<p>ABC Street, Somewhere around world</p> 
							<p>Phone: +92-51-8313316-7, Fax: +92-51-5516207</p>
							</div> 
							<a href="" id="facebooklinker"> <img src="http://pageiz.com/dashboards/public/Images/facebook.png" style="height: 44px;"> 
							</a> 
							<a href="" id="twitterlinker"> <img src="http://pageiz.com/dashboards/public/Images/twitter.png"style="height: 44px;"> 
							</a> 
							<a href="" id="gpluslinker"> <img src="http://pageiz.com/dashboards/public/Images/googleplus.png"style="height: 44px;"> 
							</a> 
							<span class="footer-handle fa fa-arrows"></span> 
							<span onclick="$(this).parent().remove();" class="footer-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span> 
							<span onclick="showfooter(0)" class="footer-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> 
							</div>
						@elseif($_GET['template'] == 'text')
							<div class="description" id="desc_0"> 
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
							</p>
							<span class="description-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" class="description-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span>
							<span onclick="showdescription(0)"  class="description-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> 
							</div>
						@elseif($_GET['template'] == 'simple')
							<div class="heading" id="heading_0">
							<h3>Title Goes Here</h3>
							<span class="heading-handle fa fa-arrows"></span>
							<span onclick="$(this).parent().remove();" class="heading-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span>
							<span onclick="showheading(0)"  class="heading-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> 
							</div>
							<div class="divider"> 
							<hr> 
							<span class="divider-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="divider-delete fa fa-trash"></span>
							</div>
							<div class="image" id="image_0"> 
								<img src="http://pageiz.com/dashboards/public/Images/image.png" class="img-responsive" style="margin:auto;">
								<span class="image-handle fa fa-arrows"></span>
								<span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="image-delete fa fa-trash"></span>
								<span onclick="showimage(0)" data-toggle="tooltip" title="Edit" class="image-edit fa fa-pencil"></span> 
							</div>
							<div class="description" id="desc_0"> 
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
							</p>
							<span class="description-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" class="description-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span>
							<span onclick="showdescription(0)"  class="description-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> 
							</div>
							<div id="footer" class="footer_'+footer+'"> 
							<div id="para">
							<p>Customer Care,</p> 
							<p>Your Company</p> 
							<p>ABC Street, Somewhere around world</p> 
							<p>Phone: +92-51-8313316-7, Fax: +92-51-5516207</p>
							</div> 
							<a href="" id="facebooklinker"> <img src="http://pageiz.com/dashboards/public/Images/facebook.png" style="height: 44px;"> 
							</a> 
							<a href="" id="twitterlinker"> <img src="http://pageiz.com/dashboards/public/Images/twitter.png"style="height: 44px;"> 
							</a> 
							<a href="" id="gpluslinker"> <img src="http://pageiz.com/dashboards/public/Images/googleplus.png"style="height: 44px;"> 
							</a> 
							<span class="footer-handle fa fa-arrows"></span> 
							<span onclick="$(this).parent().remove();" class="footer-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span> 
							<span onclick="showfooter(0)" class="footer-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> 
							</div>
						@endif
						
					@endif
					<!--form action="{{url('send-email')}}" method="POST">
					{{ csrf_field() }}
					<textarea name="emaildatas" class="form-control"></textarea>
					<button type="submit">Save</button>
					</form-->
				</div>
			</div> 
			<div class="edit-template col-md-3 col-xs-3" id="editable">
				
			</div>
		</div>
	</div>
	

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Preview</h4>
      </div>
      <div class="modal-body text-center" id="modalBody">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection
@section('pageScript')
	<script src="https://cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
		
	<script>
		$(document).ready(function(){
			
			// if($('#sortable').children().length == 0){
				// alert('no child');
			// }else{
				// alert('child');
			// }
			$('#marketing-naver').prepend('<li> <a onclick="previewemail()" href="javascript:void(0)">Preview</a> </li> <li> <a onclick="sendemail()" href="javascript:void(0)">Send</a> </li>');
			var heading = 0;
			var desc = 0;
			var button = 0;
			var social = 0;
			var image = 0;
			var footer = 0;
			var two_column = 0;
			$('[data-toggle="tooltip"]').tooltip();
			$('#image').draggable({
				connectToSortable: '#sortable',
				cursor: 'move',
				helper: 'clone',
				revert: 'invalid'
			});
			$('#heading').draggable({
				connectToSortable: '#sortable',
				cursor: 'move',
				helper: 'clone',
				revert: 'invalid'
			});
			$('#divider').draggable({
				connectToSortable: '#sortable',
				cursor: 'move',
				helper: 'clone',
				revert: 'invalid'
			});
			$('#social').draggable({
				connectToSortable: '#sortable',
				cursor: 'move',
				helper: 'clone',
				revert: 'invalid'
			});
			$('#button').draggable({
				connectToSortable: '#sortable',
				cursor: 'move',
				helper: 'clone',
				revert: 'invalid'
			});
			$('#show_footer').draggable({
				connectToSortable: '#sortable',
				cursor: 'move',
				helper: 'clone',
				revert: 'invalid'
			});
			$('#description').draggable({
				connectToSortable: '#sortable',
				cursor: 'move',
				helper: 'clone',
				revert: 'invalid'
			});
			$('#two_column').draggable({
				connectToSortable: '#sortable',
				cursor: 'move',
				helper: 'clone',
				revert: 'invalid'
			});
			$('#sortable').sortable({
				connectWith: '#sortable',
				handle: ".fa.fa-arrows",
				placeholder: 'ui-state-highlight',
				cursor:'move',
				revert:false,
				distance: 1,
				tolerance: 'pointer',
				stop: function(event, ui) {
					if (ui.item.hasClass("box_image")) {
						image++;
						ui.item.replaceWith('<div class="image" id="image_'+image+'"> <img src="http://pageiz.com/dashboards/public/Images/image.png" class="img-responsive" style="margin:auto;"><span class="image-handle fa fa-arrows"></span><span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="image-delete fa fa-trash"></span><span onclick="showimage('+image+')" data-toggle="tooltip" title="Edit" class="image-edit fa fa-pencil"></span> </div>');
						$('[data-toggle="tooltip"]').tooltip();
					}
					else if(ui.item.hasClass("box_heading")){
						heading++; 
						ui.item.replaceWith('<div class="heading" id="heading_'+heading+'"> <h3>Title Goes Here</h3><span class="heading-handle fa fa-arrows"></span><span onclick="$(this).parent().remove();" class="heading-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span><span onclick="showheading('+heading+')"  class="heading-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> </div>');
						$('[data-toggle="tooltip"]').tooltip();
					}
					else if(ui.item.hasClass("box_divider")){
						divider++; 
						ui.item.replaceWith('<div class="divider"> <hr> <span class="divider-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="divider-delete fa fa-trash"></span></div>');
						$('[data-toggle="tooltip"]').tooltip();
					}
					else if(ui.item.hasClass("box_description")){
						desc++; 
						ui.item.replaceWith('<div class="description" id="desc_'+desc+'"> <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><span class="description-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" class="description-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span><span onclick="showdescription('+desc+')"  class="description-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> </div>');
						$('[data-toggle="tooltip"]').tooltip();
					}
					else if(ui.item.hasClass("box_button")){
						button++; 
						ui.item.replaceWith('<div class="button" id="button_'+button+'"> <a href="" class="btn btn-primary">Click Here</a> <span class="button-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="button-delete fa fa-trash"></span><span onclick="showbutton('+button+')" data-toggle="tooltip" title="Edit" class="button-edit fa fa-pencil"></span> </div>');
						$('[data-toggle="tooltip"]').tooltip();
					}
					else if(ui.item.hasClass("box_footer")){
						footer++; 
						ui.item.replaceWith('<div id="footer" class="footer_'+footer+'"> <div id="para"><p>Customer Care,</p> <p>Your Company</p> <p>ABC Street, Somewhere around world</p> <p>Phone: +92-51-8313316-7, Fax: +92-51-5516207</p></div> <a href="" id="facebooklinker"> <img src="http://pageiz.com/dashboards/public/Images/facebook.png" style="height: 44px;"> </a> <a href="" id="twitterlinker"> <img src="http://pageiz.com/dashboards/public/Images/twitter.png"style="height: 44px;"> </a> <a href="" id="gpluslinker"> <img src="http://pageiz.com/dashboards/public/Images/googleplus.png"style="height: 44px;"> </a> <span class="footer-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" class="footer-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span> <span onclick="showfooter('+footer+')" class="footer-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> </div>');
						$('[data-toggle="tooltip"]').tooltip();
					}
					else if(ui.item.hasClass("box_social")){
						heading++; 
						ui.item.replaceWith('<div class="social" id="social_'+social+'"> <a href="" id="facebooklinker"><img src="http://pageiz.com/dashboards/public/Images/facebook.png" style="height: 44px;"></a> <a href="" id="twitterlinker"><img src="http://pageiz.com/dashboards/public/Images/twitter.png"style="height: 44px;"></a> <a href="" id="gpluslinker"><img src="http://pageiz.com/dashboards/public/Images/googleplus.png"style="height: 44px;"></a> <span class="social-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="social-delete fa fa-trash"></span><span onclick="showsocial('+social+')" data-toggle="tooltip" title="Edit" class="social-edit fa fa-pencil"></span> </div>');
						$('[data-toggle="tooltip"]').tooltip();
					}
					else if(ui.item.hasClass("box_two_column")){
						two_column++; 
						ui.item.replaceWith('<div class="two-column" id="twocolumn_'+two_column+'"> <div class="first" style="height: 100%; width: 49%; float: left; text-align: center;padding: 0px 23px;"> <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=Insert%20Image&w=250&h=200" style="width:100%"> <h3 style="font-weight:300;">Hello World</h3> <p style="word-wrap: break-word;text-align:justify;font-weight:300;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, </p> <a href="">Read More ></a> </div> <div class="second" style="height: 100%; width: 49%; float: right; text-align: center;padding: 0px 23px;"> <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=Insert%20Image&w=250&h=200"  style="width:100%"> <h3 style="font-weight:300;">Hello World</h3> <p style="word-wrap: break-word;text-align:justify;font-weight:300;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, </p> <a href="">Read More ></a> </div> <span class="twocolumn-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="twocolumn-delete fa fa-trash"></span> <span onclick="showtwocolumn('+two_column+')" data-toggle="tooltip" title="Edit" class="twocolumn-edit fa fa-pencil"></span> </div>');
						$('[data-toggle="tooltip"]').tooltip();
					}
				}
			});
			
			$('#sortable').disableSelection();
			
			$('#sortable').bind('sortstart', function(event, ui) {
				$('.ui-state-highlight').append('<img style="margin:auto;width:40px;" src="https://d30y9cdsu7xlg0.cloudfront.net/png/49665-200.png" class="img-responsive"><h5>Drop Here</h5>');
			});
			
			// $('#image').click(function(){
				// image++;
				// $('#sortable').prepend('<div class="image" id="image_'+image+'"> <img src="http://pageiz.com/dashboards/public/Images/image.png" class="img-responsive" style="margin:auto;"><span class="image-handle fa fa-arrows"></span><span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="image-delete fa fa-trash"></span><span onclick="showimage('+image+')" data-toggle="tooltip" title="Edit" class="image-edit fa fa-pencil"></span> </div>');
				// $('[data-toggle="tooltip"]').tooltip();
			// });
			// $('#heading').click(function(){
				// heading++; 
				// $('#sortable').prepend('<div class="heading" id="heading_'+heading+'"> <h3>Title Goes Here</h3><span class="heading-handle fa fa-arrows"></span><span onclick="$(this).parent().remove();" class="heading-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span><span onclick="showheading('+heading+')"  class="heading-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> </div>');
				// $('[data-toggle="tooltip"]').tooltip();
			// });
			// $('#divider').click(function(){
				// $('#sortable').prepend('<div class="divider"> <hr> <span class="divider-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="divider-delete fa fa-trash"></span></div>');
				// $('[data-toggle="tooltip"]').tooltip();
			// });
			// $('#button').click(function(){
				// button++;
				// $('#sortable').prepend('<div class="button" id="button_'+button+'"> <a href="" class="btn btn-primary">Click Here</a> <span class="button-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="button-delete fa fa-trash"></span><span onclick="showbutton('+button+')" data-toggle="tooltip" title="Edit" class="button-edit fa fa-pencil"></span> </div>');
				// $('[data-toggle="tooltip"]').tooltip();
			// });
			// $('#description').click(function(){
				// desc++;
				// $('#sortable').prepend('<div class="description" id="desc_'+desc+'"> <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><span class="description-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" class="description-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span><span onclick="showdescription('+desc+')"  class="description-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> </div>');
				// $('[data-toggle="tooltip"]').tooltip();
			// });
			// $('#social').click(function(){
				// social++;
				// $('#sortable').prepend('<div class="social" id="social_'+social+'"> <a href="" id="facebooklinker"><img src="http://pageiz.com/dashboards/public/Images/facebook.png" style="height: 44px;"></a> <a href="" id="twitterlinker"><img src="http://pageiz.com/dashboards/public/Images/twitter.png"style="height: 44px;"></a> <a href="" id="gpluslinker"><img src="http://pageiz.com/dashboards/public/Images/googleplus.png"style="height: 44px;"></a> <span class="social-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="social-delete fa fa-trash"></span><span onclick="showsocial('+social+')" data-toggle="tooltip" title="Edit" class="social-edit fa fa-pencil"></span> </div>');
				// $('[data-toggle="tooltip"]').tooltip();
			// });
			
			// $('#show_footer').click(function(){
				// footer++;
				// $('#sortable').prepend('<div id="footer" class="footer_'+footer+'"> <div id="para"><p>Customer Care,</p> <p>Your Company</p> <p>ABC Street, Somewhere around world</p> <p>Phone: +92-51-8313316-7, Fax: +92-51-5516207</p></div> <a href="" id="facebooklinker"> <img src="http://pageiz.com/dashboards/public/Images/facebook.png" style="height: 44px;"> </a> <a href="" id="twitterlinker"> <img src="http://pageiz.com/dashboards/public/Images/twitter.png"style="height: 44px;"> </a> <a href="" id="gpluslinker"> <img src="http://pageiz.com/dashboards/public/Images/googleplus.png"style="height: 44px;"> </a> <span class="footer-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" class="footer-delete fa fa-trash" data-toggle="tooltip" title="Delete"></span> <span onclick="showfooter('+footer+')" class="footer-edit fa fa-pencil" data-toggle="tooltip" title="Edit"></span> </div>');
				// $('[data-toggle="tooltip"]').tooltip();
			// });
		});
		
		function showheading(id){
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "0"});
			$('#editable').html('<div id="edit-heading"> <h3>Edit Heading</h3> <input type="text" class="form-control" id="eheading" value="'+$("#heading_"+id+ " h3").html()+'"> <button class="btn btn-success" onclick="editheading('+id+')">Done</button> </div>').fadeIn(400);
		}
		function showdescription(id){ 
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "0"});
			$('#editable').html('<div id="edit-description"> <h3>Edit Description</h3> <textarea name="editor1" id="edesc" class="form-control" rows="5"></textarea> <button onclick="editdesc('+id+')" class="btn btn-success">Done</button> </div>').fadeIn(400);
			showeditor(id);
		}
		function showeditor(id){
			CKEDITOR.replace( 'editor1' );
			CKEDITOR.instances['edesc'].setData($("#desc_"+id+ " p").html());
		}
		function showbutton(id){
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "0"});
			$('#editable').html('<div id="edit-button"> <h3>Edit Button</h3> <input type="text" id="elink" placeholder="Button Link" class="form-control"  value="'+ $("#button_"+id+ " a").attr('href') +'"> <input class="form-control" type="text" id="etext" placeholder="Button Text" value="'+ $("#button_"+id+ " a").html() +'"> <button class="btn btn-success" onclick="editbutton('+id+')">Done</button> </div>').fadeIn(400);
		}
		function showsocial(id){
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "0"});
			$('#editable').html('<div id="edit-social"> <h3>Edit Social Buttons</h3> <label>Facebook</label> <input type="text" id="efacebook" class="form-control" placeholder="Facebook links"> <input type="text" id="etwitter" class="form-control" placeholder="Twitter links"> <input type="text" id="egplus" class="form-control" placeholder="Google+ links"> <button onclick="editsociallinks('+id+')" class="btn btn-success">Done</button> </div>').fadeIn(400);
		}
		function showimage(id){
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "0"});
			$('#editable').html('<div id="edit-image"> <h3>Upload Image</h3> <input onchange="readURL(this,'+id+');" id="inputFile_'+id+'" type="file">  </div>').fadeIn(400);
		}
		function readURL(input,imgId) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#image_'+imgId).children('img').attr('src', e.target.result);
					$('#image_'+imgId).children('img').show();
		        }
		        
		        reader.readAsDataURL(input.files[0]);
				$('#edit-image').fadeOut();
				$(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
				return input.files[0].name;
		    }
			else
			{
				$('#image_'+imgId).children('img').attr('src', '');
					$('#image_'+imgId).children('img').hide();
			}
			
		}
		function readURL1st(input,imgId) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#twocolumn_'+imgId+ ' .first img ').attr('src', e.target.result);
					$('#twocolumn_'+imgId+ ' .first img ').show();
		        }
		        
		        reader.readAsDataURL(input.files[0]);
				// $('#edit-image').fadeOut();
				// $(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
				return input.files[0].name;
		    }
			else
			{
				// $('#image_'+imgId).children('img').attr('src', '');
					// $('#image_'+imgId).children('img').hide();
			}
			
		}
		function readURL2nd(input,imgId) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#twocolumn_'+imgId+ ' .second img').attr('src', e.target.result);
					$('#twocolumn_'+imgId+ ' .second img').show();
		        }
		        
		        reader.readAsDataURL(input.files[0]);
				// $('#edit-image').fadeOut();
				// $(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
				return input.files[0].name;
		    }
			else
			{
				// $('#image_'+imgId).children('img').attr('src', '');
					// $('#image_'+imgId).children('img').hide();
			}
			
		}
		function showfooter(id){
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "0"});
			$('#editable').html('<div id="edit-footer"> <h3>Edit Footer</h3> <textarea name="editor2" id="efooter" class="form-control" rows="5"></textarea> <label>Facebook</label> <input type="text" id="ffacebook" class="form-control" placeholder="Facebook links" value="'+$('.footer_'+id+ ' #facebooklinker').attr('href')+'"> <label>Twitter</label> <input type="text" id="ftwitter" class="form-control" placeholder="Twitter links" value="'+$('.footer_'+id+ ' #twitterlinker').attr('href')+'"> <label>Google Plus</label> <input type="text" id="fgplus" class="form-control" placeholder="Google+ links" value="'+$('.footer_'+id+ ' #gpluslinker').attr('href')+'"> <button onclick="editfooter('+id+')" class="btn btn-success"> Done </button> </div>').fadeIn(400);
			showeditor2(id);
		}
		function showeditor2(id){
			CKEDITOR.replace( 'editor2' );
			CKEDITOR.instances['efooter'].setData($(".footer_"+id+ " #para").html());
		}
		function showtwocolumn(id){
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "0"});
			$('#editable').html('<div id="two-columns"> <h3>Edit Two Columns</h3> <hr> <h4>First Column</h4> <label> Image </label> <input  type="file" id="first_image" class="form-control" onchange="readURL1st(this,'+id+');"> <label> Heading </label> <input type="text" id="first_heading" class="form-control" value="'+$('#twocolumn_'+id+ ' .first h3').html()+'"> <label> 1st Button Link </label> <input type="text" id="first_link" class="form-control" value="'+$('#twocolumn_'+id+ ' .first a').attr('href')+'"> <label> Description </label> <textarea id="first_desc" class="form-control">'+$('#twocolumn_'+id+ ' .first p').html()+'"</textarea> <hr> <h4>Second Column</h4> <label> Image </label> <input  type="file" id="second_image" class="form-control"  onchange="readURL2nd(this,'+id+');"> <label> Heading </label> <input type="text" id="second_heading" class="form-control" value="'+$('#twocolumn_'+id+ ' .second h3').html()+'"> <label> 2nd Button Link </label> <input type="text" id="second_link" class="form-control" value="'+$('#twocolumn_'+id+ ' .second a').attr('href')+'"> <label> Description </label> <textarea id="second_desc" class="form-control">'+$('#twocolumn_'+id+ ' .second p').html()+'"</textarea> <button onclick="edit_twocolumn('+id+')" class="btn btn-success">Done</button> </div>').fadeIn(400);
		}
		// $('#two_column').click(function(){
				// two_column++;
				// $('#sortable').append('<div class="two-column" id="twocolumn_'+two_column+'"> <div class="first" style="height: 100%; width: 49%; float: left; text-align: center;padding: 0px 23px;"> <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=Insert%20Image&w=250&h=200" style="width:100%"> <h3 style="font-weight:300;">Hello World</h3> <p style="word-wrap: break-word;text-align:justify;font-weight:300;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, </p> <a href="">Read More ></a> </div> <div class="second" style="height: 100%; width: 49%; float: right; text-align: center;padding: 0px 23px;"> <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=Insert%20Image&w=250&h=200"  style="width:100%"> <h3 style="font-weight:300;">Hello World</h3> <p style="word-wrap: break-word;text-align:justify;font-weight:300;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, </p> <a href="">Read More ></a> </div> <span class="twocolumn-handle fa fa-arrows"></span> <span onclick="$(this).parent().remove();" data-toggle="tooltip" title="Delete" class="twocolumn-delete fa fa-trash"></span> <span onclick="showtwocolumn('+two_column+')" data-toggle="tooltip" title="Edit" class="twocolumn-edit fa fa-pencil"></span> </div>');
				// $('[data-toggle="tooltip"]').tooltip();
			// });
		function edit_twocolumn(id){
			var first_heading = $('#first_heading').val();
			var first_desc = $('#first_desc').val();
			var first_link = $('#first_link').val();
			var second_heading = $('#second_heading').val();
			var second_desc = $('#second_desc').val();
			var second_link = $('#second_link').val();
			if(first_heading == '' && first_desc == '' && second_heading == '' && second_desc == ''){
				return;
			}
			$('#twocolumn_'+id+ ' .first h3').html(first_heading);
			$('#twocolumn_'+id+ ' .first p').html(first_desc);
			$('#twocolumn_'+id+ ' .first a').attr('href',first_link);
			$('#twocolumn_'+id+ ' .second h3').html(second_heading);
			$('#twocolumn_'+id+ ' .second p').html(second_desc);
			$('#twocolumn_'+id+ ' .second a').attr('href',second_link);
			$('#edit-footer').fadeOut(200);
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
		}
		function editfooter(id){
			var fb = $('#ffacebook').val();
			var twt = $('#ftwitter').val();
			var gplus = $('#fgplus').val();
			// alert(fb+' '+ twt + ' ' + gplus);return;
			if(fb == '' && twt == '' && gplus == ''){
				return;
			}
			$('.footer_'+id+' #facebooklinker').attr('href',fb);
			$('.footer_'+id+' #twitterlinker').attr('href',twt);
			$('.footer_'+id+' #gpluslinker').attr('href',gplus);
			var fcontent = CKEDITOR.instances['efooter'].getData();
			$('.footer_'+id+' #para').html(fcontent);
			$('#edit-footer').fadeOut(200);
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
		}
		function editsociallinks(id){
			var fb = $('#efacebook').val();
			var twt = $('#etwitter').val();
			var gplus = $('#egplus').val();
			if(fb == '' && twt == '' && gplus == ''){
				return;
			}
			$('#social_'+id+' #facebooklinker').attr('href',fb);
			$('#social_'+id+' #twitterlinker').attr('href',twt);
			$('#social_'+id+' #gpluslinker').attr('href',gplus);
			$('#edit-social').fadeOut(200);
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
		}
		
		function editbutton(id){
			var link = $('#elink').val();
			var text = $('#etext').val();
			if(link == '' && text == ''){
				return;
			}
			$('#button_'+id+' a').html(text).attr('href',link);
			$('#edit-button').fadeOut(200);
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
		}
		function editheading(id){
			var heading = $('#eheading').val();
			if(heading == ''){
				return;
			}
			$('#heading_'+id+' h3').html(heading);
			$('#edit-heading').fadeOut(200);
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
		}
		function editdesc(id){
			var desc = CKEDITOR.instances['edesc'].getData();
			// alert(desc);return
			// alert(desc);return;
			if(desc == ''){
				return;
			}
			$('#desc_'+id+' p').html(desc);
			$('#edit-description').fadeOut(200);
			$(".edit-template").css({"transition": "all 0.3s linear", "right": "-50%"});
		}
		function previewemail(){
			$('#modalBody').html($("#sortable").html());
			$( "#modalBody" ).find( "span" ).remove();

			$('#myModal').modal('show');
			// alert();
		}
		function sendemail(){
			var emaildata = $('#sortable').html();
			var textarea = $('#emaildatas').val($.trim(emaildata));
			// return;
			var url = '{{url('send-email')}}';
			$.ajax({
			type    : 'POST',
			data : formData,
			async:false,
			url: url,
			beforeSend: function(request) {
			return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
			},
			success: function(response) {
				
				// if(response == 'success'){
					
				// }
				console.log(response);
			
			},
			error:function(error) {
				console.log(error);
			}
			});
		}
		function submitform(){
			// var email_data = escape()
			// $('#emaildatas').val($('#sortable').html());
			// alert(email_data);return;
			$("#emaildataform").submit(function(e){
				e.preventDefault();
				var formData = $(this).serialize();
				// alert(formData);
				var url = '{{url('send-email')}}';
				$.ajax({
				type    : 'POST',
				enctype: 'multipart/form-data',
				data : formData,
				async:false,
				url: url,
				beforeSend: function(request) {
				return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
				},
				success: function(response) {
					
					// if(response == 'success'){
						
					// }
					console.log(response);
				
				},
				error:function(error) {
					console.log(error);
				}
				});
				});
			}
	</script>
@endsection