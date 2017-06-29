<?php
class Application_Form_BulkCitiesForm extends Zend_Form
{
public function init()
	{
		$this->setName('states');
		$this->setAttrib('enctype', 'multipart/form-data');

        $country = new Zend_Form_Element_Select('country',array('disableLoadDefaultDecorators' =>true));
				$country->setAttrib("id","country_id")
				->setLabel('* Select Country')
				->setAttrib("class","selector")
				->setAttrib("style","width: 235px;font:bold")
				->setAttrib("OnChange","getState();")
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');

				$cat = new Application_Model_Countries();
                $results = $cat->fetchAll("lang_id = " . Zend_Registry::get('lang_id'))->toArray();
                $country->addMultiOption(0, $value = "--------");
                foreach($results as $result)$country->addMultiOption($result['country_id'], $value = $result['country_name']);

		
		$bulk_cities = new Zend_Form_Element_File('bulk_cities');
		$bulk_cities->addValidator('Count', false, 1)     // ensure only 1 file
				   ->addValidator('Size', false, 1000240000 ) // 
				   ->addValidator('Extension', true, 'csv')     // only excel
				   ->removeDecorator('DtDdWrapper');
							
		          

             

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
							array('country'));
				
				$this->addElements(array($country,$bulk_cities,$submit));

				

        }
}
?>