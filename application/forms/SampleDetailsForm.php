<?php
class Application_Form_SampleDetailsForm extends Zend_Form
{
    public function init()
    {
        $name = new Zend_Form_Element_Text('name',array('disableLoadDefaultDecorators' =>true));
				$name->setRequired(true)
                    ->setAttrib('required', 'required')
                    ->setAttrib('placeholder', 'Enter your full name')
                    ->setAttrib('title', 'Enter your full name') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
        
        $biz_name = new Zend_Form_Element_Text('biz_name',array('disableLoadDefaultDecorators' =>true));
				$biz_name->setAttrib('placeholder', 'Enter business name')
                    ->setAttrib('title', 'Enter business name') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
                    
        $contact_number = new Zend_Form_Element_Text('contact_number',array('disableLoadDefaultDecorators' =>true));
		        $contact_number->setRequired(true)
					->setAttrib("class", "form-control")
						->setAttrib("required", "required")
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->removeDecorator('HtmlTag')
					->setAttrib("placeholder", "Enter Your Contact Number");
				
                $email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true));
		            $email->setRequired(true)
					->setAttrib("class", "form-control")
						->setAttrib("required", "required")
						->setAttrib("type", "email")
						->setAttrib("pattern", "[^ @]*@[^ @]*")
						->addFilter('StripTags')
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->addValidator('EmailAddress')
						->removeDecorator('HtmlTag')
						->setAttrib("placeholder", "Enter Your Email Address");
                           
        $biz_req = new Zend_Form_Element_Textarea('biz_requirements'); 
        $biz_req->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Expalin your requirements"))
		->setAttrib("class", "form-control")
        ->setAttrib("rows", "5")
        ->setAttrib('placeholder','Expalin your requirements')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'btn btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Next");
        
        $this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),
                array('name', 'biz_name', 'contact_number', 'email', 'biz_requirements'));
                
        $this->addElements(array($name, $biz_name, $contact_number, $email, $biz_req, $submit));
     }
} // class end