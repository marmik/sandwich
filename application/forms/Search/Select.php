<?php

class Form_Search_Select extends Zend_Dojo_Form
{
	public function  init()
	{
		$this->setMethod('POST');

		$searchElement = new Zend_Dojo_Form_Element_TextBox('search');
		$searchElement->setLabel('Enter domain name:');
		$this->addElement($searchElement);
                
                $searchElement = new Zend_Dojo_Form_Element_TextBox('from');
		$searchElement->setLabel('From:');
		$this->addElement($searchElement);
                
                $searchElement = new Zend_Dojo_Form_Element_TextBox('to');
		$searchElement->setLabel('To:');
		$this->addElement($searchElement);
                
                $searchElement = new Zend_Dojo_Form_Element_CheckBox('wonumbers');
		$searchElement->setLabel('WO/Numbers:');
		$this->addElement($searchElement);
                
                $searchElement = new Zend_Dojo_Form_Element_CheckBox('wodashes');
		$searchElement->setLabel('WO/Dashes:');
		$this->addElement($searchElement);

		$submitElement = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submitElement->setLabel('Search');
		$submitElement->setIgnore(true);
		$this->addElement($submitElement);
	}
}