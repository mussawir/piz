<?php
class Application_Form_PostcodesForm extends Zend_Form
{
public function init()
	{
		$this->setName('postcodes');
		        
				$pc_id = new Zend_Form_Element_Hidden('pc_id',array('disableLoadDefaultDecorators' =>true));
             	
				$postcode = new Zend_Form_Element_Text('postcode',array('disableLoadDefaultDecorators' =>true));
				$postcode->setRequired(true)
					->setLabel('* Postcode')
					->setAttrib('id', 'postcode')
					->setAttrib("class","form-control")
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->removeDecorator('htmlTag');
				
				
				$area_name = new Zend_Form_Element_Text('area_name',array('disableLoadDefaultDecorators' =>true));
				$area_name->setRequired(true)
					->setLabel('* Main Area Name')
					->setAttrib('id', 'area_name')
					->setAttrib("class","form-control")
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->removeDecorator('htmlTag');
					
					
				$areas = new Zend_Form_Element_Textarea('areas',array('disableLoadDefaultDecorators' =>true));
		        $areas->setLabel('Areas in Postcode')
				->setAttrib('COLS', '31')
  				->setAttrib('ROWS', '5')
				->setAttrib("class", "form-control")
				->setAttrib("placeholder","separate by comma")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');

					
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
							array('country','postcode', 'area_name', 'areas'));
				
				$this->addElements(array($pc_id,$postcode,$area_name,$areas, $country, $submit));

        }
}
?>