<?php
class Application_Form_PostForm extends Zend_Form
{
public function init() 
	{
				$this->setName('add_new_post');
				$this->setMethod('Post');
				$this->setAttrib('encrypt', 'multipart/form-data');
				
				$heading = new Zend_Form_Element_Text('heading',array('disableLoadDefaultDecorators' =>true));
				$heading->setRequired(true)
					->setAttrib('placeholder', 'Enter post title here')
                    ->setAttrib('title', 'Enter post title here') 
					->setAttrib('required', 'required')
					->setAttrib('onCopy', 'return false')
					->setAttrib('onDrag', 'return false')
					->setAttrib('onDrop', 'return false')
					->setAttrib('onPaste', 'return false')
					->setAttrib('autocomplete', 'off')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				 
				$url = new Zend_Form_Element_Text('url');
				$url->setAttrib('placeholder', 'Change Url')
                    ->setAttrib('title', 'Change Url')
					->setAttrib('id', 'url')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control set-txt")
					->removeDecorator('htmlTag');
				
				$contents = new Zend_Form_Element_Textarea('contents',array('disableLoadDefaultDecorators' =>true));
				$contents->setRequired(true)
					->setAttrib('id', 'contents')
                    ->setAttrib('contenteditable', 'true')
					->setAttrib("row","25")
					->setAttrib("cols","60")
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->setErrorMessages(array('Must add post contents'));
		
				$is_comment = new Zend_Form_Element_Checkbox('is_comment',array('disableLoadDefaultDecorators' =>true));
				$is_comment->setAttrib("id","is_comment")
					->setAttrib("width", "20px")
					->setAttrib("height", "20px");
					
		        $tags = new Zend_Form_Element_Text('tags',array('disableLoadDefaultDecorators' =>true));
				$tags//setLabel('Tags:')
					->setAttrib('id', 'tags')
					->setAttrib('placeholder', 'Separate tags with comma')
					->setAttrib('title', 'Separate tags with comma')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
		
               $image = new Zend_Form_Element_File('image');
			   $image->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('ImageSize', false,
                      array('minwidth' => 100,
                            'maxwidth' => 2000,
                            'minheight' => 100,
							'maxheight' => 2000))
				->addValidator('Size', false, 1000240000 ) 
				->setErrorMessages(array("Upload an image:"))
				->addValidator('Extension', false, 'jpg,png,gif,jpeg,jpg');// only JPEG, PNG, and GIFs
				
				$description_content = new Zend_Form_Element_Textarea('description_content',array('disableLoadDefaultDecorators' =>true));
				$description_content->setAttrib('id', 'description_content')
					->setAttrib('title','Description Use In SEO')
                    //->setAttrib('contenteditable', 'true')
					->setAttrib('COLS', '10')
					->setAttrib('ROWS', '3')
					->setAttrib('placeholder', 'Description Use In SEO')
					->setAttrib('maxlength','155')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag')
					->setErrorMessages(array('Text must be between 1 and 155 characters'));
					
					
				$keyword_content = new Zend_Form_Element_Text('keyword_content',array('disableLoadDefaultDecorators' =>true));
				$keyword_content->setAttrib('id', 'keyword_content')
					->setAttrib('placeholder', 'Keywords Use In SEO (Use comma to separate keywords)')
					->setAttrib('title','Keywords Use In SEO (Use comma to separate keywords)')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
													
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Publish");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('heading','url','contents','image','tags','description_content','keyword_content', 'is_comment'));
					
				$this->addElements(array($heading,$contents, $url,$image,$tags,$description_content,$keyword_content,$is_comment,$submit));

        }
}