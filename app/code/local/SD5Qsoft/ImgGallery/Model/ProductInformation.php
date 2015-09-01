<?php
// app/code/local/SD5Qsoft/ImgGallery_/Model/ProductInformation.php
class SD5Qsoft_ImgGallery_Model_ProductInformation extends Mage_Core_Model_Abstract {

	public function getAllImagesProduct($limit = 10) {
		$products = Mage::getModel("catalog/product")
		            ->getCollection()
		            ->addAttributeToSelect('*')
		            ->addAttributeToFilter('images_gallery', array('notnull' => true))
		            ->setOrder('entity_id', 'DESC')
		            ->setPageSize($limit);

		            // foreach ($products as $item) {
		            // 	var_dump($item -> images_gallery);
		            // 	break;
		            // }

		return $products;
	}

}

?>