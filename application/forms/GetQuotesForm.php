<?php
class Application_Form_GetQuotesForm extends Zend_Form{
private $db = null;
public function init()
{
		$this->setMethod('post');
		$this->setName('get-quote');
		$this->db = Zend_Db_Table::getDefaultAdapter();
		
		$fname = new Zend_Form_Element_Text('fname'); 
        $fname->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter First Name"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');

		$lname = new Zend_Form_Element_Text('lname'); 
        $lname->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter Last Name"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');

		$bname = new Zend_Form_Element_Text('bname'); 
        $bname->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter Business Name"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');

		$phone = new Zend_Form_Element_Text('phone'); 
        $phone->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter Phone Number"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');

		$email = new Zend_Form_Element_Text('email'); 
        $email->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter Email Address"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
	

		$topic = new Zend_Form_Element_Text('topic'); 
		$topic->setRequired(true) 
		->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter What are you looking for?"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');

		$location = new Zend_Form_Element_Text('location'); 
		$location->setRequired(true) 
		->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter Suburb/City"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
	
		

		/*$topic = new Zend_Form_Element_Select('topic'); 
		$topic->addFilter('StripTags') 
        ->addFilter('StringTrim') 
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
*/     //->addMultiOptions(array('1'=>‘LOREM IPSUM DOLOR SIT AMET’,'2'=>‘LOREM IPSUM DOLOR SIT AMET’));
// $countriesList = Application_Model_Countries::getAllCountriesList($this->db);
		// $country = new Zend_Form_Element_Select('country'); 
        // $country->addFilter('StripTags') 
        // ->addFilter('StringTrim') 
		// ->setAttrib("class", "form-control")
        // ->setAttrib('placeholder','Country')
		// ->removeDecorator('HtmlTag')
		// ->removeDecorator('Label')
		// ->addMultiOptions($countriesList);
		// $statesList = Application_Model_Countries::getAllStatesList($this->db);
		// $states = new Zend_Form_Element_Select('states'); 
        // $states->addFilter('StripTags') 
        // ->addFilter('StringTrim') 
		// ->setAttrib("class", "form-control")
        // ->setAttrib('placeholder','State')
		// ->removeDecorator('HtmlTag')
		// ->removeDecorator('Label')
		// ->addMultiOptions($statesList);

		$postcode = new Zend_Form_Element_Text('postcode'); 
        $postcode->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter Post Code"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
	
		$price = new Zend_Form_Element_Select('price',array('disableLoadDefaultDecorators' =>true));
				$price->setAttrib("id","price")
				//->setLabel('Enter Price Range')
				->setAttrib("class","dropdown form-control")
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
				$price->addMultiOption('RM0-250', $value = "RM0-250");
				$price->addMultiOption('RM251-1000', $value = "RM251-1000");
				$price->addMultiOption('RM1000+', $value = "RM1000+");

	/*	$detail = new Zend_Form_Element_Text('detail'); 
        $detail->addFilter('StripTags') 
        ->addFilter('StringTrim') 
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
*/
		/*$subtotal = new Zend_Form_Element_Text('subtotal'); 
        $subtotal->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter Sub Total"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');*/

		/*$promocode = new Zend_Form_Element_Text('promocode'); 
        $promocode->addFilter('StripTags') 
        ->addFilter('StringTrim') 
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');*/
		
		$agree = new Zend_Form_Element_Checkbox('agree'); 
        $agree->setRequired(true)
    	->addValidator(new Zend_Validate_InArray(array(1)), false)
		 ->setErrorMessages(array("Please select Terms & Condtions checkbox to proceed"))
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
		

		/*$total = new Zend_Form_Element_Text('total'); 
        $total->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Enter Total"))
		->setAttrib("class", "form-control")
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');*/
		$submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Send");

				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('fname','lname', 'bname','phone','email','location','topic','country','states','postcode','detail'));
		$this->addElements(array($fname,$lname,$bname,$phone,$email,$location,$topic, $postcode, $price, $agree));
				
}
}