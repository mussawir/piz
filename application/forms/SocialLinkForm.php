<?php
class Application_Form_SocialLinkForm extends Zend_Form
{
public function init()
	{
				$this->setName('social_link');
				$this->setMethod('Post');
				
				$facebook = new Zend_Form_Element_Text('facebook',array('disableLoadDefaultDecorators' =>true));
				$facebook->setAttrib('id', 'facebook')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$twitter = new Zend_Form_Element_Text('twitter',array('disableLoadDefaultDecorators' =>true));
				$twitter->setAttrib('id', 'twitter')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
		
				$youtube = new Zend_Form_Element_Text('youtube',array('disableLoadDefaultDecorators' =>true));
				$youtube->setAttrib('id', 'youtube')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$instagram = new Zend_Form_Element_Text('instagram',array('disableLoadDefaultDecorators' =>true));
				$instagram->setAttrib('id', 'instagram')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$tumblr = new Zend_Form_Element_Text('tumblr',array('disableLoadDefaultDecorators' =>true));
				$tumblr->setAttrib('id', 'tumblr')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$google_plus = new Zend_Form_Element_Text('google_plus',array('disableLoadDefaultDecorators' =>true));
				$google_plus->setAttrib('id', 'google_plus')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				
				$linkedin = new Zend_Form_Element_Text('linkedin',array('disableLoadDefaultDecorators' =>true));
				$linkedin->setAttrib('id', 'linkedIn')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');

				/*$pinterest = new Zend_Form_Element_Text('pinterest',array('disableLoadDefaultDecorators' =>true));
				$pinterest->setAttrib('id', 'pinterest')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$dailymotion = new Zend_Form_Element_Text('dailymotion',array('disableLoadDefaultDecorators' =>true));
				$dailymotion->setAttrib('id', 'dailymotion')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$vimeo = new Zend_Form_Element_Text('vimeo',array('disableLoadDefaultDecorators' =>true));
				$vimeo->setAttrib('id', 'vimeo')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');*/
					

		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-md btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Update");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('twitter','facebook','tumblr','google_plus','youtube','linkedin','instagram'));
				
				$this->addElements(array($twitter,$google_plus,$tumblr,$facebook,$youtube,$instagram,$linkedin,$submit));

        }
}