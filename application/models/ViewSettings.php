<?php
/**
 * thumbnail.inc.php
 *
 * @author 		Musavir Iftekhar (ali@aliinfotech.com)
 * @copyright 	Copyright 2015
 * @version 	1.0 (PHP5.5)
 *
 */
class Application_Model_ViewSettings{
	
	public static function common($view, $session){
			$view->user_name = $session->user_name;
	} 
	
}