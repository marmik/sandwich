<?php

class Plugin_Layout extends Zend_Controller_Plugin_Abstract
{

	public function  preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if($request->getModuleName() == 'backend') {
            Zend_Controller_Front::getInstance()
						->getPlugin('Zend_Layout_Controller_Plugin_Layout')
						->getLayout()
						->setLayout('admin');
        }

	}

}