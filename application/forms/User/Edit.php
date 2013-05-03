<?php

class Form_User_Edit extends Zend_Dojo_Form
{
	public function  init()
    {
        $this->setMethod('POST');

        $element = new Zend_Dojo_Form_Element_ValidationTextBox('email');
        $element->setLabel('Email:');
        $element->setAllowEmpty(false);
        $element->setRequired(true);

//        $element->addValidator(new Zend_Validate_EmailAddress());
//        $element->setRegExp("[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}");
//        $element->setInvalidMessage('Invalid Email Address.');
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_ValidationTextBox('firstname');
        $element->setLabel('Firstname:');
        $element->setAllowEmpty(false);
        $element->setRequired(true);
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_ValidationTextBox('middle');
        $element->setLabel('Middle:');
        $element->setAllowEmpty(false);
        $element->setRequired(true);
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_ValidationTextBox('lastname');
        $element->setLabel('Lastname:');
        $element->setAllowEmpty(false);
        $element->setRequired(true);
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_RadioButton('sex');
        $element->setLabel('Sex:');
        $element->setSeparator('&nbsp;');
        $element->addMultiOption('M', 'M');
        $element->addMultiOption('F', 'F');
        $element->setRequired(true);
        $element->setValue('M');
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_DateTextBox('birth_date');
        $config = Zend_Registry::getInstance()->get('config');

        $element->setDatePattern($config['dateFormat']);
        $element->setLabel('Birth Date:');
        $element->setDescription('Format: '.$element->getDatePattern());
        $element->setAllowEmpty(false);
        $element->setRequired(true);
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_ValidationTextBox('street');
        $element->setLabel('Street:');
        $element->setAllowEmpty(false);
        $element->setRequired(true);
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_ValidationTextBox('house_number');
        $element->setLabel('House Number:');
        $element->setAllowEmpty(false);
        $element->setRequired(true);
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_ValidationTextBox('zipcode');
        $element->setLabel('Zipcode:');
        $element->setAllowEmpty(false);
        $element->setRequired(true);
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_ValidationTextBox('city');
        $element->setLabel('City:');
        $element->setAllowEmpty(false);
        $element->setRequired(true);
        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_RadioButton('active');
        $element->setLabel('Active:');
        $element->setSeparator('&nbsp;');
        $element->addMultiOption(1, 'Yes');
        $element->addMultiOption(0, 'No');
        $element->setRequired(true);
        $element->setValue(0);
        $this->addElement($element);

        $dataTypeColumnDef = Mapper_UserTable::getInstance()->getColumnDefinition('account_type');
        $element = new Zend_Dojo_Form_Element_ComboBox('account_type');

        foreach($dataTypeColumnDef['values'] as $dataType) {
            $element->addMultiOption($dataType,$dataType);
        }

        $element->setLabel('Type');
        $element->setRequired(true);
        $element->setAllowEmpty(false);

        $this->addElement($element);

        $element = new Zend_Dojo_Form_Element_SubmitButton('save');
        $element->setLabel('Save');
        $element->setIgnore(true);
        $this->addElement($element);

        $element = new Zend_Form_Element_Hidden('id');
        $element->removeDecorator('HtmlTag');
        $element->removeDecorator('Label');
        $this->addElement($element);

        $this->setContactInfoDisplayGroup();
        $this->setPersonalInfoDisplayGroup();
        $this->setLocationInfoDisplayGroup();
        $this->setAccoutSettingsDisplayGroup();
    }


    private function setContactInfoDisplayGroup()
    {
        $this->addDisplayGroup(array(
                    'email',
                    'password',
                    're_password'
            ),'contact_info',array('legend' => 'Contact Information'));

        $displayGroup = $this->getDisplayGroup('contact_info');
        $displayGroup->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div'))
        ));
    }
    private function setPersonalInfoDisplayGroup()
    {
        $this->addDisplayGroup(array(
                    'firstname',
                    'middle',
                    'lastname',
                    'sex',
                    'birth_date'
            ),'personal_info',array('legend' => 'Personal Information'));

        $displayGroup = $this->getDisplayGroup('personal_info');
        $displayGroup->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div'))
        ));
    }

    public function setLocationInfoDisplayGroup()
    {
        $this->addDisplayGroup(array(
                    'street',
                    'house_number',
                    'zipcode',
                    'city'
            ),'location_info',array('legend' => 'Location Information'));

        $displayGroup = $this->getDisplayGroup('location_info');
        $displayGroup->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div'))
        ));
    }
    public function setAccoutSettingsDisplayGroup()
    {
        $this->addDisplayGroup(array(
                    'active',
                    'account_type',
                    'save'
            ),'account_settings',array('legend' => 'Account'));

        $displayGroup = $this->getDisplayGroup('account_settings');
        $displayGroup->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div'))
        ));
    }

}