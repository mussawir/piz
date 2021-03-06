<?php
class Application_Form_GalleryForm extends Zend_Form
{
public function init() 
	{
			  $this->setName('photo_gallery');
					
              $photo_name = new Zend_Form_Element_File('photo_name');
			  $photo_name->setRequired(true) 
			  ->addValidator('Count', false, 1)     // ensure only 1 file
	          ->addValidator('FilesSize',false,array('min' => '1kB', 'max' => '5MB'))
	          ->addValidator('ImageSize', false,
                array('minwidth' => 10,
                'minheight' => 10))
                ->addFilter('StringTrim')
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpeg,jpg,png,gif');// only JPEG, PNG, and GIFs
				
				$caption = new Zend_Form_Element_Text('caption',array('disableLoadDefaultDecorators' =>true));
				$caption->setAttrib('id', 'caption')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
                    ->setAttrib("placeholder", "Enter caption here")
                    ->setAttrib("title", "Enter caption here")
					->removeDecorator('htmlTag');
				
				$link = new Zend_Form_Element_Text('link',array('disableLoadDefaultDecorators' =>true));
				$link->setAttrib('id', 'link')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
                    ->setAttrib("placeholder", "Enter link here")
                    ->setAttrib("title", "Enter link here")
					->removeDecorator('htmlTag');	
					
				$description = new Zend_Form_Element_Textarea('description',array('disableLoadDefaultDecorators' =>true));
				$description->setAttrib('id', 'editor1')
					->setAttrib('name', 'description')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
                    ->setAttrib("placeholder", "Enter description here")
                    ->setAttrib("title", "Enter description here")
					->removeDecorator('htmlTag');
				
				$category = new Zend_Form_Element_Select('category',array('disableLoadDefaultDecorators' =>true));
				$category->setAttrib("id","category")
				->setAttrib("class","dropdown form-control")
				//->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');

				$data = new Application_Model_PGCategory();
                $results = $data->getAllCategoriesNames()->toArray();
                //$category->addMultiOption(0, $value = '-- Select --');
                foreach($results as $result) {
                    $category->addMultiOption($result['pg_cat_id'], $value = $result['category_name']);
				}

		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
				$submit->setAttrib('class', 'btn btn-lg btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Save");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),
				//array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				//array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('photo_name','category','caption','description','link'));
						
				$this->addElements(array($photo_name,$category,$caption,$description,$link,$submit));

        }
}