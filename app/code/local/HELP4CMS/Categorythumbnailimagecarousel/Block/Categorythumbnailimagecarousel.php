<?php
/*
 * Edition : Community 
 * Package: HELP4CMS_Categorythumbnailimagecarousel
 * Author: Mudit Kumawat 
 * Email: muditkumawat19@gmail.com / muditkumawat007@gmail.com
*/

class HELP4CMS_Categorythumbnailimagecarousel_Block_Categorythumbnailimagecarousel extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getCategorythumbnailimagecarousel()     
     { 
        if (!$this->hasData('categorythumbnailimagecarousel')) {
            $this->setData('categorythumbnailimagecarousel', Mage::registry('categorythumbnailimagecarousel'));
        }
        return $this->getData('categorythumbnailimagecarousel');
        
    }
}