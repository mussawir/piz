<?php

class Application_Form_ProfileDeliveryAddressForm extends Zend_Form
{
public function init()
	{
				$this->setName('delivery-address');
		       

				$street_address = new Zend_Form_Element_Text('street_address',array('disableLoadDefaultDecorators' =>true));
				$street_address->setRequired(true)
					->setLabel('* Street Address')
					->setAttrib('id', 'street_address')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$country = new Zend_Form_Element_Select('country',array('disableLoadDefaultDecorators' =>true));
				$country->setAttrib("id","country_id")
				->setLabel('* Select Country')
				->setAttrib("OnChange","getState();")
				->setAttrib("class","selector")
				->setAttrib("class", "form-control")
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');

				$contr = new Application_Model_Countries();
                $results = $contr->fetchAll("lang_id = " . Zend_Registry::get('lang_id'), "country_name asc")->toArray();
                $country->addMultiOption(0, $value = "--------");
                foreach($results as $result)$country->addMultiOption($result['country_id'], $value = $result['country_name']);

			
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
							array('street_address', 'country'));
				
				$this->addElements(array($country,$street_address,$submit));

        }
}