<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/24/2015
 * Time: 3:47 PM
 */
class Qsoft_PriceMatrix_Model_Resource_Eav_Mysql4_Product_Attribute_Backend_Media
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Backend_Media
{
    public function loadGallery($product, $object)
    {
        // Select gallery images for product
        $select = $this->_getReadAdapter()->select()
            ->from(
                array('main' => $this->getMainTable()),
                array('value_id', 'value AS file')
            )
            ->joinLeft(
                array('value' => $this->getTable(self::GALLERY_VALUE_TABLE)),
                'main.value_id=value.value_id AND value.store_id=' . (int)$product->getStoreId(),
                array('label', 'position', 'disabled', 'show_in')
            )
            ->joinLeft( // Joining default values
                array('default_value' => $this->getTable(self::GALLERY_VALUE_TABLE)),
                'main.value_id=default_value.value_id AND default_value.store_id=0',
                array(
                    'label_default' => 'label',
                    'position_default' => 'position',
                    'disabled_default' => 'disabled',
                    'show_in_default' => 'show_in'
                )
            )
            ->where('main.attribute_id = ?', $object->getAttribute()->getId())
            ->where('main.entity_id = ?', $product->getId())
            ->order('IF(value.position IS NULL, default_value.position, value.position) ASC');

        $result = $this->_getReadAdapter()->fetchAll($select);
        $this->_removeDuplicates($result);
        return $result;
    }

    public function getMediaGalleryFromOptionType($optionTypeId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(
                array('main' => $this->getTable('qsoft_pricematrix/product_option_type_image')),
                array('option_type_image_id', 'option_type_id', 'gallery_value_id')
            )
            ->join(
                array('product_option_type_value' => $this->getTable('catalog/product_option_type_value')),
                'main.option_type_id=product_option_type_value.option_type_id'
            )
            ->join(
                array('product_attribute_media_gallery' => $this->getTable('catalog/product_attribute_media_gallery')),
                'main.gallery_value_id=product_attribute_media_gallery.value_id', array('value', 'value_id')
            )
            ->join(
                array('product_option_type_title' => $this->getTable('catalog/product_option_type_title')),
                'main.option_type_id=product_option_type_title.option_type_id', array('title')
            )
            ->where('main.option_type_id = ?', $optionTypeId);
//            ->where('main.store_id = ?', 0);
        $result = $this->_getReadAdapter()->fetchRow($select);
        return $result;
    }
}