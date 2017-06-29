<?php 
class Application_Form_GetTestimonial extends Zend_Form
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
        ->setErrorMessages(array("Enter your email address"))
		->removeDecorator('HtmlTag')
		->removeDecorator('Label')
        ->setAttrib('required','required')
		->setAttrib("class", "form-control")
		->setAttrib('placeholder','Enter your email address');
        
        $message = new Zend_Form_Element_Textarea('message'); 
        $message->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter your testimonial"))
        ->setAttrib('placeholder','Enter your testimonial')
		->setAttrib("class", "form-control")
        ->setAttrib("rows", "5")
        ->setAttrib('required','required')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
		
		  $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('class', 'btn btn-success pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Send");
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper'),
							array('name','email', 'message'));
				
				
		  $this->addElements(array($cf_name,$cf_email, $message, $submit));
		
		}
		
}