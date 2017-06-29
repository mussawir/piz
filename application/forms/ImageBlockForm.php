<?php
class Application_Form_ImageBlockForm extends Zend_Form
{

public function init()
	{

	$this->setName('image_block');

	$block = new Zend_Form_Element_File('block');
	$block->setRequired(false)
	->addValidator('Count', false, 1)     // ensure only 1 file
	->addValidator('FilesSize',false,array('min' => '1kB', 'max' => '5MB'))
	->addValidator('ImageSize', false,
                            array('minwidth' => 10,
                            'minheight' => 10)
                )
                ->addFilter('StringTrim')
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpeg,jpg,png,gif');// only JPEG, PNG, and GIFs


			$caption = new Zend_Form_Element_Text('caption',array('disableLoadDefaultDecorators' =>true));
			$caption->setAttrib('id', 'caption')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				//->addFilter('htmlentities')
				->setAttrib("class", "form-control")
				->removeDecorator('htmlTag');

			$disable_link = new Zend_Form_Element_Checkbox('disable_link',array('disableLoadDefaultDecorators' =>true));
				$disable_link->setAttrib("id","disable_link")
				->setAttrib("class", "float-left")
				->addFilter('StringTrim')
				->removeDecorator('htmlTag');

			$link = new Zend_Form_Element_Text('link',array('disableLoadDefaultDecorators' =>true));
			$link->setAttrib('id', 'link')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->setAttrib("class", "form-control")
				->removeDecorator('htmlTag');

		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-middium btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Update");

				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('block','link','caption','disable_link'));

				$this->addElements(array( $block,$link,$caption,$disable_link,$submit));

        }
}