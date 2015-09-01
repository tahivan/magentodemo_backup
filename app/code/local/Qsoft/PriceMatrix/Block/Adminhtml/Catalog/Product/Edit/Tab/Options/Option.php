<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/24/2015
 * Time: 11:34 AM
 */
class Qsoft_PriceMatrix_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Option
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Option
{
    public function getTemplatesHtml()
    {
        $templates = $this->getChildHtml('text_option_type') . "\n" .
            $this->getChildHtml('file_option_type') . "\n" .
            $this->getChildHtml('selectcustom_option_type') . "\n" .
            $this->getChildHtml('date_option_type');
        return $templates;
    }

    public function getOptionValues()
    {
        $optionsArr = array_reverse($this->getProduct()->getOptions(), true);
//        $optionsArr = $this->getProduct()->getOptions();

        if (!$this->_values) {
            $values = array();
            $scope = (int)Mage::app()->getStore()->getConfig(Mage_Core_Model_Store::XML_PATH_PRICE_SCOPE);
            foreach ($optionsArr as $option) {
                /* @var $option Mage_Catalog_Model_Product_Option */

                $this->setItemCount($option->getOptionId());

                $value = array();

                $value['id'] = $option->getOptionId();
                $value['item_count'] = $this->getItemCount();
                $value['option_id'] = $option->getOptionId();
                $value['title'] = $this->htmlEscape($option->getTitle());
                $value['type'] = $option->getType();
                $value['is_require'] = $option->getIsRequire();
                $value['sort_order'] = $option->getSortOrder();

                if ($this->getProduct()->getStoreId() != '0') {
                    $value['checkboxScopeTitle'] = $this->getCheckboxScopeHtml($option->getOptionId(), 'title', is_null($option->getStoreTitle()));
                    $value['scopeTitleDisabled'] = is_null($option->getStoreTitle()) ? 'disabled' : null;
                }

                if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
//                    $valuesArr = array_reverse($option->getValues(), true);

                    $imageOptions = $this->getProduct()->getOptionImages($this->getProduct());
                    $imageOptMapping = array();
                    foreach ($imageOptions as $imageOption) {
                        $imageOptMapping[$imageOption['option_type_id']] = array(
                            'imageId' => $imageOption['value_id'],
                            'imageFileName' => $imageOption['value'],
                        );
                    }

                    $i = 0;
                    $itemCount = 0;//var_dump($option->getValues()); die;
                    foreach ($option->getValues() as $_value) {
                        $optionTypeId = $_value->getOptionTypeId();
                        /* @var $_value Mage_Catalog_Model_Product_Option_Value */
                        $value['optionValues'][$i] = array(
                            'item_count' => max($itemCount, $_value->getOptionTypeId()),
                            'option_id' => $_value->getOptionId(),
                            'option_type_id' => $optionTypeId,
                            'title' => $this->htmlEscape($_value->getTitle()),
                            'price' => $this->getPriceValue($_value->getPrice(), $_value->getPriceType()),
                            'price_type' => $_value->getPriceType(),
                            'sku' => $this->htmlEscape($_value->getSku()),
                            'sort_order' => $_value->getSortOrder(),
                        );
                        if (isset($imageOptMapping[$optionTypeId])) {
                            $value['optionValues'][$i]['imageId'] = $imageOptMapping[$optionTypeId]['imageId'];
                            $value['optionValues'][$i]['imageFileName'] = $imageOptMapping[$optionTypeId]['imageFileName'];
                        } else {
                            $value['optionValues'][$i]['imageId'] = 0;
                            $value['optionValues'][$i]['imageFileName'] = null;
                        }

                        if ($this->getProduct()->getStoreId() != '0') {
                            $value['optionValues'][$i]['checkboxScopeTitle'] = $this->getCheckboxScopeHtml($_value->getOptionId(), 'title', is_null($_value->getStoreTitle()), $_value->getOptionTypeId());
                            $value['optionValues'][$i]['scopeTitleDisabled'] = is_null($_value->getStoreTitle()) ? 'disabled' : null;
                            if ($scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE) {
                                $value['optionValues'][$i]['checkboxScopePrice'] = $this->getCheckboxScopeHtml($_value->getOptionId(), 'price', is_null($_value->getstorePrice()), $_value->getOptionTypeId());
                                $value['optionValues'][$i]['scopePriceDisabled'] = is_null($_value->getStorePrice()) ? 'disabled' : null;
                            }
                        }
                        $i++;
                    }
                } else {
                    $value['price'] = $this->getPriceValue($option->getPrice(), $option->getPriceType());
                    $value['price_type'] = $option->getPriceType();
                    $value['sku'] = $this->htmlEscape($option->getSku());
                    $value['max_characters'] = $option->getMaxCharacters();
                    $value['description'] = $option->getDescription();
                    $value['file_extension'] = $option->getFileExtension();
                    $value['image_size_x'] = $option->getImageSizeX();
                    $value['image_size_y'] = $option->getImageSizeY();
                    if ($this->getProduct()->getStoreId() != '0' && $scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE) {
                        $value['checkboxScopePrice'] = $this->getCheckboxScopeHtml($option->getOptionId(), 'price', is_null($option->getStorePrice()));
                        $value['scopePriceDisabled'] = is_null($option->getStorePrice()) ? 'disabled' : null;
                    }
                }
                $values[] = new Varien_Object($value);
            }
            $this->_values = $values;
        }

        return $this->_values;
    }
}