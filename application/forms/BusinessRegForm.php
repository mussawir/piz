<?php
class Application_Form_BusinessRegForm extends Zend_Form
{
    public function init() 
	{
	   $email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true));
		            $email->setRequired(true)
					->setAttrib("class", "form-control")
                        ->setAttrib('placeholder', 'Enter your email')
						->addFilter('StripTags')
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->removeDecorator('HtmlTag');
       
	   $first_name = new Zend_Form_Element_Text('first_name',array('disableLoadDefaultDecorators' =>true));
				$first_name->setRequired(true)
                ->setAttrib('id', 'first_name')
					->setAttrib('placeholder', 'Enter your name')
                    ->setAttrib('required', 'required')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
                    
        $contact_number = new Zend_Form_Element_Password('contact_number',array('disableLoadDefaultDecorators' =>true));
		           $contact_number->setRequired(true)
					->addFilter('StripTags')
					->setAttrib("class", "form-control")
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->addValidator('NotEmpty')
					->removeDecorator('HtmlTag')
					->setAttrib("placeholder", "Enter your phone number");
                           
        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'btn-checkout');
				$submit->setAttrib('class', 'btn btn-success form-control')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Register");
                
        $this->setElementDecorators(array(
							'Errors',
							'ViewHelper'),
							array('email','first_name','contact_number'));
	   $this->addElements(array($email,$first_name,$contact_number,$submit));
	}
} // class end