<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/20/2015
 * Time: 4:50 PM
 */
class Qsoft_PriceMatrix_IndexController extends Mage_Core_Controller_Front_Action
{

    protected function _construct()
    {

        parent::_construct();
    }

    public function calculateAction()
    {
        $params = $this->_request->getParams();
        $price = Mage::helper('qsoft_pricematrix')->calculateMatrixPrice($params);
        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('price' => $price)));
    }

}