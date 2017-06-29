<?php 
 class Application_Form_NewsletterForm extends Zend_Form
{ 
    public function init() 
    	{
		$this->setMethod('post');
		
		$cf_name = new Zend_Form_Element_Text('name'); 
        $cf_name->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter your full name"))
        ->setAttrib('placeholder','Enter your full name')
		->setAttrib("class", "form-control")
        ->setAttrib('required','required')
	   	->removeDecorator('HtmlTag')
		->removeDecorator('Label');
		
		$cf_email = new Zend_Form_Element_Text('email'); 
        $cf_email->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
		->addValidator('EmailAddress',true)
        ->setErrorMessages(array("Enter your email addresa"))
        ->setAttrib('placeholder','Enter your email address')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label')
		->setAttrib("class", "form-control")
        ->setAttrib('required','required');
		
		  $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('class', 'btn btn-success pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Subscribe");
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper'),
							array('name','email'));
				
				
		  $this->addElements(array($cf_name,$cf_email, $submit));
		
		}
		
}