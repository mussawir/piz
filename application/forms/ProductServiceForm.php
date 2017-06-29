<?php
class Application_Form_ProductServiceForm extends Zend_Form
{
    public function init() 
	{
        $name = new Zend_Form_Element_Text('name',array('disableLoadDefaultDecorators' =>true));
				$name->setRequired(true)
					->setAttrib('placeholder', 'Enter product/service name')
                    ->setAttrib('title', 'Enter product/service name') 
					->setAttrib('required', 'required')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
                    
        $photo = new Zend_Form_Element_File('photo');
		   $photo->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))
                ->addValidator('ImageSize', false,array('minwidth' => 10,'minheight' => 10))
				->setErrorMessages(array("Choose photo:"))
				->addValidator('Extension', false, 'png,gif,jpeg,jpg');// only JPEG, PNG, and GIFs
				
				$description = new Zend_Form_Element_Textarea('description',array('disableLoadDefaultDecorators' =>true));
				$description->setAttrib('title','Product/service description')
                    ->setAttrib('ROWS', '3')
					->setAttrib('placeholder', '')
					// ->setAttrib('maxlength','255')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag')
					->setErrorMessages(array('Description must be between 1 and 255 characters'));
                    
        $price = new Zend_Form_Element_Text('price',array('disableLoadDefaultDecorators' =>true));
				$price->setAttrib('placeholder', 'Enter product/service price')
                    ->setAttrib('title', 'Enter product/service price in RM') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');

        $hide_price = new Zend_Form_Element_Checkbox('hide_price',array('disableLoadDefaultDecorators' =>true));
        $hide_price->setAttrib("width", "20px")
                    ->setAttrib("height", "20px");

        $discount = new Zend_Form_Element_Text('discount',array('disableLoadDefaultDecorators' =>true));
				$discount->setAttrib('placeholder', 'Enter product/service discount')
                    ->setAttrib('title', 'Enter product/service discount') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
                    
        $hide_discount = new Zend_Form_Element_Checkbox('hide_discount',array('disableLoadDefaultDecorators' =>true));
        $hide_discount->setAttrib("width", "20px")
                    ->setAttrib("height", "20px");
                    
					
		  $price_usd = new Zend_Form_Element_Text('price_usd',array('disableLoadDefaultDecorators' =>true));
				$price_usd->setAttrib('placeholder', 'Enter product/service price in USD')
                    ->setAttrib('title', 'Enter product/service price in USD') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');

        $discount_usd = new Zend_Form_Element_Text('discount_usd',array('disableLoadDefaultDecorators' =>true));
				$discount_usd->setAttrib('placeholder', 'Enter product/service discount in USD')
                    ->setAttrib('title', 'Enter product/service discount in USD') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
        $buy_link = new Zend_Form_Element_Text('buy_link',array('disableLoadDefaultDecorators' =>true));
				$buy_link->setAttrib('placeholder', 'Enter product/service link')
                    ->setAttrib('title', 'Enter product/service link') 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
                    
        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('class', 'btn btn-success pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Save");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),
				array('name','photo','description','price', 'hide_price','discount', 'hide_discount', 'buy_link'));
					
				$this->addElements(array($name,$photo, $price, $hide_price, $discount,$price_usd, $discount_usd, $hide_discount, $description, $buy_link, $submit));
    }
}