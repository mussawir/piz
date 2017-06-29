<?php
class Application_Form_MemberPageForm extends Zend_Form
{
public function init() 
	{
				$title = new Zend_Form_Element_Text('title',array('disableLoadDefaultDecorators' =>true));
				$title->setRequired(true)
					->setAttrib('placeholder', 'Enter page title here')
                    ->setAttrib('title', 'Enter page title here') 
					->setAttrib('autocomplete', 'off')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$url_slug = new Zend_Form_Element_Text('url_slug');
				$url_slug->setRequired(true)
					->setAttrib('id', 'url')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control set-txt")
					->removeDecorator('htmlTag');
				
				$banner_link = new Zend_Form_Element_Text('banner_link',array('disableLoadDefaultDecorators' =>true));
				$banner_link->setAttrib('placeholder', 'Add hyperlink to banner')
                    ->setAttrib('banner_lin', 'Add hyperlink to banner')
					->setAttrib('id', 'banner_link')					
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$is_comment = new Zend_Form_Element_Checkbox('is_comment',array('disableLoadDefaultDecorators' =>true));
				$is_comment->setAttrib("id","is_comment")
					->setAttrib("width", "20px")
					->setAttrib("height", "20px");
					
				$contents = new Zend_Form_Element_Textarea('contents',array('disableLoadDefaultDecorators' =>true));
				$contents
					->setAttrib('id', 'contents')
                    ->setAttrib('contenteditable', 'true')
					->setAttrib("row","25")
					->setAttrib("cols","60")
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					//->setRequired(true)
					->setErrorMessages(array('Must add page contents'))
					;
				
				$description_content = new Zend_Form_Element_Textarea('description_content',array('disableLoadDefaultDecorators' =>true));
				$description_content->setAttrib('id', 'description_content')
					->setAttrib('title','Description Use In SEO')
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
                    
                $business_name = new Zend_Form_Element_Text('business_name',array('disableLoadDefaultDecorators' =>true));
				$business_name->setRequired(true)
					->setAttrib('placeholder', 'Enter business name')
                    ->setAttrib('title', 'Enter business name') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');	
                
                $contact_number = new Zend_Form_Element_Text('contact_number',array('disableLoadDefaultDecorators' =>true));
				$contact_number//->setRequired(true)
					->setAttrib('placeholder', 'Enter contact number')
                    ->setAttrib('title', 'Enter contact number') 
					->setAttrib('maxlength','24')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
                    
                $page_description = new Zend_Form_Element_Textarea('page_description',array('disableLoadDefaultDecorators' =>true));
				$page_description->setRequired(true)
                    ->setAttrib('id', 'page_description')
					->setAttrib('title','Enter page description (within 255 characters)')
                    ->setAttrib('ROWS', '3')
					->setAttrib('placeholder', 'Enter page description (within 255 characters)')
					->setAttrib('maxlength','255')
                    ->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag')
					->setErrorMessages(array('Page description is required'));
                    
                $is_featured = new Zend_Form_Element_Checkbox('is_featured',array('disableLoadDefaultDecorators' =>true));
				$is_featured->setAttrib("id","is_featured")
					->setAttrib("width", "20px")
					->setAttrib("height", "20px");
                                    
                $address = new Zend_Form_Element_Textarea('address',array('disableLoadDefaultDecorators' =>true));
				$address->setAttrib('id', 'address')
					->setAttrib('title','Enter your address')
                    ->setAttrib('COLS', '10')
					->setAttrib('ROWS', '3')
					->setAttrib('placeholder', 'Enter your address')
					->setAttrib('maxlength','150')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag')
					->setErrorMessages(array('Address must be between 1 to 200 characters long'));
				
                /*$categories = new Zend_Form_Element_Select('categories',array('disableLoadDefaultDecorators' =>true));
				$categories->setAttrib("id","categories")
				->setAttrib("class", "form-control")
                ->setAttrib("OnChange","getSubCat1();")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
                
                $categories_model = new Application_Model_PageCategories();
                $results = $categories_model->fetchAll("parent_id = 0",  "category_name asc")->toArray();
                $categories->addMultiOption(0, $value = "-- Select --");
                foreach($results as $result){
                    $categories->addMultiOption($result['category_id'], $value = $result['category_name']);
                }*/
                
                $country = new Zend_Form_Element_Select('country_id',array('disableLoadDefaultDecorators' =>true));
				$country->setAttrib("id","country_id")
				//->setLabel('* Select Country')
				->setAttrib("OnChange","getState();")
				->setAttrib("class","selector")
				->setAttrib("class", "form-control")
				//->setAttrib('required', 'required')
				//->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');

				$country_model = new Application_Model_Countries();
                $results = $country_model->getAllCountries()->toArray();
                $country->addMultiOption(0, $value = "-- Select --");
                foreach($results as $result){
                    $country->addMultiOption($result['country_id'], $value = $result['country_name']);
                }
                
                $wap_number = new Zend_Form_Element_Text('wap_number',array('disableLoadDefaultDecorators' =>true));
				$wap_number->setAttrib('placeholder', 'Enter WhatsApp number')
                    ->setAttrib('title', 'Enter WhatsApp number') 
					->setAttrib('maxlength','24')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
                    
            $wechat_number = new Zend_Form_Element_Text('wechat_number',array('disableLoadDefaultDecorators' =>true));
				$wechat_number->setAttrib('placeholder', 'Enter Wechat number')
                    ->setAttrib('title', 'Enter Wechat number') 
					->setAttrib('maxlength','24')
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
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('banner_link','title','is_comment','url_slug','contents', 'description_content','keyword_content',
                      'tags', 'business_name', 'contact_number', 'address', 'page_description',
                      'is_featured', 'country_id', 'wap_number', 'wechat_number'));
						
				$this->addElements(array($banner_link, $title,$is_comment,$url_slug,$contents, $description_content, 
                $tags, $keyword_content, $business_name, $contact_number, $address, $page_description, 
                $is_featured, $country, $wap_number, $wechat_number, $submit));

        }
} ?>