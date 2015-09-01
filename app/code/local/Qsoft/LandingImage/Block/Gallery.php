<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 8/31/2015
 * Time: 11:40 AM
 */
class Qsoft_LandingImage_Block_Gallery extends Mage_Core_Block_Template
{
    /**
     * @desc Get all option of attribute gallery_type
     * @author TuyenNN
     *
     * @return array
     * @throws Mage_Core_Exception
     */
    public function getGalleryType()
    {
        $options = array();
        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'gallery_type');
        if ($attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions(false);
        }
        return $options;
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
        return $collection;
    }

    public function getOptionLabel($strIds)
    {
        $optionValue = array();
        if (!$strIds) {
            return '';
        }
        $arrIds = explode(',', $strIds);
        if (empty($arrIds)) {
            return '';
        }
        $attributeDetails = Mage::getSingleton("eav/config")->getAttribute("catalog_product", 'gallery_type');
        foreach ($arrIds as $id) {
            $optionValue[] = $this->reFormatText($attributeDetails->getSource()->getOptionText($id));
        }
        if (empty($optionValue)) {
            return '';
        }
        return implode(' ', $optionValue);
    }

    public function reFormatText($str)
    {
        return strtolower(str_replace(' ', '_', $str));
    }

    public function getWidth()
    {
        $width = array('320', '420', '240', '460');
        $randomKey = array_rand($width);
        return $width[$randomKey];
    }

    public function  getHeight()
    {
        $height = array('214', '168', '320', '240', '161');
        $randomKey = array_rand($height);
        return $height[$randomKey];
    }

    public function  getColumn()
    {
        $column = array('1', '1', '1', '1', '2');
        $randomKey = array_rand($column);
        return $column[$randomKey];
    }

    public function shortenDesc($str)
    {
        $str = substr($str, 0, 40);
        $str = substr($str, 0, strrpos($str, ' ')) . "...";
        return $str;
    }
}