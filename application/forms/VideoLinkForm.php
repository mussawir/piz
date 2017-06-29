<?php
class Application_Form_VideoLinkForm extends Zend_Form
{
public function init() 
	{
				$this->setName('add_video_link');
				$this->setAttrib('enctype', 'multipart/form-data');
				
				$title = new Zend_Form_Element_Text('title',array('disableLoadDefaultDecorators' =>true));
				$title->setRequired(true)
					->setAttrib('id', 'video_title')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
                    ->setAttrib("placeholder", "Enter video title here")
                    ->setAttrib("title", "Enter video title here")
					->removeDecorator('htmlTag');
		
            $url_video = new Zend_Form_Element_Text('url_video',array('disableLoadDefaultDecorators' =>true));
				$url_video->setRequired(true)
					->setAttrib('id', 'url_video')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->addFilter('StripTags')
					->addFilter('StringTrim')
                    ->setAttrib("placeholder", "Enter video url here")
                    ->setAttrib("title", "Enter video url here")
					->removeDecorator('htmlTag'); 
				
				$short_description= new Zend_Form_Element_Textarea('short_description',array('disableLoadDefaultDecorators' =>true));
				$short_description->setRequired(false)
				->setAttrib("id","editor1")
				->setAttrib("class", "form-control")
                ->setAttrib("placeholder", "Enter video description here")
                ->setAttrib("title", "Enter video description here")
				->addFilter('StringTrim');

				$video_img = new Zend_Form_Element_File('video_img');
				$video_img->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('FilesSize',false,array('min' => '1kB', 'max' => '5MB'))
				->addValidator('ImageSize', false,array('minwidth' => 10,'minheight' => 10))
                ->addFilter('StringTrim')
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpeg,jpg,png,gif');// only JPEG, PNG, and GIFs
						
				$is_featured = new Zend_Form_Element_Checkbox('is_featured',array('disableLoadDefaultDecorators' =>true));
				$is_featured->setAttrib("id","is_featured")
				//->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')->setLabel('Show as Featured: ');
                
                $is_featured->setDecorators(array(
                'ViewHelper',
                'Description',
                'Errors',
                array(array('data'=>'HtmlTag'), array('tag' => 'span')),
                array('Label', array('tag' => 'span'))));
                //array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class' => 'row col-md-12'))));
                
				$is_main = new Zend_Form_Element_Checkbox('is_main',array('disableLoadDefaultDecorators' =>true));
				$is_main->setAttrib("id","is_main")
				//->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')->setLabel('Mark as main video: ');
						
                $is_main->setDecorators(array(
                'ViewHelper',
                'Description',
                'Errors',
                array(array('data'=>'HtmlTag'), array('tag' => 'span', 'style' => 'margin-right:100px')),
                array('Label', array('tag' => 'span'))));
                //array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class' => 'row col-md-12'))));
                
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
				$submit->setAttrib('class', 'btn btn-md btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Save");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('title','short_description','url_video','video_img', 'is_featured', 'is_main'));
				
				$this->addElements(array($title,$short_description,$video_img,$url_video,$is_featured,$is_main,$submit));

        }
}