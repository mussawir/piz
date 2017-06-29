<?php
class Zend_View_Helper_BaseUrl
{
	function baseUrl()
	{
		$fc = Zend_Controller_Front::getInstance();
		return $fc->getBaseUrl(); //actual base url function
		/*
$request = Zend_Controller_Front::getInstance()->getRequest();
$url = $request->getScheme() . '://' . $request->getHttpHost();
return $url;
*/	
	}
}