<?php
class Application_Form_MembersForm extends Zend_Form
{
public function init()
	{
		$this->setName('Members');
		$this->setAttrib('enctype', 'multipart/form-data');

                $member_id = new Zend_Form_Element_Hidden('rm_id');
                
                $firstname = new Zend_Form_Element_Text('first_name',array('disableLoadDefaultDecorators' =>true));
		                 $firstname->setRequired(true)
				->setAttrib("class", "form-control")
				->setLabel('* First Name')
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
                   
                $lastname = new Zend_Form_Element_Text('last_name',array('disableLoadDefaultDecorators' =>true));
		                 $lastname
				//->setRequired(true)
				->setLabel('Last Name')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				//->addValidator('NotEmpty')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
				
				$email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true));
		                 $email->setRequired(true)
				->setAttrib("class", "form-control")
				->setLabel('* Email')
				->addFilter('StripTags')
				->addFilter('StringTrim') 
				->addValidator('NotEmpty')
				->addValidator('EmailAddress',true)
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
				
                $role_id = new Zend_Form_Element_Select('role_id',array('disableLoadDefaultDecorators' =>true));
				$role_id->setAttrib("class", "form-control")
                ->setLabel('* Member Role')
    				->addFilter('StripTags')
    				->addFilter('StringTrim')
    				->removeDecorator('htmlTag');
                    
                $role_id->addMultiOption(2, $value = "Business");
				$role_id->addMultiOption(3, $value = "Marketer");
                $role_id->addMultiOption(1, $value = "Free Member");
				
                $contact_number = new Zend_Form_Element_Text('contact_number',array('disableLoadDefaultDecorators' =>true));
		       	$contact_number->setRequired(true)
				->setLabel('* Contact Number')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
				
				$ic_passport = new Zend_Form_Element_Text('ic_passport',array('disableLoadDefaultDecorators' =>true));
		       	$ic_passport->setLabel('IC/Passport')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
                
                $gender = new Zend_Form_Element_Radio('gender',array('disableLoadDefaultDecorators' =>true));
				$gender->addMultiOptions(array(
				'1' => ' Male',
				'2' => ' Female'));
				
				$gender->setLabel('* Gender')
					->setAttrib('id','gender')
					->setRequired(true);
                    
                $photo = new Zend_Form_Element_File('photo');
                $photo->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('ImageSize', false,
                      array('minwidth' => 100,
                            'maxwidth' => 2000,
                            'minheight' => 100,
							'maxheight' => 2000))
				->addValidator('Size', false, 1000240000 ) 
                //->setErrorMessages(array("Photo (optional):"))
				->addValidator('Extension', false, 'jpg,png,gif,jpeg,jpg');// only JPEG, PNG, and GIFs
                
                $address = new Zend_Form_Element_Textarea('street_address',array('disableLoadDefaultDecorators' =>true));
				$address->setAttrib('id', 'address')
                    ->setLabel('* Address')
                    ->setRequired(true)
                    ->setAttrib('COLS', '20')
					->setAttrib('ROWS', '5')
					->setAttrib('maxlength','150')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					//->setErrorMessages(array('Text must be between 1 and 155 characters'));
					
				 $password = new Zend_Form_Element_Text('password',array('disableLoadDefaultDecorators' =>true));
		                 $password
				->setRequired(true)
				->setLabel('* Password')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
				
                
		        $submit = new Zend_Form_Element_Submit('Save');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
                
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('first_name','last_name','email','password', 'role_id','contact_number', 'ic_passport', 'gender', 'photo', 'street_address'));
				
				$this->addElements(array($member_id,$email,$firstname,$lastname,$password, $role_id, $contact_number,$ic_passport,$gender,$photo,$address,$submit));		

        }
}
?>