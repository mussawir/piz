<?php
class Application_Form_CountryForm extends Zend_Form
{
public function init()
	{
		$this->setName('country');
		$this->setAttrib('enctype', 'multipart/form-data');
			
		$country_name = new Zend_Form_Element_Text('country_name',array('disableLoadDefaultDecorators' =>true));
		$country_name->setRequired(true)
					->setLabel('* Country Name')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib('class', 'form-control')
					->removeDecorator('HtmlTag')
				->removeDecorator('Label');
					
					$country_code = new Zend_Form_Element_Text('country_code',array('disableLoadDefaultDecorators' =>true));
					$country_code->setRequired(true)
					->setLabel('* Country Code')
					->setAttrib('class', 'form-control')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setRequired("true")
					->removeDecorator('HtmlTag')
				->removeDecorator('Label');
					
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('country_code','country_name'));
				
				$this->addElements(array($country_name,$country_code));
        }
}
