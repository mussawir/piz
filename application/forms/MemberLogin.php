<?php 
 class Application_Form_MemberLogin extends Zend_Form
{ 
	
    public function init() 
    	{ 
	 $this->setDisableLoadDefaultDecorators(true);
    		
	  $this->setName('Member Login'); 

		$email = new Zend_Form_Element_Text('email'); 
       $email->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter your email"))
        ->addValidator('EmailAddress',true)
		->addValidator('NotEmpty')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label')
		->setAttrib('class','form-control')
		->setAttrib('place-holder','Email Address as ID');

/* password */
 $password = new Zend_Form_Element_Password('password'); 
        $password->setLabel('password')
        ->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Password required"))
        ->addValidator('NotEmpty')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label')
		->setAttrib('class','form-control')
		->setAttrib('place-holder','Password');
		
  	
		
        $submit = new Zend_Form_Element_Submit('submit'); 
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("login")
		->removeDecorator('Errors')
		->setAttrib('class','form-control')
		->setAttrib('place-holder','Email address')
		->setAttrib('required','')
					->removeDecorator('HtmlTag')
					->removeDecorator('Label'); 
 		$this->setAction('login');
		$this->setMethod('Post');  
		$this->addElements(array($email, $password, $submit)); 
	   
	} 

} 

