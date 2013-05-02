<?php

class Form_User_Login extends Zend_Dojo_Form
{
	public function  init()
	{
		$this->setMethod('POST');

		$loginElement = new Zend_Dojo_Form_Element_TextBox('email');
		$loginElement->setLabel('Login:');
		$loginElement->setRequired(true);
		$this->addElement($loginElement);

		$passwordElement = new Zend_Dojo_Form_Element_PasswordTextBox('password');
		$passwordElement->setLabel('Password:');
		$passwordElement->setRequired(true);
		$this->addElement($passwordElement);

		$submitElement = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submitElement->setLabel('Login');
		$submitElement->setIgnore(true);
		$this->addElement($submitElement);
	}
}