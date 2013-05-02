<?php

class View_Helper_FlashMessenger extends Zend_View_Helper_Abstract
{

	public function flashMessenger($translator = NULL)
	{
		// Set up some variables, including the retrieval of all flash messages.

		$messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();

		$output = "";
		foreach ($messages as $message)
		{
			$output .= $message."<br />";
		}

		return $output;
	}
}