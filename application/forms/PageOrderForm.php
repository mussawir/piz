<?php
class Application_Form_PageOrderForm extends Zend_Form
{
public function init()
	{
				$name = new Zend_Form_Element_Text('name',array('disableLoadDefaultDecorators' =>true));
		        $name->setRequired(true)
					->setAttrib("class", "form-control")
					->setAttrib("required", "required")
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->removeDecorator('HtmlTag')
					->setAttrib("placeholder", "Enter Your Name");
					
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
				
		
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Submit Order");
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('email','user_name','password','role'));
				$this->addElements(array($name, $contact_number,$email,$submit));

        }
}