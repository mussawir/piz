<?php
class Application_Form_PageForm extends Zend_Form
{
public function init() 
	{
				$this->setName('new_page');
				$this->setMethod('Post');
				$this->setAttrib('encrypt', 'multipart/form-data');
				
				$title = new Zend_Form_Element_Text('title',array('disableLoadDefaultDecorators' =>true));
				$title->setRequired(true)
					->setAttrib('placeholder', 'Enter page title here')
                    ->setAttrib('title', 'Enter page title here') 
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
					
				$url_slug = new Zend_Form_Element_Text('url_slug');
				$url_slug->setRequired(true)
					//->setLabel('* Page Url:')
					->setAttrib('id', 'url')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control set-txt")
					->removeDecorator('htmlTag');
				
				$is_comment = new Zend_Form_Element_Checkbox('is_comment',array('disableLoadDefaultDecorators' =>true));
				$is_comment->setAttrib("id","is_comment")
					->setAttrib("width", "20px")
					->setAttrib("height", "20px");
					
				$contents = new Zend_Form_Element_Textarea('contents',array('disableLoadDefaultDecorators' =>true));
				$contents->setRequired(true)
					->setAttrib('id', 'contents')
                    ->setAttrib('contenteditable', 'true')
					->setAttrib("row","25")
					->setAttrib("cols","60")
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->setErrorMessages(array('Must add page contents'));
				
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
				
				$tags = new Zend_Form_Element_Text('tags',array('disableLoadDefaultDecorators' =>true));
				$tags//setLabel('Tags:')
					->setAttrib('id', 'tags')
					->setAttrib('placeholder', 'Tags (Use comma to separate each tag)')
					->setAttrib('title', 'Tags (Use comma to separate each tag)')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');				
				
                $page_for = new Zend_Form_Element_Radio('page_for',array('disableLoadDefaultDecorators' =>true));
				$page_for->setAttrib("id","page_for")
					//->setAttrib("width", "20px")
					//->setAttrib("height", "20px");
                    ->addMultiOptions(array('1'=> ' Marketer', '2' =>' Business', '3' =>' Member', '0'=> ' Public'))
                    ->setValue('0')
                    ->setSeparator("&nbsp;&nbsp;");
                
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
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('title','is_comment','url_slug','contents', 'description_content','keyword_content', 'tags', 'page_for'));
						
				$this->addElements(array($title,$is_comment,$url_slug,$contents, $description_content, $tags, $keyword_content, $page_for ,$submit));

        }
}