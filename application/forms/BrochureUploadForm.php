<?php

class Application_Form_BrochureUploadForm extends Zend_Form
{
    public function init()
    {
        $title = new Zend_Form_Element_Text('title',array('disableLoadDefaultDecorators' =>true));
				$title->setAttrib('placeholder', 'Enter brochure title')
                    ->setAttrib('title', 'Enter brochure title') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
                    
        $image = new Zend_Form_Element_File('image');
		   $image->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))
                ->addValidator('ImageSize', false,array('minwidth' => 10,'minheight' => 10))
				->setErrorMessages(array("Choose photo:"))
				->addValidator('Extension', false, 'png,gif,jpeg,jpg');// only JPEG, PNG, and GIFs
        
        $brochure = new Zend_Form_Element_File('brochure');
        $brochure->setRequired(true)
            ->setAttrib("accept","application/pdf")
            ->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))
            ->addFilter('StringTrim')
            ->setErrorMessages(array("Choose Brochure"))
            ->addValidator('Extension', false, 'pdf');
            
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'btn btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Upload Brochure");
        
        $this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),
                array('brochure', 'title', 'image'));
                
        $this->addElements(array($brochure, $title, $image,$submit));
    }
    
} // class end