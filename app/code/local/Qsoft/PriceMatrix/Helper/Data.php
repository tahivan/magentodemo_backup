<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/10/2015
 * Time: 3:18 PM
 */
class Qsoft_PriceMatrix_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_qty = 1;
    protected $_unit = '';
    protected $_drop = 0;
    protected $_width = 0;
    protected $_dropOdd = 0;
    protected $_widthOdd = 0;
    protected $_productId = '';
    protected $_productOptions = '';

    protected $_availableMeasure = array();

    protected $_price;
    protected $_optionsPrice = 0;

    public function calculateMatrixPrice($data)
    {
        $this->_prepareParams($data)
            ->getConfigPrice()
            ->applyOptionsPrice()
            ->applyPromotion()
            ->applyTierPrice();
        return ($this->_price < 0) ? 0 : $this->_price;
    }

    /**
     * @param $arrParams
     * @return Qsoft_PriceMatrix_Helper_Data
     */
    protected function _prepareParams($arrParams)
    {
        if (isset($arrParams['qty']) && $arrParams['qty'] != '') {
            $this->_qty = intval($arrParams['qty']);
        }
        if (isset($arrParams['unit']) && $arrParams['unit'] != '') {
            $this->_unit = (int)$arrParams['unit'];
        }
        if (isset($arrParams['drop']) && $arrParams['drop'] != '') {
            $this->_drop = (float)$arrParams['drop'];
        }
        if (isset($arrParams['width']) && $arrParams['width'] != '') {
            $this->_width = (float)$arrParams['width'];
        }
        if (isset($arrParams['drop_odd']) && $arrParams['drop_odd'] != '') {
            $this->_dropOdd = $arrParams['drop_odd'];
        }
        if (isset($arrParams['width_odd']) && $arrParams['width_odd'] != '') {
            $this->_widthOdd = $arrParams['width_odd'];
        }
        if (isset($arrParams['productId']) && $arrParams['productId'] != '') {
            $this->_productId = $arrParams['productId'];
        }
        if (isset($arrParams['options']) && $arrParams['options'] != '') {
            $this->_productOptions = $arrParams['options'];
        }
        return $this->_formatMeasure();
    }

    /**
     * @desc Recalculate drop and width
     *
     * @return $this
     */
    protected function _formatMeasure()
    {
        if ($this->_unit == 1) {
            $this->_drop = (float)(2.54 * ($this->_drop + $this->_dropOdd));
            $this->_width = (float)(2.54 * ($this->_width + $this->_widthOdd));
        } else {
            $this->_drop += $this->_dropOdd;
            $this->_width += $this->_widthOdd;
        }
        $availableMeasure = $this->getListAvailableMeasure();
        if (!in_array($this->_drop, $availableMeasure['drop'])) {
            foreach ($availableMeasure['drop'] as $_drop) {
                if ((int)$_drop > $this->_drop) {
                    $this->_drop = $_drop;
                    break;
                }
            }
        }
        if (!in_array($this->_width, $availableMeasure['width'])) {
            foreach ($availableMeasure['width'] as $_width) {
                if ((int)$_width > $this->_width) {
                    $this->_width = $_width;
                    break;
                }
            }
        }

        return $this;
    }

    /**
     * @desc Get matrix price base on drop and witdth config in backend
     *
     * @return $this
     * @throws Exception
     */
    protected function getConfigPrice()
    {
        $targetPrice = '';
        try {
            $collection = Mage::getModel('qsoft_pricematrix/matrixprice')->getCollection()
                ->addFieldToFilter('`drop`', array('eq' => (string)$this->_drop))
                ->addFieldToFilter('`width`', array('eq' => (string)$this->_width))
                ->addFieldToFilter('`entity_id`', array('eq' => $this->_productId));
            foreach ($collection->getData() as $data) {
                $targetPrice = $data['value'];
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->_price = $targetPrice;
        return $this;
    }

    protected function getListAvailableMeasure()
    {
        if (empty($this->_availableMeasure)) {
            $this->_availableMeasure = Mage::getModel('qsoft_pricematrix/matrixprice')->getAvailableMeasure($this->_productId);
        }
        return $this->_availableMeasure;
    }

    /**
     * @desc Apply options price to matrix price
     *
     * @return $this
     */
    protected function applyOptionsPrice()
    {
        $this->getProductOptionsPrice();
        $this->_price += $this->_optionsPrice;
        return $this;
    }

    /**
     * @desc Calculate custom options price
     *
     * @return $this
     */
    protected function getProductOptionsPrice()
    {
        if ($this->checkEmpty($this->_productOptions)) {
            $optionsCollection = Mage::getSingleton('catalog/product_option')->getCollection();
            $optionsCollection->getSelect()
                ->join(
                    array('option_type_value' => Mage::getSingleton('core/resource')->getTableName('catalog/product_option_type_value')),
                    'option_type_value.option_id = main_table.option_id', array('main_table.option_id', 'option_type_value.option_type_id')
                )
                ->join(
                    array('option_type_price' => Mage::getSingleton('core/resource')->getTableName('catalog/product_option_type_price')),
                    'option_type_price.option_type_id = option_type_value.option_type_id', array('SUM(option_type_price.price) AS options_price')
                )
                ->where($this->getOptionsCondition());
            foreach ($optionsCollection->getData() as $data) {
                if (isset($data['options_price'])) {
                    $this->_optionsPrice = (float)$data['options_price'];
                }
            }
        }
        return $this;
    }

    protected function getOptionsCondition()
    {
        $condition = ' 1=1 ';
        if (!empty($this->_productOptions)) {
            $condition .= 'AND ';
            foreach ($this->_productOptions as $key => $value) {
                if (!is_array($value)) {
                    if ($value == '') {
                        continue;
                    }
                    $condition .= ' ( main_table.option_id = ' . $key . ' AND option_type_value.option_type_id = ' . $value . ' ) OR';
                } else {
                    if (empty($value)) {
                        continue;
                    }
                    foreach ($value as $v) {
                        if ($v == '') {
                            continue;
                        }
                        $condition .= ' ( main_table.option_id = ' . $key . ' AND option_type_value.option_type_id = ' . $v . ' ) OR';
                    }
                }
            }
        }
        $condition = rtrim($condition, 'AND ');
        return rtrim($condition, 'OR');
    }

    /**
     * @desc Apply promotion to matrix price: Special price, group price and any promotion rules
     *
     * @return $this
     */
    protected function applyPromotion()
    {
        $_product = Mage::getModel('catalog/product')->load($this->_productId);
        $regularPrice = number_format($_product->getPrice(), 2);
        $discountedPrice = number_format($_product->getFinalPrice(), 2);
        $discountAmount = (float)$regularPrice - (float)$discountedPrice;
        $this->_price -= $discountAmount;
        return $this;
    }

    /**
     * @desc Apply tier price to matrix price
     *
     * @return $this
     */
    protected function applyTierPrice()
    {
        $this->_price *= $this->_qty;
        $_product = Mage::getModel('catalog/product')->load($this->_productId);
        $tierPrice = Mage::app()->getLayout()->getBlockSingleton('catalog/product_price')->getTierPrices($_product);
        if (!empty($tierPrice)) {
            foreach ($tierPrice as $_tier) {
                if ($this->_qty >= $_tier['price_qty']) {
                    $this->_price = $_tier['website_price'] * $this->_qty;
                }
            }
        }
        return $this;
    }

    public function checkEmpty($var)
    {
        if (!is_array($var) && $var !== '') {
            return true;
        } else {
            foreach ($var as $v) {
                return $this->checkEmpty($v);
            }
            return false;
        }
    }

    public function useAsChooseImg($type)
    {
        $targetArray = array('radio', 'drop_down', 'checkbox');
        if (!in_array($type, $targetArray)) {
            return false;
        }
        return true;
    }
}