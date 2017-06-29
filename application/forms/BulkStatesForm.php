<?php
class Application_Form_BulkStatesForm extends Zend_Form
{
public function init()
	{
		$this->setName('states');
		$this->setAttrib('enctype', 'multipart/form-data');

        $country = new Zend_Form_Element_Select('country');
				$country->setAttrib("id","country_id")
				->setAttrib("class","form-control")
				->setAttrib("style","width: 235px;")
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->removeDecorator('DtDdWrapper');
					
				$cat = new Application_Model_Countries();
                $results = $cat->fetchAll("lang_id = " . Zend_Registry::get('lang_id'))->toArray();
                $country->addMultiOption(0, $value = "--------");
                foreach($results as $result)$country->addMultiOption($result['country_id'], $value = $result['country_name']);



		
		$bulk_states = new Zend_Form_Element_File('bulk_states');
		$bulk_states->addValidator('Count', false, 1)     // ensure only 1 file
				   ->addValidator('Size', false, 1000240000 ) // 
				   ->addValidator('Extension', true, 'csv')     // only excel
				   ->removeDecorator('DtDdWrapper');
							
		          

             

		        $submit = new Zend_Form_Element_Submit('Save');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary btn-block')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
				
				$this->addElements(array($country,$bulk_states,$submit));

				

        }
}
?>