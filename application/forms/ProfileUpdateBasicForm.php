<?php
class Application_Form_ProfileUpdateBasicForm extends Zend_Form
{
public function init()
	{
				$this->setName('basic-update');
		       
			   
				$first_name = new Zend_Form_Element_Text('first_name',array('disableLoadDefaultDecorators' =>true));
				$first_name->setRequired(true)
					->setLabel('* First Name')
					->setAttrib('id', 'first_name')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$last_name = new Zend_Form_Element_Text('last_name',array('disableLoadDefaultDecorators' =>true));
				$last_name->setRequired(true)
					->setLabel('* Last Name')
					->setAttrib('id', 'last_name')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
					
				$office_contact_number = new Zend_Form_Element_Text('office_contact_number ',array('disableLoadDefaultDecorators' =>true));
				$office_contact_number  ->setRequired(true)
					->setLabel('* Office Contact Number')
					->setAttrib('id', 'officecontactnumber ')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
					
				$contact_number = new Zend_Form_Element_Text('contact_number',array('disableLoadDefaultDecorators' =>true));
				$contact_number ->setRequired(true)
					->setLabel('* Contact Number')
					->setAttrib('id', 'contactnumber')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
			
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Update");
				
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('first_name','last_name','office_contact_number','contact_number'));
				
				$this->addElements(array($first_name,$last_name,$office_contact_number, $contact_number,$submit));

        }
}
