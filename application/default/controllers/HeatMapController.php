<?php

class HeatMapController extends Zend_Controller_Action {
    
    private $baseurl = '';
	var $user_session = null;
	private $db = null;
    private $heatmap_model = null;

    public function init() {
		$this->_helper->layout->setLayout('layout');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
		$this->heatmap_model = new Application_Model_HeatmapClicks();		
    }

    public function indexAction()
    {
        $this->ajaxed();
        
        $x_axis = $this->_request->getPost('x');
        $y_axis = $this->_request->getPost('y');
        $location_axis = $this->_request->getPost('l');
        $click_page_id = $this->_request->getPost('click_page_id');
        $click_page_type = $this->_request->getPost('click_page_type');
        
        //var_dump($x_axis .'------'.$y_axis.'------'.$location_axis);
        $data = array('x'=>$x_axis,
         'y'=>$y_axis, 
         'location'=>$location_axis,
         'click_page_id'=>(isset($click_page_id) ? $click_page_id :0),
         'click_page_type'=>(isset($click_page_type) ? $click_page_type :""),
         'created_date'=>date('Y-m-d'));
        $this->heatmap_model->add($data);
    }
    
    public function showHeatmapAction()
    {        
        $this->ajaxed();
        
        $location = $this->_request->getParam('l');
        $location = trim($location);
        $result = $this->heatmap_model->getClickInfo($location);
        
        $html = '<div id="clickmap-container" class=items_'.count($result).'>';
        foreach($result as $row)
        {
            $html .= sprintf('<div style="left:%spx;top:%spx"></div>', ($row['x'] - 10), ($row['y'] - 10));
            //$html .= sprintf('<div class="dp"><span class="x">%s</span><span class="y">%s</span></div>', ($row['x'] - 10), ($row['y'] - 10));
        }
        
        $html .= '</div>'; 
        
        echo $html;
    }
    
    public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        if (!$this->_request->isXmlHttpRequest()){
		  $this->_redirect('index');	
			return; // if not a ajax request leave function
		}
    }
    
    public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
}