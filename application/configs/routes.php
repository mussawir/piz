<?php
$route = new Zend_Controller_Router_Route(
    ':action',
    array(
        'module'     => 'default',
        'controller' => 'pages'
    ) 
);

$router->addRoute('call', $route);