<?php

class Application_Form_MemberChangePassForm extends Zend_Form
{
	public function init()
	{
		$this->setName('pass-update');
			    $pwd_current = new Zend_Form_Element_Password('pwd_current');
		        $pwd_current->setRequired(true)
						 ->setLabel('Current Password')
						->setAttrib("class", "form-control")
						->addValidator('StringLength', false, array(1, 20))
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->removeDecorator('HtmlTag')
						->removeDecorator('Label');
			   
				
				$pwd = new Zend_Form_Element_Password('pwd');
		                $pwd->setRequired(true)
						->setLabel('New Password')
						->addFilter('StripTags')
						
						->setAttrib("class", "form-control")
						->addValidator('StringLength', false, array(1, 20))
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->removeDecorator('HtmlTag')
						->removeDecorator('Label');
						
				$pwd_confirm = new Zend_Form_Element_Password('pwd_confirm');
		                //$pwd_confirm->setRequired(true)
						
						 $pwd_confirm->addFilter('StripTags')
					
						->setAttrib("class", "form-control")
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						
						->removeDecorator('HtmlTag')
						->setLabel('Confirm Password')
						->removeDecorator('Label');


               
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton')
				->setAttrib("class", "btn btn-primary")
				
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Update Password");
				
				
			$this->addElements(array($pwd_current, $pwd, $pwd_confirm, $submit));				
				

        }
}