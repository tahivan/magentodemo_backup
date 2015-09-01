<?php

/**
 * Copyright [2014] [Dexxtz]
 *
 * @package   Dexxtz_Contactus
 * @author    Dexxtz
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

class Dexxtz_Contactus_Block_System_Config_Source_Department extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{	
	protected $_addAfter = false;
	
    public function __construct()
    {
        $this->addColumn('department', array(
            'label' => Mage::helper('contactus')->__('Registered Options'),
            'style' => 'width:120px',
        ));

        $this->_addButtonLabel = Mage::helper('contactus')->__('Add Option');

        parent::__construct();
    }
}