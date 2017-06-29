<?php
class Application_Form_CitiesForm extends Zend_Form
{
public function init()
	{
		$this->setName('cities');
		        $city_id = new Zend_Form_Element_Hidden('city_id',array('disableLoadDefaultDecorators' =>true));
             	
				$city_name = new Zend_Form_Element_Text('city_name',array('disableLoadDefaultDecorators' =>true));
				$city_name->setRequired(true)
					->setLabel('* City Name')
					->setAttrib('id', 'city_name')
					->setAttrib("class","form-control")
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->removeDecorator('htmlTag');
					
					
				$country = new Zend_Form_Element_Select('country',array('disableLoadDefaultDecorators' =>true));
				$country->setAttrib("id","country_id")
				->setLabel('* Select Country')
				->setAttrib("class","dropdown form-control")
				->setAttrib("OnChange","getState();")
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');

				$cat = new Application_Model_Countries();
                $results = $cat->fetchAll("lang_id = " . Zend_Registry::get('lang_id'),  "country_name asc")->toArray();
                $country->addMultiOption(0, $value = "--------");
                foreach($results as $result)$country->addMultiOption($result['country_id'], $value = $result['country_name']);

/* We are using this form for ajax communication so we don't need submit button */         
		        $submit = new Zend_Form_Element_Submit('Save');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary btn-block')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('country','city_name'));
				
				$this->addElements(array($city_id,$city_name,$country, $submit));

				

        }
}
?>