<?php
class Application_Form_CategoryForm extends Zend_Form
{

public function init()
	{
		$this->setName('category');
		$this->setAttrib('enctype', 'multipart/form-data');
          
		        $category_name = new Zend_Form_Element_Text('category_name');
				$category_name->setLabel('* Category Name:')
				->setAttrib("class", "form-control")
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
			
			    $code = new Zend_Form_Element_Text('code');
				$code->setLabel('* Code:')
				->setAttrib("class", "form-control")
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
		
			   
			   $parent_id = new Zend_Form_Element_Select('parent_id',array('disableLoadDefaultDecorators' =>true));
				$parent_id->setAttrib("id","parent_id")
				->setLabel('* Select Top Category')
				->setAttrib("class","dropdown form-control")
				->setAttrib("OnChange","getSubCat1();")
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');

				$cat = new Application_Model_PageCategories();
                $results = $cat->fetchAll("parent_id = 0",  "category_name asc")->toArray();
                $parent_id->addMultiOption(0, $value = "-- Select --");
                foreach($results as $result)$parent_id->addMultiOption($result['category_id'], $value = $result['category_name']);
			   
			   
			    $is_main = new Zend_Form_Element_Checkbox('is_main');
				$is_main->setLabel('Is Main:');
		
				$is_featured = new Zend_Form_Element_Checkbox('is_featured');
				$is_featured->setLabel('Is Featured:');
		
             
                $category_image = new Zend_Form_Element_File('myfile');
		$category_image->addValidator('Count', false, 1)     // ensure only 1 file
		->addValidator('Size', false, array('min' => '10kB', 'max' => '2MB')) 
		->addValidator('Extension', false, 'jpg,png,gif') ;// only JPEG, PNG, and GIFs

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('class', 'submitbutton');
		$submit->setDecorators(array(
 		   	'ViewHelper',
    		array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
    		array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
  			array(array('row' => 'HtmlTag'), array('tag' => 'tr')),));
  		//$this->setAttrib("action", "/admin/category/save-edit");
		$this->addElements(array($category_name, $code, $parent_id,$category_image,$is_main,$is_featured, $submit));
                $category_image->removeDecorator('label');
                $category_image->removeDecorator('htmlTag'); //DtDd

		$this->setElementDecorators(
				array(
                'Errors',
				'ViewHelper',
				array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
			    array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))
				),
				array(
              			'category_name','code','parent_id','category_image','is_main','is_featured')
				);

        }
}
