<?php 
 class Application_Form_ContactForm extends Zend_Form
{ 
    public function init() 
    	{
		$this->setMethod('post');
		
		$cf_name = new Zend_Form_Element_Text('cf_name'); 
        $cf_name->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter your name"))
		->setAttrib("class", "form-control")
       ->setAttrib('placeholder','Enter your name')
       ->setAttrib('required','required')
	   	->removeDecorator('HtmlTag')
		->removeDecorator('Label');
		
		$cf_email = new Zend_Form_Element_Text('cf_email'); 
        $cf_email->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
		->addValidator('EmailAddress',true)
        ->setErrorMessages(array("Enter your email address"))
		->removeDecorator('HtmlTag')
		->removeDecorator('Label')
		->setAttrib("class", "form-control")
        ->setAttrib('required','required')
		->setAttrib('placeholder','Enter your email address');
		
		
		$cf_message = new Zend_Form_Element_Textarea('cf_message'); 
        $cf_message->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter your message"))
		->setAttrib("class", "form-control")
        ->setAttrib("rows", "5")
        ->setAttrib('required','required')
       ->setAttrib('placeholder','Enter your message')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
		
		
		  $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'send')
				->setAttrib('name', 'send')
				->setAttrib('class', 'btn btn-success pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Send");
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('cf_name','cf_email', 'cf_message'));
				
				
		$this->addElements(array($cf_name,$cf_email,$cf_message, $submit));
		
		}
		
		
		}