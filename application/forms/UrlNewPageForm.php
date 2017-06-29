<?php
class Application_Form_UrlNewPageForm extends Zend_Form
{
public function init() 
	{
				$url_slug = new Zend_Form_Element_Text('url_slug',array('disableLoadDefaultDecorators' =>true));
				$url_slug->setRequired(true)
					->setAttrib('placeholder', 'Add page address')
					->setAttrib('id', 'url_slug')
					->setAttrib('onChange', 'disableSubmit();')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control set-txt")
					->removeDecorator('htmlTag');
				
                $page_type = new Zend_Form_Element_Select('page_type',array('disableLoadDefaultDecorators' =>true));
				$page_type->setAttrib("class","selector")
				->setAttrib("class", "form-control")
				->setAttrib('required', 'required')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
					->removeDecorator('htmlTag');
				$page_type->addMultiOption("none", "-- Select --")
				->addMultiOption("Organization", "Organization")
				->addMultiOption("Product", "Product")
				->addMultiOption("Service", "Service")
				->addMultiOption("Trading", "Trading")
				->addMultiOption("Charity", "Charity")
				->addMultiOption("Religious", "Religious");
				
				
				
				
				$categories = new Zend_Form_Element_Select('categories',array('disableLoadDefaultDecorators' =>true));
				$categories->setAttrib("class","selector")
				->setAttrib("class", "form-control")
				->setAttrib('required', 'required')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
					->removeDecorator('htmlTag');
				$categories->addMultiOption('0', "-- Select --")
				->addMultiOption('1', "Administration")
				->addMultiOption('2', "Accounting & Bookkeeping")
				->addMultiOption('3', "Cleaning")
				->addMultiOption('4', "Design & Print")
				->addMultiOption('5', "Equipment, Fitouts &amp; Repairs")
				->addMultiOption('6', "Events & Exhibitions")
				->addMultiOption('7', "Finance & Insurance")
				->addMultiOption('8', "Food")
				->addMultiOption('9', "Home Based Businesses")
				->addMultiOption('10', "Human Resources & Payroll")
				->addMultiOption('11', "Legal")
				->addMultiOption('12', "Manufacturing")
				->addMultiOption('13', "Marketing & Communications")
				->addMultiOption('14', "Photography &amp; Video")
				->addMultiOption('15', "Premises & Real Estate")
				->addMultiOption('16', "Promotional Materials &amp; Gifts")
				->addMultiOption('17', "Technology")
				->addMultiOption('18', "Training & Development")
				->addMultiOption('19', "Transport")
				->addMultiOption('20', "Warehousing, Storage & Removalists")
				->addMultiOption('21', "Wholesale");
				
			
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Next");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('url_slug'));
				$this->addElements(array($url_slug, $submit));

        }
}