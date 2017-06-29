<?php
class Application_Form_UserForm extends Zend_Form
{
public function init()
	{
		$this->setName('user');

                $user_id = new Zend_Form_Element_Hidden('user_id');
                
                $email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true));
		            $email->setRequired(true)
					->setAttrib("class", "form-control")
						->addFilter('StripTags')
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->addValidator('EmailAddress')
						->removeDecorator('HtmlTag')
						->setAttrib("placeholder", "Enter Email");
				
		$password = new Zend_Form_Element_Password('password',array('disableLoadDefaultDecorators' =>true));
		           $password->setRequired(true)
					->addFilter('StripTags')
					->setAttrib("class", "form-control")
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->addValidator('NotEmpty')
					->removeDecorator('HtmlTag')
					->setAttrib("placeholder", "Enter Password");


                $user_name = new Zend_Form_Element_Text('user_name',array('disableLoadDefaultDecorators' =>true));
		           $user_name->setRequired(true)
					->setAttrib("class", "form-control")
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->removeDecorator('HtmlTag')
					->setAttrib("placeholder", "Enter User Name");
               
			   $role = new Zend_Form_Element_Select('role',array('disableLoadDefaultDecorators' =>true));
				$role->setAttrib("id","role")
				->setAttrib("class","dropdown form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');

				$data = new Application_Model_UserRoles();
                $results = $data->getAllRolesName()->toArray();
                foreach($results as $result)$role->addMultiOption($result['role_id'], $value = $result['role']);
	                
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-md btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Save");
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('email','user_name','password','role'));
				$this->addElements(array($user_id,$email,$password,$user_name,$role,$submit));

        }
}