<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/24/2015
 * Time: 4:02 PM
 */
class Qsoft_PriceMatrix_Block_Adminhtml_Catalog_Product_Helper_Form_Gallery_Content
    extends Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content
{
    public function getImageOptionsJson()
    {
        $imageOption = $this->getImageOptions();
        if (count($imageOption) > 0) {
            return Mage::helper('core')->jsonEncode($imageOption);
        }
        return '[]';
    }

    public function getImageOptions()
    {
        $imageOption = array();
        $imageOptionId = array();
        $product = Mage::registry('product');
        $options = $product->getOptions();

        if (is_array($this->getElement()->getValue())) {
            $value = $this->getElement()->getValue();
            if (count($value['images']) > 0) {
                foreach ($value['images'] as &$image) {
                    $labelFull = $image['label'];
                    $pos = strpos($labelFull, "|$|");
                    $optionItem = array();
                    foreach ($options as $option) {
                        $checkExist = false;
                        if ($pos) {
                            $selectOptions = substr($labelFull, $pos + 3);
                            foreach (explode(" ", $selectOptions) as $_seloption) {
                                $values = explode(",", $_seloption);
                                if ($values[0] == $option->getId()) {
                                    $optionItem[] = array('id' => $option->getId(), 'value' => $values[1]);
                                    $checkExist = true;
                                    break;
                                } else $checkExist = false;
                            }
                        }
                        if (!$checkExist) {
                            $optionItem[] = array('id' => $option->getId(), 'value' => "0");
                        }
                        $imageOption[$image['value_id']] = $optionItem;
                    }
                }
            }
        }
        return $imageOption;
    }

    public function getImagesJson()
    {
        $imageOption = array();
        $imageOptionId = array();
        $product = Mage::registry('product');
        $options = $product->getOptions();

        if (is_array($this->getElement()->getValue())) {
            $value = $this->getElement()->getValue();
            if (count($value['images']) > 0) {
                foreach ($value['images'] as &$image) {
                    $labelFull = $image['label'];
                    $pos = strpos($labelFull, "|$|");
                    $optionItem = array();
                    foreach ($options as $option) {
                        $checkExist = false;
                        if ($pos) {
                            $selectOptions = substr($labelFull, $pos + 3);
                            foreach (explode(" ", $selectOptions) as $_seloption) {
                                $values = explode(",", $_seloption);
                                if ($values[0] == $option->getId()) {
                                    //$optionItem[] = array('id' => $option->getId(),'value' => $values[1]);
                                    $optionItem[] = "{'id':'" . $option->getId() . "', 'value':'" . $values[1] . "'}";
                                    $checkExist = true;
                                    break;
                                } else $checkExist = false;
                            }
                        }
                        if (!$checkExist) {
                            $optionItem[] = "{'id':'" . $option->getId() . "', 'value':'0'}";
                        }
                    }
                    $image['optionImage'] = $optionItem;
                    $labelFull = $image['label'];
                    $image['url'] = Mage::getSingleton('catalog/product_media_config')->getMediaUrl($image['file']);
                    $image['label_default'] = $image['label'] = $this->getRealImageLabel($labelFull);
                }
                return Mage::helper('core')->jsonEncode($value['images']);
            }
        }
        return '[]';
    }

    function getRealImageLabel($imageLabel)
    {
        $pos = strpos($imageLabel, "|$|");
        if ($pos == false)
            return $imageLabel;
        return substr($imageLabel, 0, $pos - 1);
    }
}