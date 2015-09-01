<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/24/2015
 * Time: 3:50 PM
 */
class Qsoft_PriceMatrix_Model_Product_Attribute_Backend_Media
    extends Mage_Catalog_Model_Product_Attribute_Backend_Media
{
    public function afterSave($object)
    {
        if ($object->getIsDuplicate() == true) {
            $this->duplicate($object);
            return;
        }

        $attrCode = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attrCode);
        if (!is_array($value) || !isset($value['images']) || $object->isLockedAttribute($attrCode)) {
            return;
        }
        $toDelete = array();
        $filesToValueIds = array();
        foreach ($value['images'] as &$image) {
            if (!empty($image['removed'])) {
                if (isset($image['value_id'])) {
                    $toDelete[] = $image['value_id'];
                }
                continue;
            }

            if (!isset($image['value_id'])) {
                $data = array();
                $data['entity_id'] = $object->getId();
                $data['attribute_id'] = $this->getAttribute()->getId();
                $data['value'] = $image['file'];
                $image['value_id'] = $this->_getResource()->insertGallery($data);
            }

            $this->_getResource()->deleteGalleryValueInStore($image['value_id'], $object->getStoreId());
            // Add per store labels, position, disabled
            $data = array();
            $data['value_id'] = $image['value_id'];
            $data['label'] = $image['label'];
            $data['show_in'] = $image['show_in'];
            $data['position'] = (int)$image['position'];
            $data['disabled'] = (int)$image['disabled'];
            $data['store_id'] = (int)$object->getStoreId();
            $this->_getResource()->insertGalleryValueInStore($data);
        }

        $this->_getResource()->deleteGallery($toDelete);
    }

}