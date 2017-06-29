<?php
class Application_Form_EmailServiceForm extends Zend_Form
{
public function init() 
	{
				$this->setName('email_services');
				$this->setMethod('Post');
				$this->setAttrib('enctype', 'multipart/form-data');
				
				$api_key = new Zend_Form_Element_Text('api_key',array('disableLoadDefaultDecorators' =>true));
				$api_key->setRequired(true)
					->setLabel('* API Key:')
					->setAttrib('id', 'api_key')
                    ->setAttrib('required', 'required')
                    ->setAttrib('placeholder', 'Enter API Key')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
                $list_id = new Zend_Form_Element_Text('list_id',array('disableLoadDefaultDecorators' =>true));
				$list_id->setRequired(true)
					->setLabel('* List Id:')
					->setAttrib('id', 'list_id')
                    ->setAttrib('required', 'required')
                    ->setAttrib('placeholder', 'Enter List ID')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');                		
										
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
				$submit->setAttrib('class', 'btn btn-lg btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Save");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'div')),
				array('Label', array('tag' => 'span')),
				array('decorator' => array('div' => 'HtmlTag'), 'options' => array('tag' => 'div'))),
				array('api_key','list_id'));
						
				$this->addElements(array($api_key,$list_id,$submit));

        }
}