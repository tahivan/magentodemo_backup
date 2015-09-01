<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/10/2015
 * Time: 3:51 PM
 */
class Qsoft_PriceMatrix_Model_Observer
{

    public function adminhtmlCatalogProductEditPrepareForm(Varien_Event_Observer $observer)
    {
        $form = $observer->getEvent()->getForm();
        $matrixPrice = $form->getElement('matrix_price');
        if ($matrixPrice) {
            $matrixPrice->setRenderer(
                Mage::app()->getLayout()->createBlock('qsoft_pricematrix/adminhtml_catalog_product_edit_tab_price_matrix')
            );
        }
    }

    /**
     * @desc Set matrix price as quote custom price
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkoutCartProductAddAfter(Varien_Event_Observer $observer)
    {
        $params = Mage::app()->getRequest()->getParams();
        $customPrice = Mage::helper('qsoft_pricematrix')->calculateMatrixPrice($params);
        $quote_item = $observer->getEvent()->getQuoteItem();
        $quote_item->setOriginalCustomPrice($customPrice);
        $quote_item->getQuote()->save();
    }
}