<?php

class Form_Search_Deletetext extends Zend_Dojo_Form
{
	public function  init()
	{
		$this->setMethod('POST');
                $this->setAction('/domain/delete-text');
                
		$searchElement = new Zend_Dojo_Form_Element_TextBox('deletetext');
		$searchElement->setLabel('Enter number of days to start delete from:');
                $searchElement->setRequired(true);
                $searchElement->addValidator(new Zend_Validate_Between(array('min' => 0, 'max' => 10)));
		$this->addElement($searchElement);
                
                $submitElement = new Zend_Dojo_Form_Element_SubmitButton('delete');
		$submitElement->setLabel('Delete');
		$submitElement->setIgnore(true);
		$this->addElement($submitElement);
	}
}