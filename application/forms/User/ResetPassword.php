<?php

class Form_User_ResetPassword extends Zend_Dojo_Form
{
	public function  init()
	{
		$this->setMethod('POST');

		$passwordElement = new Zend_Dojo_Form_Element_PasswordTextBox('password');
		$passwordElement->setLabel('Password:');
		$passwordElement->setRequired(true);
		$this->addElement($passwordElement);

        $passwordElement = new Zend_Dojo_Form_Element_PasswordTextBox('re_password');
		$passwordElement->setLabel('Repeat Password:');
		$passwordElement->setRequired(true);
		$this->addElement($passwordElement);

		$submitElement = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submitElement->setLabel('Login');
		$submitElement->setIgnore(true);
		$this->addElement($submitElement);
	}
}