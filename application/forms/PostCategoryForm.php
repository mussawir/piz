<?php
class Application_Form_PostCategoryForm extends Zend_Form
{
        public function init() 
        {
				$this->setName('post_category');
				$this->setAttrib('enctype', 'multipart/form-data');
                $this->setMethod('post');
				
				$cat_name = new Zend_Form_Element_Text('name',array('disableLoadDefaultDecorators' =>true));
				$cat_name->setRequired(true)
					->setAttrib('id', 'cat_name')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
                    ->setAttrib("placeholder", "Enter category name")
                    ->setAttrib("title", "Enter category name")
					->removeDecorator('htmlTag');
		
                $parent = new Zend_Form_Element_Select('parent_id',array('disableLoadDefaultDecorators' =>true));
				$parent->setAttrib("class", "form-control")
    				->addFilter('StripTags')
    				->addFilter('StringTrim')
    				->removeDecorator('htmlTag');
				
                $parent->addMultiOption(0, $value = "None");
                $list = $this->categoryTree();
                foreach($list as $result){
                    $parent->addMultiOption($result['category_id'], $value = $result['name']);
				}
                
                $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-button');
				$submit->setAttrib('class', 'btn btn-primary pull-right')
					->removeDecorator('HtmlTag')
					->removeDecorator('Label')
					->setLabel("Add New Category");
                
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),
				array('name','parent_id'));
				
				$this->addElements(array($cat_name, $parent, $submit));

        }
        
        private function categoryTree($parent_id = 0, $spacing = '', $tree_array = '')
        {
            $post_category_model = new Application_Model_PostCategories();
            $result = $post_category_model->getCategoriesByParent($parent_id);
            
            if (!is_array($tree_array)){
                $tree_array = array();
            }
            
            if (isset($result)) {
                foreach($result as $r) {
                    $tree_array[] = array("category_id" => $r['category_id'], "name" => ($spacing . $r['name']));
                    $tree_array = $this->categoryTree($r['category_id'], ('&nbsp;&nbsp;'.$spacing . '&nbsp;'), $tree_array);
                }
            }        
            return $tree_array;
        }
}