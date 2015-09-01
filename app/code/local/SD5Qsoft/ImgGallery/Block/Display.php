<?php
	class SD5Qsoft_ImgGallery_Block_Display extends Mage_Core_Block_Template
	{
		public function getAllAttributes() {

			$storeId   = Mage::app()->getStore()->getStoreId();
			$config    = Mage::getModel('eav/config');
			$attribute = $config->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'images_gallery');
			$values    = $attribute->setStoreId($storeId)->getSource()->getAllOptions();
			unset($values[0]);
			return $values;

		}

		public function  getAllImagesProduct($limit = 10) {
			$products = Mage::getModel("sd5qsoft_imggallery/productinformation")->getAllImagesProduct($limit);
			return $products;
		}
		/**
		*
		*  setEntityTypeFilter(4) -> get attr for product
		*	1 = Customer Entity
		*	2 = Shipping Entity (I believe)
		*	3 = Category Entity
		*	4 = Product Entity
		*
		**/

		public function getAllAttrInfor() {

			$attributesInfo = Mage::getResourceModel('eav/entity_attribute_collection')
			->setEntityTypeFilter(4)
			->setCodeFilter(array('images_gallery'))
			->addSetInfo()
			->getData();

			$attributeId = Mage::getResourceModel('eav/entity_attribute')
			->getIdByCode('catalog_product',$attributesInfo[0]['attribute_code']);

			return $attributeId;
		}
	}