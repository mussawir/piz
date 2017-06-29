<?php

class Application_Form_PageAdsForm extends Zend_Form
{
    public function init()
    {
        // ad block 1
        $ad_title1 = new Zend_Form_Element_Text('ad_title1', array('disableLoadDefaultDecorators' => true));
        $ad_title1->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad title here')->setAttrib('title',
            'Enter ad title here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $ad_target_url1 = new Zend_Form_Element_Text('ad_target_url1', array('disableLoadDefaultDecorators' => true));
        $ad_target_url1->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad target url here')->setAttrib('title',
            'Enter ad target url here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $ad_type1 = new Zend_Form_Element_Radio('ad_type1',array('disableLoadDefaultDecorators' =>true));
        $ad_type1//->setAttrib("id","page_for")
		->addMultiOptions(array('1'=> ' Image', '2' =>' Text', '3' =>' Image & Text'))
        ->setValue('1')->setSeparator("&nbsp;&nbsp;");
        
        $ad_image1 = new Zend_Form_Element_File('ad_image1');
        $ad_image1->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs
            
        $ad_text1 = new Zend_Form_Element_Textarea('ad_text1',array('disableLoadDefaultDecorators' =>true));
		$ad_text1->setAttrib('contenteditable', 'true')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
                    
        // ad block 2
        $ad_title2 = new Zend_Form_Element_Text('ad_title2', array('disableLoadDefaultDecorators' => true));
        $ad_title2->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad title here')->setAttrib('title',
            'Enter ad title here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $ad_target_url2 = new Zend_Form_Element_Text('ad_target_url2', array('disableLoadDefaultDecorators' => true));
        $ad_target_url2->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad target url here')->setAttrib('title',
            'Enter ad target url here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $ad_type2 = new Zend_Form_Element_Radio('ad_type2',array('disableLoadDefaultDecorators' =>true));
        $ad_type2//->setAttrib("id","page_for")
		->addMultiOptions(array('1'=> ' Image', '2' =>' Text', '3' =>' Image & Text'))
        ->setValue('1')->setSeparator("&nbsp;&nbsp;");
        
        $ad_image2 = new Zend_Form_Element_File('ad_image2');
        $ad_image2->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs
            
        $ad_text2 = new Zend_Form_Element_Textarea('ad_text2',array('disableLoadDefaultDecorators' =>true));
		$ad_text2->setAttrib('contenteditable', 'true')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
        
        // ad block 3
        $ad_title3 = new Zend_Form_Element_Text('ad_title3', array('disableLoadDefaultDecorators' => true));
        $ad_title3->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad title here')->setAttrib('title',
            'Enter ad title here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $ad_target_url3 = new Zend_Form_Element_Text('ad_target_url3', array('disableLoadDefaultDecorators' => true));
        $ad_target_url3->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad target url here')->setAttrib('title',
            'Enter ad target url here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $ad_type3 = new Zend_Form_Element_Radio('ad_type3',array('disableLoadDefaultDecorators' =>true));
        $ad_type3//->setAttrib("id","page_for")
		->addMultiOptions(array('1'=> ' Image', '2' =>' Text', '3' =>' Image & Text'))
        ->setValue('1')->setSeparator("&nbsp;&nbsp;");
        
        $ad_image3 = new Zend_Form_Element_File('ad_image3');
        $ad_image3->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs
            
        $ad_text3 = new Zend_Form_Element_Textarea('ad_text3',array('disableLoadDefaultDecorators' =>true));
		$ad_text3->setAttrib('contenteditable', 'true')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
        
        // ad block 4
        $ad_title4 = new Zend_Form_Element_Text('ad_title4', array('disableLoadDefaultDecorators' => true));
        $ad_title4->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad title here')->setAttrib('title',
            'Enter ad title here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $ad_target_url4 = new Zend_Form_Element_Text('ad_target_url4', array('disableLoadDefaultDecorators' => true));
        $ad_target_url4->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad target url here')->setAttrib('title',
            'Enter ad target url here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $ad_type4 = new Zend_Form_Element_Radio('ad_type4',array('disableLoadDefaultDecorators' =>true));
        $ad_type4//->setAttrib("id","page_for")
		->addMultiOptions(array('1'=> ' Image', '2' =>' Text', '3' =>' Image & Text'))
        ->setValue('1')->setSeparator("&nbsp;&nbsp;");
        
        $ad_image4 = new Zend_Form_Element_File('ad_image4');
        $ad_image4->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs
            
        $ad_text4 = new Zend_Form_Element_Textarea('ad_text4',array('disableLoadDefaultDecorators' =>true));
		$ad_text4->setAttrib('contenteditable', 'true')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
        
        // ad banner 1
        $banner_title1 = new Zend_Form_Element_Text('banner_title1', array('disableLoadDefaultDecorators' => true));
        $banner_title1->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad banner title here')->setAttrib('title',
            'Enter ad banner title here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $banner_url1 = new Zend_Form_Element_Text('banner_url1', array('disableLoadDefaultDecorators' => true));
        $banner_url1->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad banner target url here')->setAttrib('title',
            'Enter ad banner target url here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
                    
        $banner_img1 = new Zend_Form_Element_File('banner_img1');
        $banner_img1->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs
        
        // ad banner 2
        $banner_title2 = new Zend_Form_Element_Text('banner_title2', array('disableLoadDefaultDecorators' => true));
        $banner_title2->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad banner title here')->setAttrib('title',
            'Enter ad banner title here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $banner_url2 = new Zend_Form_Element_Text('banner_url2', array('disableLoadDefaultDecorators' => true));
        $banner_url2->setRequired(true)
            ->setAttrib('placeholder', 'Enter ad banner target url here')->setAttrib('title',
            'Enter ad banner target url here')->setAttrib('required', 'required')
            ->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
                    
        $banner_img2 = new Zend_Form_Element_File('banner_img2');
        $banner_img2->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs
        
        $this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),
                array('ad_title1', 'ad_target_url1', 'ad_type1', 'ad_image1', 'ad_text1',
                    'ad_title2', 'ad_target_url2', 'ad_type2', 'ad_image2', 'ad_text2',
                    'ad_title3', 'ad_target_url3', 'ad_type3', 'ad_image3', 'ad_text3',
                    'ad_title4', 'ad_target_url4', 'ad_type4', 'ad_image4', 'ad_text4',
                    'banner_title1', 'banner_url1', 'banner_img1',
                    'banner_title2', 'banner_url2', 'banner_img2'));
                
        $this->addElements(array($ad_title1, $ad_target_url1, $ad_type1, $ad_image1, $ad_text1,
                            $ad_title2, $ad_target_url2, $ad_type2, $ad_image2, $ad_text2,
                            $ad_title3, $ad_target_url3, $ad_type3, $ad_image3, $ad_text3,
                            $ad_title4, $ad_target_url4, $ad_type4, $ad_image4, $ad_text4,
                            $banner_title1, $banner_url1, $banner_img1,
                            $banner_title2, $banner_url2, $banner_img2));
    }
} // class end
