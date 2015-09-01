<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/26/2015
 * Time: 1:21 AM
 */
class Qsoft_PriceMatrix_Block_Product_View_Color extends Mage_Catalog_Block_Product_View_Options
{
    const COLOR = 'color';

    public function getColorOption()
    {
        $this->getOptions();
        foreach ($this->getOptions() as $opt) {
            if (strtolower($opt->getTitle()) == self::COLOR) {
                return $opt;
            }
        }
    }


    public function getOptionImageUrl($valueId, &$title = '')
    {
        $imgUrl = '';
        $optionGallery = Mage::getResourceModel('catalog/product_attribute_backend_media')->getMediaGalleryFromOptionType($valueId);
        if (!empty($optionGallery)) {
            if (isset($optionGallery['title'])) {
                $title = $optionGallery['title'];
            }
            if (isset($optionGallery['value'])) {
                $imgUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product/' . $optionGallery['value'];
            }
        }
        return $imgUrl;
    }

    public function getMeasureAction()
    {
        return $this->getUrl('matrix/index/calculate');
    }

    public function getCurrentCurrency()
    {
        $currencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        $currencySymbol = Mage::app()->getLocale()->currency($currencyCode)->getSymbol();
        return $currencySymbol;
    }

    public function getMinimumMatrixPrice()
    {
        $currentProduct = Mage::registry('current_product');
        $collection = Mage::getModel('qsoft_pricematrix/matrixprice')
            ->getCollection()
            ->addFieldToFilter('`entity_id`', array('eq' => $currentProduct->getId()))
            ->setOrder('value', 'ASC')
            ->getFirstItem();
        $minMatrixPrice = $collection->getValue() ? $this->getCurrentCurrency() . (float)$collection->getValue() : '';
        return $minMatrixPrice;
    }
}