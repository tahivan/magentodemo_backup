<?php

class Qsoft_ContactUs_Block_System_Config_Source_Option extends
    Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_addAfter = false;

    public function __construct()
    {
        $this->addColumn('option', array(
            'label' => Mage::helper('contactus')->__('Registered Options'),
            'style' => 'width:120px',
        ));

        $this->_addButtonLabel = Mage::helper('contactus')->__('Add Option');

        parent::__construct();
    }
}