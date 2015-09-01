<?php

/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/24/2015
 * Time: 11:00 AM
 */
class Qsoft_PriceMatrix_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Selectcustom
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('catalog/product/edit/options/type/selectcustom.phtml');
    }

    protected function _prepareLayout()
    {
        $this->setChild('add_select_row_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Add New Row'),
                    'class' => 'add add-select-row',
                    'id' => 'add_select_row_button_{{option_id}}',
                ))
        );

        $this->setChild('delete_select_row_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Delete Row'),
                    'class' => 'delete delete-select-row icon-btn',
                ))
        );

        return parent::_prepareLayout();
    }

    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_select_row_button');
    }

    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_select_row_button');
    }

    public function getPriceTypeSelectHtml()
    {
        $this->getChild('option_price_type')
            ->setData('id', 'product_option_{{id}}_select_{{select_id}}_price_type')
            ->setName('product[options][{{id}}][values][{{select_id}}][price_type]');

        return parent::getPriceTypeSelectHtml();
    }

    /**
     * Render block Image to select per row
     */
    public function imageBlockRender()
    {
        $product = Mage::registry('product');
        $inputHidden = '<input type="hidden" name="product[options][{{id}}][values][{{select_id}}][image]" id="product_option_{{id}}_select_{{select_id}}_image_selected" value="0"/>';
        $imageSel = '<ul class="selectImage" id="product_option_{{id}}_select_{{select_id}}_image" style="display:block;">';
        $baseDir = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product';
        $urlMedia = trim(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA), '/') . '/catalog/product';
        $urlSkin = trim(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN), '/') . '/adminhtml/default/default/images/';
        //default
        $imageSel .= '<li class="selectImage-item-selected" id="product_option_{{id}}_select_{{select_id}}_image_li_0">';
        $imageSel .= '<a topparentid="product_option_{{id}}_select_{{select_id}}_image" offset="0" href="#" onclick="setImageSelectedToFocus(this);return false;"><img src="' . $urlSkin . 'none_select_image.jpg' . '" alt="" title="" valign="absmiddle"/></a><span id="product_option_{{id}}_select_{{select_id}}_image_lispan_0"></span>';
        $imageSel .= "</li>";
        try {
            $imageOptions = $product->getOptionImages($product);

            $gallery = $product->getData('media_gallery');  //var_dump($gallery); die;
            $images = $gallery['images'];
            $i = 0;
            foreach ($images as $image) {
                if ($image['show_in'] == 1) {
                    $fileName = $baseDir . $image['file'];
                    $fileName = str_replace(DS, '/', $fileName);
                    if (file_exists($fileName) && is_file($fileName)) {
                        $i++;
                        $imageSel .= '<li id="product_option_{{id}}_select_{{select_id}}_image_li_' . $image['value_id'] . '">';
                        $imageSel .= '<a topparentid="product_option_{{id}}_select_{{select_id}}_image" offset="' . $image['value_id'] . '" href="#" onclick="setImageSelectedToFocus(this);return false;"><img src="' . $urlMedia . $image['file'] . '" alt="" title="" valign="absmiddle"/><span id="product_option_{{id}}_select_{{select_id}}_image_lispan_' . $image['value_id'] . '"></span></a>';
                        $imageSel .= "</li>";
                    }
                }

            }
        } catch (Exception $e) {
            //
        }
        $imageSel .= "</ul>";
        return $inputHidden . $imageSel;

        //var_dump($product->getData('media_gallery')); die("<hr/>");
        $options = array(
            array('label' => 'None image', 'value' => "0"),
            array('label' => 'Love You', 'value' => "1"),
            array('label' => 'Like You', 'value' => "2"),
        );
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName('product[options][{{option_id}}][image]')
            ->setId('product_option_{{option_id}}_image')
            ->setTitle('')
            ->setValue(0)
            ->setExtraParams('onchange="dkm(this.id)"')
            ->setClass('select select-product-option-type')
            ->setOptions($options);

        return $select->getHtml();
    }
}